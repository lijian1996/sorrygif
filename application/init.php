<?php

define('EXT', '.php');
define('DS', DIRECTORY_SEPARATOR);
defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).DS.'application' . DS);
defined('ROOT_PATH') or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);
defined('TEMP_PATH') or define('TEMP_PATH', ROOT_PATH . 'temp' . DS);
defined('LOG_PATH') or define('LOG_PATH', TEMP_PATH . 'log' . DS);
defined('CACHE_PATH') or define('CACHE_PATH', TEMP_PATH . 'cache' . DS);
defined('CONF_PATH') or define('CONF_PATH', ROOT_PATH.'config'. DS);
defined('PUBLIC_PATH') or define('PUBLIC_PATH', '/public/');
defined('VIEW_PATH') or define('VIEW_PATH',  DS.'view'.DS);
defined('TEMPLATE_PATH') or define('TEMPLATE_PATH',PUBLIC_PATH.'template/');
defined('CONF_EXT') or define('CONF_EXT', EXT);
defined('CLI_MOD') or define('CLI_MOD', PHP_SAPI == 'cli'?true:false);
defined('CGI_MOD') or define('CGI_MOD', strpos(PHP_SAPI, 'cgi') === 0?true:false);


