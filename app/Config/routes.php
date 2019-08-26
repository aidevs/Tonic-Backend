<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'users', 'action' => 'login'));
	//Router::connect('/pages/forgot', array('controller' => 'pages', 'action' => 'forgot'));
	Router::connect('/admin', array('admin'=>true,'controller' => 'users', 'action' => 'login'));
	//Router::connect('/users/login', array('admin'=>true,'controller' => 'users', 'action' => 'login'));
	Router::connect('/admin/dashboard', array('admin'=>true,'controller' => 'users', 'action' => 'dashboard'));
       
        /**Barber Routes**/
        Router::connect('/barber', array('barber'=>true,'controller' => 'barbers', 'action' => 'login'));
        Router::connect('/barber/users/login', array('barber'=>true,'controller' => 'barbers', 'action' => 'login'));
        Router::connect('/barber/schedules', array('barber'=>true,'controller' => 'barbers', 'action' => 'schedules'));
        Router::connect('/barber/vacations', array('barber'=>true,'controller' => 'barbers', 'action' => 'vacations'));
        Router::connect('/barber/add_vacation', array('barber'=>true,'controller' => 'barbers', 'action' => 'add_vacation'));
        Router::connect('/barber/logout', array('barber'=>true,'controller' => 'barbers', 'action' => 'logout'));

/**
 * ...and connect the rest of 'Web Service' Routes.
 */        
    Router::connect('/api/login', array('admin'=>false,'controller' => 'api','action'=>'login'));         
/**
 * 
 
 * ...and connect the rest of 'Barber' Routes.
 */
    
     
    Router::connect('/:slug/login/*', array('admin'=>false,'controller' => 'users','action'=>'login'),array('pass'=>array('slug'))); 
    Router::connect('/:slug', array('admin'=>false,'controller' => 'users','action'=>'login'),array('pass'=>array('slug'))); 
    Router::connect('/:slug/tv', array('admin'=>false,'controller' => 'pages','action'=>'tv'),array('pass'=>array('slug'))); 
   Router::connect('/:slug/tvReservationList', array('admin'=>false,'controller' => 'pages','action'=>'tvReservationList'),array('pass'=>array('slug')));
 Router::connect('/:slug/tvWalkinList', array('admin'=>false,'controller' => 'pages','action'=>'tvWalkinList'),array('pass'=>array('slug')));
    Router::connect('/:slug/register/*', array('admin'=>false,'controller' => 'users','action'=>'register'),array('pass'=>array('slug'))); 
    
   Router::connect('/:slug/forgot_password/*', array('admin'=>false,'controller' => 'users','action'=>'forgot_password'),array('pass'=>array('slug'))); 
 
   
/**
 *
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
 if (!defined('INACTIVE')) {
    define('INACTIVE', '0');
}
if (!defined('ACTIVE')) {
    define('ACTIVE', '1');
}
require CAKE . 'Config' . DS . 'routes.php';
define('ASSETS_URL', Router::url('/')); 
define('SITE_URL', Router::url('/'));
define('SITE_FULL_URL', Router::url('/',true));

