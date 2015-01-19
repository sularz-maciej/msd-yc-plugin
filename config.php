<?php
// Plugin constants 
if( !defined('MSD_PLUGIN_VERSION') )       define('MSD_PLUGIN_VERSION',       '1.0b' );
if( !defined('MSD_PLUGIN_NAME') )          define('MSD_PLUGIN_NAME',          'MSDesign YouTube Channel Plugin' );

// Directory Names constants
if( !defined('MSD_PLUGIN_DIR') )           define('MSD_PLUGIN_DIR',           __FILE__ );
if( !defined('MSD_PLUGIN_DIR_STYLES') )    define('MSD_PLUGIN_DIR_STYLES',    'css' );
if( !defined('MSD_PLUGIN_DIR_IMAGES') )    define('MSD_PLUGIN_DIR_IMAGES',    'images' );
if( !defined('MSD_PLUGIN_DIR_INCLUDES') )  define('MSD_PLUGIN_DIR_INCLUDES',  'inc' );
if( !defined('MSD_PLUGIN_DIR_RESOURCES') ) define('MSD_PLUGIN_DIR_RESOURCES', 'resources' );
if( !defined('MSD_PLUGIN_DIR_SCRIPTS') )   define('MSD_PLUGIN_DIR_SCRIPTS',   'scripts' );

// URL address constants
if( !defined('MSD_PLUGIN_URL') )           define('MSD_PLUGIN_URL',           plugins_url(null,   __FILE__) );
if( !defined('MSD_PLUGIN_URL_STYLES') )    define('MSD_PLUGIN_URL_STYLES',    plugins_url(MSD_PLUGIN_DIR_STYLES,    __FILE__) );
if( !defined('MSD_PLUGIN_URL_IMAGES') )    define('MSD_PLUGIN_URL_IMAGES',    plugins_url(MSD_PLUGIN_DIR_IMAGES,    __FILE__) );
if( !defined('MSD_PLUGIN_URL_INCLUDES') )  define('MSD_PLUGIN_URL_INCLUDES',  plugins_url(MSD_PLUGIN_DIR_INCLUDES,  __FILE__) );
if( !defined('MSD_PLUGIN_URL_RESOURCES') ) define('MSD_PLUGIN_URL_RESOURCES', plugins_url(MSD_PLUGIN_DIR_RESOURCES, __FILE__) );
if( !defined('MSD_PLUGIN_URL_SCRIPTS') )   define('MSD_PLUGIN_URL_SCRIPTS',   plugins_url(MSD_PLUGIN_DIR_SCRIPTS,   __FILE__) );
?>
