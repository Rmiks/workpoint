<?php

/**
 * For best experience install deployer from http://deployer.org/
 *
 * Usage: dep deploy stage
 * where stage can be omitted and default stage (staging) will be used instead
 * It is also possible to execute deployer from composer vendor folder
 * Usage: shared/vendor/deployer/deployer/bin/dep deploy stage
 */

namespace Deployer;

use Deployer\Task\Context;

require realpath( dirname( __FILE__ ) ) . '/../../shared/vendor/deployer/deployer/recipe/common.php';


serverList( 'config/deploy/servers.yml' );
set( 'keep_releases', 3 );

set( 'bin/composer', function()
{
    if( commandExist( 'composer' ) )
    {
        $composer = run( 'which composer' )->toString();
    }

    if( empty( $composer ) )
    {
        run( "cd {{deploy_path}}/shared && curl -sS https://getcomposer.org/installer | {{bin/php}}" );
        $composer = '{{bin/php}} {{deploy_path}}/shared/composer.phar';
    }

    return $composer;
} );

task( 'leaf:check', function()
{
    run( 'ssh -oStrictHostKeyChecking=no -T git@git.cubesystems.lv' );

    run( "mkdir -p {{deploy_path}}/shared/certs" );
    run( "mkdir -p {{deploy_path}}/shared/files" );
    run( "mkdir -p {{deploy_path}}/shared/cache" );


    $config = "<?php 
    
\"$'\x24'\"config['dbconfig'] = array(
    'hostspec' => '',    
    'database' => '',     
    'username' => '',      
    'password' => '',    
    'mysql_set_utf8' => true,   
);

\"$'\x24'\"config['errorReport'] = array(
    'key' => '', 
);";

    run( "if [ ! -f {{deploy_path}}/shared/config.php ]; then echo \"$config\" > {{deploy_path}}/shared/config.php; fi" );

    # setup LEAF_PRODUCTION env variable to shell and crontab
    run( "if (crontab -l | grep -q LEAF_PRODUCTION=1) ;  then echo /dev/null; else { crontab -l; echo \"LEAF_PRODUCTION=1\"; } | crontab -; fi" );
    run( "if (grep -q LEAF_PRODUCTION=1 ~/.profile) ;  then echo /dev/null; else  echo \"export LEAF_PRODUCTION=1\" >> ~/.profile; fi" );
} );

task( 'leaf:cleanup', function()
{
    run( "rm -Rf {{release_path}}/deploy.php" );
    run( "rm -Rf {{release_path}}/config/deploy" );
    run( "rm -Rf {{release_path}}/.gitignore" );
    run( "rm -Rf {{release_path}}/.git" );
    run( "rm -Rf {{release_path}}/README.md" );
} );

task( 'leaf:finalize', function()
{
    # write current REVISION to database
    run( "bash -l -c 'php {{release_path}}/cli/update_revision.php'" );
    # update crontab
    run( "bash -l -c 'php {{release_path}}/cli/update_crontab.php'" );
    run( "export LEAF_PRODUCTION=1 && php {{release_path}}/cli/migrate.php -v" );
} );

task( 'leaf:set_current_revision', function()
{
    $repository = trim( get( 'repository' ) );
    $branch     = get( 'branch' );
    $git        = get( 'bin/git' );
    $revision   = run( "$git ls-remote $repository | awk '/$branch/ {print \$1}'" );
    set( 'revision', $revision );
    run( "echo $revision >> {{release_path}}/REVISION" );
} );

task( 'leaf:link_directories', function()
{
    run( "ln -nfs {{deploy_path}}/shared/cache {{release_path}}/shared/cache" );
    run( "ln -nfs {{deploy_path}}/shared/files {{release_path}}/files" );
} );

task( 'leaf:assets:version', function()
{
    run( "bash -l -c 'php {{release_path}}/cli/update_asset_hashes.php'" );
} );

task( 'leaf:assets:build', function()
{
    runLocally( 'yarn install' );
    runLocally( 'npm run build' );
} );

task( 'leaf:assets:upload', function()
{
    upload( 'styles', '{{release_path}}/styles' );
    upload( 'images', '{{release_path}}/images' );
    upload( 'js', '{{release_path}}/js' );
} );


task( "sync:files:download", function()
{
    $server = Context::get()->getServer();
    $host   = $server->getConfiguration()->getHost();
    $user   = $server->getConfiguration()->getUser();
    $port   = $server->getConfiguration()->getPort();
    runLocally( "scp -P $port -r $user@$host:app/current/files ./" );
} );
task( "sync:files:upload", function()
{
    upload( 'files', '{{release_path}}/files' );
} )->onlyForStage( "staging" );

task( "sync:db:import", function()
{
    $server   = Context::get()->getServer();
    $host     = $server->getConfiguration()->getHost();
    $user     = $server->getConfiguration()->getUser();
    $port     = $server->getConfiguration()->getPort();    
    $stage    = input()->getArgument( 'stage' ) ? input()->getArgument( 'stage' ) : get( "default_stage" );

    $gtidFix  = $stage === "staging" ? "--set-gtid-purged=OFF" : "";

    $remoteDb = ask( "enter remote db name: " );
    $localDb  = ask( "enter local db name: ", $remoteDb );
    
    run( "mysqldump $remoteDb $gtidFix > {{deploy_path}}/shared/dump.sql" );
    runLocally( "scp -P $port $user@$host:{{deploy_path}}/shared/dump.sql ./" );
    runLocally( "mysql $localDb < dump.sql" );
    runLocally( "rm dump.sql" );
    run( "rm {{deploy_path}}/shared/dump.sql" );
} );

task( "sync:db:export", function()
{
    $server = Context::get()->getServer();
    $host   = $server->getConfiguration()->getHost();
    $user   = $server->getConfiguration()->getUser();
    $port   = $server->getConfiguration()->getPort();

    $remoteDb = ask( "enter remote db name: " );
    $localDb  = ask( "enter local db name: ", $remoteDb );

    runLocally( "mysqldump $localDb > dump.sql" );
    runLocally( "scp -P $port dump.sql $user@$host:{{deploy_path}}/shared/dump.sql" );
    run( "mysql $remoteDb < {{deploy_path}}/shared/dump.sql" );
    runLocally( "rm dump.sql" );
    run( "rm {{deploy_path}}/shared/dump.sql" );
} )->onlyForStage( "staging" );

after( 'rollback', 'leaf:assets:version' );

/**
 * Main task
 */
task( 'deploy', [
    'deploy:prepare',
    'leaf:check',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'leaf:set_current_revision',
    'leaf:link_directories',
    'deploy:vendors',
    'leaf:assets:build',
    'leaf:assets:upload',
    'leaf:assets:version',
    'deploy:symlink',
    'leaf:finalize',
    'cleanup',
    'leaf:cleanup'
] )->desc( 'Deploy your project' );

/**
 * Run this to setup remote folders first without pulling code
 */
task( 'check', [
    'deploy:prepare',
    'leaf:check'
] );

task( 'sync:up', [
    'sync:db:export',
    'sync:files:upload',
    'leaf:assets:version',
    'leaf:finalize'
] )->onlyForStage( "staging" );

task( 'sync:down', [
    'sync:db:import',
    'sync:files:download'
] );
