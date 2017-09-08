<?php
require_once( dirname( __FILE__ ) . '/prepend.cli.php' );
$options = getopt( 'd::' );
$dryRun  = isset( $options[ 'd' ] );
dbSetQuerySeparation( false );
$classMap = require SHARED_PATH . '/vendor/composer/autoload_classmap.php';
$loader   = new \Composer\Autoload\ClassLoader();
$loader->addClassMap( $classMap );
foreach( $loader->getClassMap() as $class => $path )
{
    if( preg_match( '/custom_apis/', $path ) && method_exists( $class, '_autoload' ) )
    {
        $class::_autoload( $class );
    }
}


// maintain all known tables
$table_defs = getTableDefinitions();

foreach( $table_defs as $tableName => $table )
{
    maintainTable( $tableName, null, $table_defs, $dryRun );
}
// Recompile xml_templates with XO tables
$xmlize                      = new xmlize();
$xmlize->main_xml_file_mtime = time(); // Force recompile proccess
$q                           = "SELECT template_path FROM `xml_templates_list` WHERE `table` IS NOT NULL";
$xo_templates                = dbGetAll( $q, $key = null, $value = 'template_path' );
if( sizeof( $xo_templates ) > 0 )
{
    foreach( $xo_templates as $template )
    {
        if( file_exists( $xmlize->templates_path . $template . '.xml' ) )
        {
            $xmlize->recompileTemplate( $template );
        }
    }
}