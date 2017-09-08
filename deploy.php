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
require 'config/deploy/recipe.php';

set( 'repository', 'git@git.cubesystems.lv:cube/leaf-bundle.git' );
set( 'branch', 'master' );
set( 'default_stage', 'staging' );

set('ssh_type', 'native');
set('ssh_multiplexing', true);