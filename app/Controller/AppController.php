<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        // 'DebugKit.Toolbar',
        'Auth' => array(
            //'authError' => 'Did you really think you are allowed to see that?',
            'authenticate' => array(
                'Form' => array(
                    'fields' => array('username' => 'email'),
                    'scope' => array('User.status' => 1,'User.deleted' => 0)
                )
            ),
            'ajaxLogin' => 'Ajax/login'
        ),
        'Email',
        'Session',
        'Cookie',
    );
    public $helpers = array('Html', 'Form', 'BootForm');
    public $uses = array('Setting', 'SystemMail', 'User');
    public $current_shop;

    public function beforeFilter() {
//        pr($this->Session->read('Auth')); die;
        $this->Auth->flash = array(
            'element' => 'default',
            'key' => 'flash',
            'params' => array('class' => 'error')
        );
        if ($this->name == 'CakeError') {
            $this->layout = 'error';
           
        }

        if (isset($this->request->params['admin']) && $this->name != 'CakeError') {
            $this->layout = 'admin';
        }
        //pr($this->Session->read('Auth.User'));die;
        // pr($this->params);
        if (isset($this->params['slug']) && $this->params['slug'] != $this->params['controller']) {
            $shop = $this->User->findByShopSlugAndStatus($this->params['slug'], 1);
            if (empty($shop)) {
                echo 'Opps!! Invalid Barber Shop';
                exit;
            }
            $this->current_shop = $shop;
            $this->set('current_shop', $shop);
        }
        if ($this->Session->read('Auth.User.role_id') == 4 && isset($this->params['slug']) && $this->Session->read('Auth.User.barber') != '' && $this->params['slug'] != $this->Session->read('Auth.User.barber')) {
            $this->Auth->logout();
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'slug' => $this->params['slug']));
        }

        if ($this->Session->read('Auth.User.id') != '' && (isset($this->request->params['admin']))) {
            if (!in_array($this->Session->read('Auth.User.role_id'), array(1, 2))) {
                $this->redirect(array('admin' => false, 'controller' => 'users', 'action' => 'my_account'));
            }
        }
        if ($this->Session->read('Auth.User.id') != '' && (isset($this->request->params['barber']))) {
            if ($this->Session->read('Auth.User.role_id') != 3) {
                $this->redirect('/');
            }
        }

        /*  Set time zone */
        $this->setTimeZone();

        /* SMTP Options */
        $this->Email->smtpOptions = array(
            'port' => '465',
            'timeout' => '30',
            'host' => 'ssl://smtp.gmail.com',
            'username' => 'hiveEventB2B@gmail.com',
            'password' => 'hive123456',
            'context' => [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]
        );
        $this->Email->delivery = 'smtp';

        $settings = $this->Setting->find('all', array('conditions' => array('Setting.key' => array('Site.title', 'Site.status'))));
        foreach ($settings as $setting) {
            Configure::write($setting['Setting']['key'], $setting['Setting']['value']);
        }
        Configure::write('Site.scheduleHour', 30);
        if (Configure::read('Site.status') == 0 && !isset($this->request->params['admin'])) {
            $this->layout = 'maintenance';
        }
        $days = array(
            'mon' => 'Monday',
            'tue' => 'Tuesday',
            'wed' => 'Wednesday',
            'thu' => 'Thursday',
            'fri' => 'Friday',
            'sat' => 'Saturday',
            'sun' => 'Sunday',
        );
        $this->set(compact('days'));
    }

    function beforeRender() {
        if ($this->Session->read('Auth.User.role_id') != 1 && $this->Session->read('Auth.User.role_id') != 2 && !$this->params['admin'] && !$this->params['barber']) {
            $session_msg = '';
            App::uses('SessionHelper', 'View/Helper');
            $tmpSessionHelper = new SessionHelper(new View());

            $messages = $this->Session->read('Message');

            if (!empty($messages)) {
                $output = '';
                if (is_array($messages)) {
                    foreach (array_keys($messages) AS $key) {
                        $output .= $tmpSessionHelper->flash($key);
                    }
                }
                //warning,information
                $class = (isset($messages['flash']['params']['class'])) ? $messages['flash']['params']['class'] : 'warning';
                $msg = (isset($messages['flash']['message'])) ? $messages['flash']['message'] : $messages['auth']['message'];
                $session_msg = "toastr['" . $class . "']('" . $output . "');";
            }
            $this->set('session_msg', $session_msg);
        } else {
            $session_msg = '';
            $this->set('session_msg', $session_msg);
        }
    }

    function getServiceScheduleSlots($duration, $break, $stTime, $enTime) {
        $start = new DateTime($stTime);
        $end = new DateTime($enTime);
        $interval = new DateInterval("PT" . $duration . "M");
        $breakInterval = new DateInterval("PT" . $break . "M");

        for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($breakInterval)) {

            $endPeriod = clone $intStart;
            $endPeriod->add($interval);
            if ($endPeriod > $end) {
                $endPeriod = $end;
            }
            $periods[$intStart->format('H:i')] = $intStart->format('h:i A');
        }
        if (isset($periods)) {
            return $periods;
        } else {
            return array();
        }
    }

    function getFileName($data) {
        return strtolower(str_replace(' ', '', $data['User']['first_name'])) . '_' . time() . '.' . pathinfo($data['User']['image']['name'], PATHINFO_EXTENSION);
    }

    function setTimeZone() {

        $zone = 'EST';
        $window_hours = 3;
        if ($this->Auth->user('role_id') == 2 && $this->Auth->user('timezone') == '') {
            $user = $this->User->findById($this->Auth->user('id'), array('User.timezone', 'User.window_hours'));

            if (isset($user['User']['timezone']) && !empty($user['User']['timezone'])) {
                $zone = $user['User']['timezone'];
                $window_hours = $user['User']['window_hours'];
            }
        } elseif ($this->Auth->user('role_id') == 3 && $this->Auth->user('timezone') == '') {
            $user = $this->User->findById($this->Auth->user('parent_id'), array('User.timezone', 'User.window_hours'));
            if (isset($user['User']['timezone']) && !empty($user['User']['timezone'])) {
                $zone = $user['User']['timezone'];
                $window_hours = $user['User']['window_hours'];
            }
        } elseif ($this->Auth->user('role_id') == 4 && $this->Auth->user('timezone') == '') {


            $user = $this->User->findByShopSlug($this->Auth->user('barber'), array('User.timezone', 'User.window_hours'));

            if (isset($user['User']['timezone']) && !empty($user['User']['timezone'])) {
                $zone = $user['User']['timezone'];
                $window_hours = $user['User']['window_hours'];
            }
        }



        if ($this->Auth->user('id') != '') {
            if ($this->Auth->user('timezone') != '') {
                $zone = $this->Auth->user('timezone');
            } else {
                $this->Session->write('Auth.User.timezone', $zone);
            }
        }
        date_default_timezone_set($zone);


        //
        if ($this->Auth->user('role_id') == 2) {
            $user = $this->User->findById($this->Auth->user('id'), array('User.timezone', 'User.window_hours'));
            $window_hours = $user['User']['window_hours'];
        } elseif ($this->Auth->user('role_id') == 3) {
            $user = $this->User->findById($this->Auth->user('parent_id'), array('User.timezone', 'User.window_hours'));

            $window_hours = $user['User']['window_hours'];
        } elseif ($this->Auth->user('role_id') == 4) {
            $user = $this->User->findByShopSlug($this->Auth->user('barber'), array('User.timezone', 'User.window_hours'));

            $window_hours = $user['User']['window_hours'];
        }
        if ($this->Auth->user('role_id')) {
            $this->Session->write('Auth.User.window_hours', $window_hours);
        }
    }

}
