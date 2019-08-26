<?php
/**
 * This is core configuration file.
 *
 * Use it to configure core behavior of Cake.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * CakePHP Debug Level:
 *
 * Production Mode:
 * 	0: No error messages, errors, or warnings shown. Flash messages redirect.
 *
 * Development Mode:
 * 	1: Errors and warnings shown, model caches refreshed, flash messages halted.
 * 	2: As in 1, but also with full debug messages and SQL output.
 *
 * In production mode, flash messages redirect after a time interval.
 * In development mode, you need to click the flash message to continue.
 */
	Configure::write('debug', 2);

/**
 * Configure the Error handler used to handle errors for your application. By default
 * ErrorHandler::handleError() is used. It will display errors using Debugger, when debug > 0
 * and log errors with CakeLog when debug = 0.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle errors. You can set this to any callable type,
 *   including anonymous functions.
 *   Make sure you add App::uses('MyHandler', 'Error'); when using a custom handler class
 * - `level` - integer - The level of errors you are interested in capturing.
 * - `trace` - boolean - Include stack traces for errors in log files.
 *
 * @see ErrorHandler for more information on error handling and configuration.
 */
	Configure::write('Error', array(
		'handler' => 'ErrorHandler::handleError',
		'level' => E_ALL & ~E_DEPRECATED,
		'trace' => true
	));

/**
 * Configure the Exception handler used for uncaught exceptions. By default,
 * ErrorHandler::handleException() is used. It will display a HTML page for the exception, and
 * while debug > 0, framework errors like Missing Controller will be displayed. When debug = 0,
 * framework errors will be coerced into generic HTTP errors.
 *
 * Options:
 *
 * - `handler` - callback - The callback to handle exceptions. You can set this to any callback type,
 *   including anonymous functions.
 *   Make sure you add App::uses('MyHandler', 'Error'); when using a custom handler class
 * - `renderer` - string - The class responsible for rendering uncaught exceptions. If you choose a custom class you
 *   should place the file for that class in app/Lib/Error. This class needs to implement a render method.
 * - `log` - boolean - Should Exceptions be logged?
 * - `skipLog` - array - list of exceptions to skip for logging. Exceptions that
 *   extend one of the listed exceptions will also be skipped for logging.
 *   Example: `'skipLog' => array('NotFoundException', 'UnauthorizedException')`
 *
 * @see ErrorHandler for more information on exception handling and configuration.
 */
	Configure::write('Exception', array(
		'handler' => 'ErrorHandler::handleException',
		'renderer' => 'ExceptionRenderer',
		'log' => true
	));

/**
 * Application wide charset encoding
 */
	Configure::write('App.encoding', 'UTF-8');

/**
 * To configure CakePHP *not* to use mod_rewrite and to
 * use CakePHP pretty URLs, remove these .htaccess
 * files:
 *
 * /.htaccess
 * /app/.htaccess
 * /app/webroot/.htaccess
 *
 * And uncomment the App.baseUrl below. But keep in mind
 * that plugin assets such as images, CSS and JavaScript files
 * will not work without URL rewriting!
 * To work around this issue you should either symlink or copy
 * the plugin assets into you app's webroot directory. This is
 * recommended even when you are using mod_rewrite. Handling static
 * assets through the Dispatcher is incredibly inefficient and
 * included primarily as a development convenience - and
 * thus not recommended for production applications.
 */
	//Configure::write('App.baseUrl', env('SCRIPT_NAME'));

/**
 * To configure CakePHP to use a particular domain URL
 * for any URL generation inside the application, set the following
 * configuration variable to the http(s) address to your domain. This
 * will override the automatic detection of full base URL and can be
 * useful when generating links from the CLI (e.g. sending emails)
 */
	//Configure::write('App.fullBaseUrl', 'http://example.com');

/**
 * Web path to the public images directory under webroot.
 * If not set defaults to 'img/'
 */
	//Configure::write('App.imageBaseUrl', 'img/');

/**
 * Web path to the CSS files directory under webroot.
 * If not set defaults to 'css/'
 */
	//Configure::write('App.cssBaseUrl', 'css/');

/**
 * Web path to the js files directory under webroot.
 * If not set defaults to 'js/'
 */
	//Configure::write('App.jsBaseUrl', 'js/');

/**
 * Uncomment the define below to use CakePHP prefix routes.
 *
 * The value of the define determines the names of the routes
 * and their associated controller actions:
 *
 * Set to an array of prefixes you want to use in your application. Use for
 * admin or other prefixed routes.
 *
 * 	Routing.prefixes = array('admin', 'manager');
 *
 * Enables:
 *	`admin_index()` and `/admin/controller/index`
 *	`manager_index()` and `/manager/controller/index`
 *
 */
	Configure::write('Routing.prefixes', array('admin','barber'));

/**
 * Turn off all caching application-wide.
 *
 */
	Configure::write('Cache.disable', true);

/**
 * Enable cache checking.
 *
 * If set to true, for view caching you must still use the controller
 * public $cacheAction inside your controllers to define caching settings.
 * You can either set it controller-wide by setting public $cacheAction = true,
 * or in each action using $this->cacheAction = true.
 *
 */
	//Configure::write('Cache.check', true);

/**
 * Enable cache view prefixes.
 *
 * If set it will be prepended to the cache name for view file caching. This is
 * helpful if you deploy the same application via multiple subdomains and languages,
 * for instance. Each version can then have its own view cache namespace.
 * Note: The final cache file name will then be `prefix_cachefilename`.
 */
	//Configure::write('Cache.viewPrefix', 'prefix');

/**
 * Session configuration.
 *
 * Contains an array of settings to use for session configuration. The defaults key is
 * used to define a default preset to use for sessions, any settings declared here will override
 * the settings of the default config.
 *
 * ## Options
 *
 * - `Session.cookie` - The name of the cookie to use. Defaults to 'CAKEPHP'
 * - `Session.timeout` - The number of minutes you want sessions to live for. This timeout is handled by CakePHP
 * - `Session.cookieTimeout` - The number of minutes you want session cookies to live for.
 * - `Session.checkAgent` - Do you want the user agent to be checked when starting sessions? You might want to set the
 *    value to false, when dealing with older versions of IE, Chrome Frame or certain web-browsing devices and AJAX
 * - `Session.defaults` - The default configuration set to use as a basis for your session.
 *    There are four builtins: php, cake, cache, database.
 * - `Session.handler` - Can be used to enable a custom session handler. Expects an array of callables,
 *    that can be used with `session_save_handler`. Using this option will automatically add `session.save_handler`
 *    to the ini array.
 * - `Session.autoRegenerate` - Enabling this setting, turns on automatic renewal of sessions, and
 *    sessionids that change frequently. See CakeSession::$requestCountdown.
 * - `Session.ini` - An associative array of additional ini values to set.
 *
 * The built in defaults are:
 *
 * - 'php' - Uses settings defined in your php.ini.
 * - 'cake' - Saves session files in CakePHP's /tmp directory.
 * - 'database' - Uses CakePHP's database sessions.
 * - 'cache' - Use the Cache class to save sessions.
 *
 * To define a custom session handler, save it at /app/Model/Datasource/Session/<name>.php.
 * Make sure the class implements `CakeSessionHandlerInterface` and set Session.handler to <name>
 *
 * To use database sessions, run the app/Config/Schema/sessions.php schema using
 * the cake shell command: cake schema create Sessions
 *
 */
Configure::write('Session', array(
    'defaults' => 'cake',
    'timeout' => 2160, // 36 hours
    'ini' => array(
        'session.gc_maxlifetime' => 129600 // 36 hours
    )
));

/**
 * A random string used in security hashing methods.
 */
	Configure::write('Security.salt', 'DYhG93b0qySUNYJfIxfs2guVoUubWwvniR2G0FgaC9mi');

/**
 * A random numeric string (digits only) used to encrypt/decrypt strings.
 */
	Configure::write('Security.cipherSeed', '768593096574535424967496836450143');

/**
 * Apply timestamps with the last modified time to static assets (js, css, images).
 * Will append a query string parameter containing the time the file was modified. This is
 * useful for invalidating browser caches.
 *
 * Set to `true` to apply timestamps when debug > 0. Set to 'force' to always enable
 * timestamping regardless of debug value.
 */
	//Configure::write('Asset.timestamp', true);

/**
 * Compress CSS output by removing comments, whitespace, repeating tags, etc.
 * This requires a/var/cache directory to be writable by the web server for caching.
 * and /vendors/csspp/csspp.php
 *
 * To use, prefix the CSS link URL with '/ccss/' instead of '/css/' or use HtmlHelper::css().
 */
	//Configure::write('Asset.filter.css', 'css.php');

/**
 * Plug in your own custom JavaScript compressor by dropping a script in your webroot to handle the
 * output, and setting the config below to the name of the script.
 *
 * To use, prefix your JavaScript link URLs with '/cjs/' instead of '/js/' or use JsHelper::link().
 */
	//Configure::write('Asset.filter.js', 'custom_javascript_output_filter.php');

/**
 * The class name and database used in CakePHP's
 * access control lists.
 */
	Configure::write('Acl.classname', 'DbAcl');
	Configure::write('Acl.database', 'default');

/**
 * Uncomment this line and correct your server timezone to fix
 * any date & time related errors.
 */
        
        if($_SERVER['HTTP_HOST']=='192.168.0.67:8800' || $_SERVER['HTTP_HOST']=='mymeetingdesk.com'){
          date_default_timezone_set('Asia/Calcutta');   
        }else{
	  date_default_timezone_set('EST');
        }
       
/**
 * `Config.timezone` is available in which you can set users' timezone string.
 * If a method of CakeTime class is called with $timezone parameter as null and `Config.timezone` is set,
 * then the value of `Config.timezone` will be used. This feature allows you to set users' timezone just
 * once instead of passing it each time in function calls.
 */
	Configure::write('Config.timezone', 'EST');

/**
 * Cache Engine Configuration
 * Default settings provided below
 *
 * File storage engine.
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'File', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 * 		'path' => CACHE, //[optional] use system tmp directory - remember to use absolute path
 * 		'prefix' => 'cake_', //[optional]  prefix every cache file with this string
 * 		'lock' => false, //[optional]  use file locking
 * 		'serialize' => true, //[optional]
 * 		'mask' => 0664, //[optional]
 *	));
 *
 * APC (http://pecl.php.net/package/APC)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Apc', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 *
 * Xcache (http://xcache.lighttpd.net/)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Xcache', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional] prefix every cache file with this string
 *		'user' => 'user', //user from xcache.admin.user settings
 *		'password' => 'password', //plaintext password (xcache.admin.pass)
 *	));
 *
 * Memcached (http://www.danga.com/memcached/)
 *
 * Uses the memcached extension. See http://php.net/memcached
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Memcached', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 * 		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 * 		'servers' => array(
 * 			'127.0.0.1:11211' // localhost, default port 11211
 * 		), //[optional]
 * 		'persistent' => 'my_connection', // [optional] The name of the persistent connection.
 * 		'compress' => false, // [optional] compress data in Memcached (slower, but uses less memory)
 *	));
 *
 *  Wincache (http://php.net/wincache)
 *
 * 	 Cache::config('default', array(
 *		'engine' => 'Wincache', //[required]
 *		'duration' => 3600, //[optional]
 *		'probability' => 100, //[optional]
 *		'prefix' => Inflector::slug(APP_DIR) . '_', //[optional]  prefix every cache file with this string
 *	));
 */

/**
 * Configure the cache handlers that CakePHP will use for internal
 * metadata like class maps, and model schema.
 *
 * By default File is used, but for improved performance you should use APC.
 *
 * Note: 'default' and other application caches should be configured in app/Config/bootstrap.php.
 *       Please check the comments in bootstrap.php for more info on the cache engines available
 *       and their settings.
 */
$engine = 'File';

// In development mode, caches should expire quickly.
$duration = '+999 days';
if (Configure::read('debug') > 0) {
	$duration = '+10 seconds';
}

// Prefix each application on the same server with a different string, to avoid Memcache and APC conflicts.
$prefix = 'myapp_';

/**
 * Configure the cache used for general framework caching. Path information,
 * object listings, and translation cache files are stored with this configuration.
 */
Cache::config('_cake_core_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_core_',
	'path' => CACHE . 'persistent' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));

/**
 * Configure the cache for model and datasource caches. This cache configuration
 * is used to store schema descriptions, and table listings in connections.
 */
Cache::config('_cake_model_', array(
	'engine' => $engine,
	'prefix' => $prefix . 'cake_model_',
	'path' => CACHE . 'models' . DS,
	'serialize' => ($engine === 'File'),
	'duration' => $duration
));
$zone=array('(UTC-11:00) Midway Island' => 'Pacific/Midway',
    '(UTC-11:00) Samoa' => 'Pacific/Samoa',
    '(UTC-10:00) Hawaii' => 'Pacific/Honolulu',
    '(UTC-09:00) Alaska' => 'US/Alaska',
    '(UTC-08:00) Pacific Time (US & Canada)' => 'America/Los_Angeles',
    '(UTC-08:00) Tijuana' => 'America/Tijuana',
    '(UTC-07:00) Arizona' => 'US/Arizona',
    '(UTC-07:00) Chihuahua' => 'America/Chihuahua',
    '(UTC-07:00) La Paz' => 'America/Chihuahua',
    '(UTC-07:00) Mazatlan' => 'America/Mazatlan',
    '(UTC-07:00) Mountain Time (US & Canada)' => 'US/Mountain',
    '(UTC-06:00) Central America' => 'America/Managua',
    '(UTC-06:00) Central Time (US & Canada)' => 'US/Central',
    '(UTC-06:00) Guadalajara' => 'America/Mexico_City',
    '(UTC-06:00) Mexico City' => 'America/Mexico_City',
    '(UTC-06:00) Monterrey' => 'America/Monterrey',
    '(UTC-06:00) Saskatchewan' => 'Canada/Saskatchewan',
    '(UTC-05:00) Bogota' => 'America/Bogota',
    '(UTC-05:00) Eastern Time (US & Canada)' => 'US/Eastern',
    '(UTC-05:00) Indiana (East)' => 'US/East-Indiana',
    '(UTC-05:00) Lima' => 'America/Lima',
    '(UTC-05:00) Quito' => 'America/Bogota',
    '(UTC-04:00) Atlantic Time (Canada)' => 'Canada/Atlantic',
    '(UTC-04:30) Caracas' => 'America/Caracas',
    '(UTC-04:00) La Paz' => 'America/La_Paz',
    '(UTC-04:00) Santiago' => 'America/Santiago',
    '(UTC-03:30) Newfoundland' => 'Canada/Newfoundland',
    '(UTC-03:00) Brasilia' => 'America/Sao_Paulo',
    '(UTC-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
    '(UTC-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
    '(UTC-03:00) Greenland' => 'America/Godthab',
    '(UTC-02:00) Mid-Atlantic' => 'America/Noronha',
    '(UTC-01:00) Azores' => 'Atlantic/Azores',
    '(UTC-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
    '(UTC+00:00) Casablanca' => 'Africa/Casablanca',
    '(UTC+00:00) Edinburgh' => 'Europe/London',
    '(UTC+00:00) Greenwich Mean Time : Dublin' => 'Etc/Greenwich',
    '(UTC+00:00) Lisbon' => 'Europe/Lisbon',
    '(UTC+00:00) London' => 'Europe/London',
    '(UTC+00:00) Monrovia' => 'Africa/Monrovia',
    '(UTC+00:00) UTC' => 'UTC',
    '(UTC+01:00) Amsterdam' => 'Europe/Amsterdam',
    '(UTC+01:00) Belgrade' => 'Europe/Belgrade',
    '(UTC+01:00) Berlin' => 'Europe/Berlin',
    '(UTC+01:00) Bern' => 'Europe/Berlin',
    '(UTC+01:00) Bratislava' => 'Europe/Bratislava',
    '(UTC+01:00) Brussels' => 'Europe/Brussels',
    '(UTC+01:00) Budapest' => 'Europe/Budapest',
    '(UTC+01:00) Copenhagen' => 'Europe/Copenhagen',
    '(UTC+01:00) Ljubljana' => 'Europe/Ljubljana',
    '(UTC+01:00) Madrid' => 'Europe/Madrid',
    '(UTC+01:00) Paris' => 'Europe/Paris',
    '(UTC+01:00) Prague' => 'Europe/Prague',
    '(UTC+01:00) Rome' => 'Europe/Rome',
    '(UTC+01:00) Sarajevo' => 'Europe/Sarajevo',
    '(UTC+01:00) Skopje' => 'Europe/Skopje',
    '(UTC+01:00) Stockholm' => 'Europe/Stockholm',
    '(UTC+01:00) Vienna' => 'Europe/Vienna',
    '(UTC+01:00) Warsaw' => 'Europe/Warsaw',
    '(UTC+01:00) West Central Africa' => 'Africa/Lagos',
    '(UTC+01:00) Zagreb' => 'Europe/Zagreb',
    '(UTC+02:00) Athens' => 'Europe/Athens',
    '(UTC+02:00) Bucharest' => 'Europe/Bucharest',
    '(UTC+02:00) Cairo' => 'Africa/Cairo',
    '(UTC+02:00) Harare' => 'Africa/Harare',
    '(UTC+02:00) Helsinki' => 'Europe/Helsinki',
    '(UTC+02:00) Istanbul' => 'Europe/Istanbul',
    '(UTC+02:00) Jerusalem' => 'Asia/Jerusalem',
    '(UTC+02:00) Kyiv' => 'Europe/Helsinki',
    '(UTC+02:00) Pretoria' => 'Africa/Johannesburg',
    '(UTC+02:00) Riga' => 'Europe/Riga',
    '(UTC+02:00) Sofia' => 'Europe/Sofia',
    '(UTC+02:00) Tallinn' => 'Europe/Tallinn',
    '(UTC+02:00) Vilnius' => 'Europe/Vilnius',
    '(UTC+03:00) Baghdad' => 'Asia/Baghdad',
    '(UTC+03:00) Kuwait' => 'Asia/Kuwait',
    '(UTC+03:00) Minsk' => 'Europe/Minsk',
    '(UTC+03:00) Nairobi' => 'Africa/Nairobi',
    '(UTC+03:00) Riyadh' => 'Asia/Riyadh',
    '(UTC+03:00) Volgograd' => 'Europe/Volgograd',
    '(UTC+03:30) Tehran' => 'Asia/Tehran',
    '(UTC+04:00) Abu Dhabi' => 'Asia/Muscat',
    '(UTC+04:00) Baku' => 'Asia/Baku',
    '(UTC+04:00) Moscow' => 'Europe/Moscow',
    '(UTC+04:00) Muscat' => 'Asia/Muscat',
    '(UTC+04:00) St. Petersburg' => 'Europe/Moscow',
    '(UTC+04:00) Tbilisi' => 'Asia/Tbilisi',
    '(UTC+04:00) Yerevan' => 'Asia/Yerevan',
    '(UTC+04:30) Kabul' => 'Asia/Kabul',
    '(UTC+05:00) Islamabad' => 'Asia/Karachi',
    '(UTC+05:00) Karachi' => 'Asia/Karachi',
    '(UTC+05:00) Tashkent' => 'Asia/Tashkent',
    '(UTC+05:30) Chennai' => 'Asia/Calcutta',
    '(UTC+05:30) Kolkata' => 'Asia/Kolkata',
    '(UTC+05:30) Mumbai' => 'Asia/Calcutta',
    '(UTC+05:30) New Delhi' => 'Asia/Calcutta',
    '(UTC+05:30) Sri Jayawardenepura' => 'Asia/Calcutta',
    '(UTC+05:45) Kathmandu' => 'Asia/Katmandu',
    '(UTC+06:00) Almaty' => 'Asia/Almaty',
    '(UTC+06:00) Astana' => 'Asia/Dhaka',
    '(UTC+06:00) Dhaka' => 'Asia/Dhaka',
    '(UTC+06:00) Ekaterinburg' => 'Asia/Yekaterinburg',
    '(UTC+06:30) Rangoon' => 'Asia/Rangoon',
    '(UTC+07:00) Bangkok' => 'Asia/Bangkok',
    '(UTC+07:00) Hanoi' => 'Asia/Bangkok',
    '(UTC+07:00) Jakarta' => 'Asia/Jakarta',
    '(UTC+07:00) Novosibirsk' => 'Asia/Novosibirsk',
    '(UTC+08:00) Beijing' => 'Asia/Hong_Kong',
    '(UTC+08:00) Chongqing' => 'Asia/Chongqing',
    '(UTC+08:00) Hong Kong' => 'Asia/Hong_Kong',
    '(UTC+08:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
    '(UTC+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
    '(UTC+08:00) Perth' => 'Australia/Perth',
    '(UTC+08:00) Singapore' => 'Asia/Singapore',
    '(UTC+08:00) Taipei' => 'Asia/Taipei',
    '(UTC+08:00) Ulaan Bataar' => 'Asia/Ulan_Bator',
    '(UTC+08:00) Urumqi' => 'Asia/Urumqi',
    '(UTC+09:00) Irkutsk' => 'Asia/Irkutsk',
    '(UTC+09:00) Osaka' => 'Asia/Tokyo',
    '(UTC+09:00) Sapporo' => 'Asia/Tokyo',
    '(UTC+09:00) Seoul' => 'Asia/Seoul',
    '(UTC+09:00) Tokyo' => 'Asia/Tokyo',
    '(UTC+09:30) Adelaide' => 'Australia/Adelaide',
    '(UTC+09:30) Darwin' => 'Australia/Darwin',
    '(UTC+10:00) Brisbane' => 'Australia/Brisbane',
    '(UTC+10:00) Canberra' => 'Australia/Canberra',
    '(UTC+10:00) Guam' => 'Pacific/Guam',
    '(UTC+10:00) Hobart' => 'Australia/Hobart',
    '(UTC+10:00) Melbourne' => 'Australia/Melbourne',
    '(UTC+10:00) Port Moresby' => 'Pacific/Port_Moresby',
    '(UTC+10:00) Sydney' => 'Australia/Sydney',
    '(UTC+10:00) Yakutsk' => 'Asia/Yakutsk',
    '(UTC+11:00) Vladivostok' => 'Asia/Vladivostok',
    '(UTC+12:00) Auckland' => 'Pacific/Auckland',
    '(UTC+12:00) Fiji' => 'Pacific/Fiji',
    '(UTC+12:00) International Date Line West' => 'Pacific/Kwajalein',
    '(UTC+12:00) Kamchatka' => 'Asia/Kamchatka',
    '(UTC+12:00) Magadan' => 'Asia/Magadan',
    '(UTC+12:00) Marshall Is.' => 'Pacific/Fiji',
    '(UTC+12:00) New Caledonia' => 'Asia/Magadan',
    '(UTC+12:00) Solomon Is.' => 'Asia/Magadan',
    '(UTC+12:00) Wellington' => 'Pacific/Auckland',
    '(UTC+13:00) Nuku\'alofa' => 'Pacific/Tongatapu');
Configure::write('TimeZones',  array_flip($zone));
  