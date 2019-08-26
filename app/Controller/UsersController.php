<?php

/**
 * Users controller.
 *
 * This file will render views from views/pages/
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('AppController', 'Controller');

/**
 * Users controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Users';

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('User', 'Country', 'Appointment', 'Walkin', 'Week', 'Schedule', 'WalkinAppointment', 'WalkinAppointmentBarber', 'Advertisement','LunchBreak','Vacation','WalkinAppointments','WalkinAppointmentBarbers');
    var $helper = array('Common');
    var $components = array('Common', 'Email');

    /**
     * Displays a view
     *
     * @param mixed What page to display
     * @return void
     */
    function beforeFilter() {

        $this->Auth->allow(array('admin_forgot', 'admin_reset_password', 'login', 'forgot_password', 'register', 'activate', 'reset_password','temp_login'));
        parent::beforeFilter();

        $restrictAdminActions = array('admin_index', 'admin_list', 'admin_add', 'admin_edit', 'admin_delete');
        if ($this->Auth->user('role_id') == 2 && in_array($this->params['action'], $restrictAdminActions)) {
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
        $restrictFrontActions = array('login', 'edit_profile', 'my_profile', 'forgot_password', 'reset_password', 'chnage_password', 'my_account', 'notes');
        if (in_array($this->Auth->user('role_id'), array(1, 2)) && in_array($this->params['action'], $restrictFrontActions)) {
            $this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'dashboard'));
        }
    }

    public function admin_dashboard() {
        $this->set('title_for_layout', __('Dashboard', true));
        $this->layout = "admin";
        $total_re_customers = $total_walk_customers = array();
        if ($this->Auth->user('role_id') == 1) {
            $total_users = $this->User->find('count', array('conditions' => array('User.role_id !=' => 1, 'User.role_id' => 2)));
        } else {
            $total_users = $this->User->find('count', array('conditions' => array('User.role_id' => 3, 'User.parent_id' => $this->Auth->user('id'))));
            $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'barber_id'))));
            $total_re_customers = $this->Appointment->find('count', array('conditions' => array('User.parent_id' => $this->Auth->user('id'))));

            $total_walk_customers = $this->Walkin->find('count', array('conditions' => array('User.parent_id' => $this->Auth->user('id'))));
        }

        $this->set(compact('total_users', 'total_re_customers', 'total_walk_customers'));
    }

    public function admin_profile() {
        $this->set('title_for_layout', __('Account Setting', true));
        if (!empty($this->request->data)) {
            $this->User->id = $this->Auth->user('id');
            if ($user = $this->User->save($this->request->data, false)) {
                $this->Session->write('Auth.User.name', $user['User']['name']);
                if ($this->Auth->user('role_id') == 2) {
                    $this->Session->write('Auth.User.timezone', $user['User']['timezone']);
                }
                $this->Session->setFlash(__('Profile has been changed successfully.'), 'default', array('class' => 'success'));
                $this->redirect(array('controller' => 'users', 'action' => 'profile#profile'));
            } else {
                $this->Session->setFlash(__('Profile cannot be change. Please try again.'), 'default', array('class' => 'error'));
                $this->redirect(array('controller' => 'users', 'action' => 'profile#profile'));
            }
        }

        $this->request->data = $this->User->findById($this->Auth->user('id'));
        unset($this->request->data['User']['password']);
    }

    public function admin_index() {

        $this->User->recursive = 0;
        $this->set('title_for_layout', 'Admins Manager');
        $countries = $this->Country->find('list', array('fields' => array('id', 'country_name'), 'order' => array('country_name' => 'asc')));
        $this->set(compact('countries'));
        $this->set('total_users', $this->User->find('count', array('condition' => array('User.role_id' => 2))));
    }

    public function admin_list() {
        $this->layout = false;

        if (isset($this->request->data['customActionType']) && $this->request->data['customActionType'] == 'group_action') {
            $this->User->updateAll(array('User.status' => $this->request->data['customActionName']), array('User.id' => $this->request->data['id']));
        }

        $phone_con = $shop_name_con = $con = $email_con = $name_con = $country_con = $status_con = $barber_limit_con = array();

        if (isset($this->request->data['name']) && $this->request->data['name'] != '') {
            $name_con = array('User.name LIKE' => '%' . $this->request->data['name'] . '%');
        }
        if (isset($this->request->data['email']) && $this->request->data['email'] != '') {
            $email_con = array('User.email LIKE' => '%' . $this->request->data['email'] . '%');
        }
        if (isset($this->request->data['shop_name']) && $this->request->data['shop_name'] != '') {
            $shop_name_con = array('User.shop_name LIKE' => '%' . $this->request->data['shop_name'] . '%');
        }
        if (isset($this->request->data['phone']) && $this->request->data['phone'] != '') {
            $phone_con = array('User.phone' => $this->request->data['phone']);
        }
        if (isset($this->request->data['country_id']) && $this->request->data['country_id'] != '') {
            $country_con = array('User.country_id' => $this->request->data['country_id']);
        }

        if (isset($this->request->data['status']) && $this->request->data['status'] != '') {
            $status_con = array('User.status' => $this->request->data['status']);
        }
		
		if (isset($this->request->data['unlimited_barber']) && $this->request->data['unlimited_barber'] != '') {
            $barber_limit_con = array('User.unlimited_barber' => $this->request->data['unlimited_barber']);
        }

		
        switch ($this->request->data['order'][0]['column']) {
            case 1:
                $order = array('User.name' => $this->request->data['order'][0]['dir']);
                break;
            case 2:
                $order = array('User.email' => $this->request->data['order'][0]['dir']);
                break;
            case 3:
                $order = array('User.shop_name' => $this->request->data['order'][0]['dir']);
                break;
            case 4:
                $order = array('Country.country_name' => $this->request->data['order'][0]['dir']);
                break;
            case 5:
                $order = array('User.phone' => $this->request->data['order'][0]['dir']);
                break;
            case 6:
                $order = array('User.status' => $this->request->data['order'][0]['dir']);
                break;
			case 7:
                $order = array('User.unlimited_barber' => $this->request->data['order'][0]['dir']);
                break;
            default:
                break;
        }
        if (isset($this->request->data['start']) && $this->request->data['start'] != $this->request->data['length']) {
            $page = ($this->request->data['start'] / $this->request->data['length']) + 1;
        } elseif ($this->request->data['start'] == $this->request->data['length']) {
            $page = 2;
        } else {
            $page = 1;
        }

        $con = array_merge($phone_con, $shop_name_con, $email_con, $name_con, $country_con, $status_con, $barber_limit_con, array('User.role_id' => 2));
		
		//pr($this->request->data);die;
		//pr($order);die;
		//pr($con);die;
		
        $this->paginate = array('conditions' => $con, 'limit' => $this->request->data['length'], 'order' => $order, 'page' => $page);
        $this->User->recursive = 1;
        $this->set('users', $this->paginate('User'));
        //pr($this->paginate('User'));die;
    }

    public function admin_login() {
        $this->layout = 'admin_login';
        $this->set('title_for_layout', 'Admin Login');
        if ($this->Session->read('Auth.User')) {
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => true));
        }

        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                if (isset($this->request->data['User']['remmber_me']) && $this->request->data['User']['remmber_me'] == 1) {
                    $this->Cookie->delete('Auth');
                    $cookie = array();
                    $cookie['email'] = $this->request->data['User']['email'];
                    $cookie['password'] = $this->request->data['User']['password'];
                    $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
                    unset($this->request->data['User']['remember_me']);
                } else {
                    $this->Cookie->delete('Auth');
                }
                //$this->redirect($this->Auth->redirect());
                $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => true));
            } else {
                $this->Session->setFlash(__('Invalid email or password.'), 'default', array('class' => 'error'));
            }
        }
        $cookie = $this->Cookie->read('Auth');
        if (isset($cookie) && !empty($cookie) && isset($cookie['User'])) {
            $this->request->data['User']['email'] = $cookie['User']['email'];
            $this->request->data['User']['password'] = $cookie['User']['password'];
            $this->request->data['User']['remmber_me'] = 1;
        }
        $this->render(false);
    }

    public function admin_logout() {
        $this->Session->setFlash('Logout Successfully.', 'default', array('class' => 'success'));
        $this->redirect($this->Auth->logout());
        // $this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'login'));
    }

    public function admin_change_password() {
        if (!empty($this->request->data)) {
            $this->User->id = $this->Auth->user('id');
            if ($this->User->save($this->request->data, false)) {
                $this->Session->setFlash(__('Password has been changed successfully.'), 'default', array('class' => 'success'));
                $this->redirect(array('controller' => 'users', 'action' => 'profile#password'));
            } else {
                $this->Session->setFlash(__('Password cannot be change. Please try again.'), 'default', array('class' => 'error'));
                $this->redirect(array('controller' => 'users', 'action' => 'profile#password'));
            }
        }
    }

    public function admin_add() {
        $this->set('title_for_layout', 'Add Admin');
        if (!empty($this->request->data)) {
			
            $this->request->data['User']['role_id'] = 2;
            $password = mt_rand(100000, 999999);
            $this->request->data['User']['password'] = $password;
            $this->request->data['User']['status'] = (isset($this->request->data['User']['status'])) ? $this->request->data['User']['status'] : 0;
            $this->request->data['User']['unlimited_barber'] = (isset($this->request->data['User']['unlimited_barber'])) ? $this->request->data['User']['unlimited_barber'] : 0;
            $this->request->data['User']['is_ad_on'] = (isset($this->request->data['User']['is_ad_on'])) ? $this->request->data['User']['is_ad_on'] : 0;
            $this->request->data['User']['is_social_on'] = (isset($this->request->data['User']['is_social_on'])) ? $this->request->data['User']['is_social_on'] : 0;
			
			
			
            if ($res = $this->User->save($this->request->data)) {
                $shop_url = Router::url(array('admin' => true, 'controller' => 'users', 'action' => 'login'), true);
                $shop_url = "<a href='$shop_url'>Click Here</a> or <a href='$shop_url'>$shop_url</a>";
                $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'AdminRegistration')));
                $mail['SystemMail']['message'] = str_replace('[first_name]', $res['User']['name'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[email]', $this->request->data['User']['email'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[password]', $password, $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[shop_url]', $shop_url, $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[site_url]', SITE_FULL_URL, $mail['SystemMail']['message']);
                $to = $this->request->data['User']['email'];
                $this->Email->to = $to;
                $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
                $this->Email->subject = $mail['SystemMail']['subject'];
                $this->Email->sendAs = 'html';
                $this->Email->template = 'default';
                $this->set('message', $mail['SystemMail']['message']);
                $this->set('title', $mail['SystemMail']['subject']);
                if ($this->Email->send()) {
                    $this->Session->setFlash('The admin has been saved', 'default', array('class' => 'success'));
                } else {
                    $this->Session->setFlash('The admin has been saved but email not send.', 'default', array('class' => 'success'));
                }
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The admin could not be saved', 'default', array('class' => 'error'));
            }
        }
        $countries = $this->Country->find('list', array('fields' => array('id', 'country_name'), 'order' => array('country_name' => 'asc')));
        $this->set(compact('countries'));
    }

    public function admin_edit($id = null) {
        $this->set('title_for_layout', 'Edit Admin');
        $user = $this->User->findById($id);
        if (!$id || empty($user)) {
            $this->Session->setFlash(__('Invalid admin', true));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->request->data)) {
            $this->request->data['User']['status'] = (isset($this->request->data['User']['status'])) ? $this->request->data['User']['status'] : 0;
            $this->request->data['User']['unlimited_barber'] = (isset($this->request->data['User']['unlimited_barber'])) ? $this->request->data['User']['unlimited_barber'] : 0;
            $this->request->data['User']['is_ad_on'] = (isset($this->request->data['User']['is_ad_on'])) ? $this->request->data['User']['is_ad_on'] : 0;
            $this->request->data['User']['is_social_on'] = (isset($this->request->data['User']['is_social_on'])) ? $this->request->data['User']['is_social_on'] : 0;
            
            if ($this->User->save($this->request->data['User'])) {
                $this->Session->setFlash('The admin has been saved', 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The admin could not be saved', 'default', array('class' => 'error'));
            }
        }
        $countries = $this->Country->find('list', array('fields' => array('id', 'country_name'), 'order' => array('country_name' => 'asc')));
        $this->set(compact('countries'));
        $this->request->data = $user;
    }

    public function admin_reset_password($id = null) {

        $this->layout = 'admin_reset_password';
        $this->set('title_for_layout', 'Admin Reset Password');
        if (!empty($this->request->data)) {
            $this->request->data['User']['activation_key'] = md5(uniqid());
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Your password has been reset successfully.'), 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'login'));
            } else {
                $this->Session->setFlash('Password could not be reset. Please try again.', 'default', array('class' => 'error'));
            }
        }
        if (!empty($id) && empty($this->data)) {
            $this->request->data = $this->User->findByActivationKey($id);
            unset($this->request->data['User']['password']);
            if (empty($this->data)) {
                $this->Session->setFlash(__('Reset password link has been expired. Please try again.', 'default', array('class' => 'error')));
                $this->redirect(array('action' => 'forgot'));
            }
        }

        $this->render(false);
    }

    public function admin_forgot() {
        $this->layout = 'admin_login';
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->User->recursive = 0;
            $user = $this->User->findByEmail($this->request->data['User']['email']);
            if (!empty($user) && isset($user)) {
                $this->User->id = $user['User']['id'];
                $activationKey = md5(uniqid());
                $this->User->saveField('activation_key', $activationKey);
                $url1 = SITE_FULL_URL . 'admin/users/reset_password/' . $activationKey;
                $url = "<a href='$url1'>Click Here</a> and $url1";
                $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'ForgotPassword')));
                $mail['SystemMail']['message'] = str_replace('[fullname]', $user['User']['name'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[link]', $url1, $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                $this->Email->to = $user['User']['email'];
                $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
                $this->Email->subject = $mail['SystemMail']['subject'];
                $this->Email->sendAs = 'html';
                $this->Email->template = 'default';
                $this->set('message', $mail['SystemMail']['message']);
                $this->set('title', $mail['SystemMail']['subject']);
                if ($this->Email->send()) {
                    $this->Session->setFlash('An email has been sent with instructions for reset your password.', 'default', array('class' => 'success'));
                    return $this->redirect(array('action' => 'login'));
                }
            } else {
                $this->Session->setFlash('No user was found with the submitted email.', 'default', array('class' => 'error'));
            }
        }
        $this->render(false);
    }

    public function admin_delete($id = null) {

        $this->User->id = $id;
        if (!$this->User->exists()) {
            $this->Session->setFlash('Invalid admin.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->User->delete()) {
            $this->Session->setFlash('The admin has been deleted.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The admin could not be deleted. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    /*     * ******************Front************************************** */
    function temp_login($id=null,$barber=null) {
        $user=$this->User->findById($id);
        if(!empty($user)){
        $this->Session->write('Auth', $user);
        $this->Session->write('Auth.User.barber',$barber);
         return $this->redirect('my_account');
        }
        exit;
    }        
    function login() {
        $this->set('title_for_layout', 'Login');
        if ($this->Session->read('Auth.User')) {
            $this->redirect(array('controller' => 'users', 'action' => 'my_account'));
        }
        if ($this->request->is('post')) {

            if ($this->Auth->login()) {
                $this->Session->write('Auth.User.barber', $this->params['slug']);
                if (isset($this->request->data['User']['remmber_me']) && $this->request->data['User']['remmber_me'] == 1) {
                    $this->Cookie->delete('Auth.User');
                    $cookie = array();
                    $cookie['email'] = $this->request->data['User']['email'];
                    $cookie['password'] = $this->request->data['User']['password'];
                    $this->Cookie->write('Auth.User', $cookie, true, '+2 weeks');
                    unset($this->request->data['User']['remember_me']);
                } else {
                    $this->Cookie->delete('Auth');
                }
                if ($this->Session->read('Auth.User.role_id') == 4 && $this->Session->read('Auth.User.barber') == '') {
                    $this->Auth->logout();
                    $this->Session->setFlash(__('Invalid barber shop. Please try with barber shop login url.', true), 'default', array('class' => 'error'));
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                } elseif (in_array($this->Session->read('Auth.User.role_id'), array(1, 2))) {
                    $this->Auth->logout();
                    $this->Session->setFlash(__('Invalid email and password.', true), 'default', array('class' => 'error'));
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }
                //return $this->redirect($this->Auth->redirect());
                return $this->redirect('my_account');
            } else {
                $this->Session->setFlash(__('Invalid email and password'), 'default', array('class' => 'error'));
            }
        }
        $cookie = $this->Cookie->read('Auth');
        if (!empty($cookie) && isset($cookie['User'])) {
            $this->request->data['User']['email'] = $cookie['User']['email'];
            $this->request->data['User']['password'] = $cookie['User']['password'];
            $this->request->data['User']['remmber_me'] = 1;
        }
    }

    public function logout() {
        $barber = $this->Session->read('Auth.User.barber');
        $this->Auth->logout();
        if ($barber == '') {
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        } else {
            $this->redirect(array('controller' => 'users', 'action' => 'login', 'slug' => $barber));
        }
    }

    public function forgot_password() {


        $this->set('title_for_layout', 'Forgot Password');
        $flag = 0;
        if ($this->request->is('post')) {
            $this->User->recursive = 0;
            $user = $this->User->findByEmail($this->request->data['User']['email']);
            if (!empty($user) && isset($user)) {
                $this->User->id = $user['User']['id'];
                $activationKey = md5(uniqid());
                $this->User->saveField('activation_key', $activationKey);
                $url1 = SITE_FULL_URL . 'users/reset_password/' . $activationKey;
                $url = "<a href='$url1'>Click Here</a> and $url1";
                $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'ForgotPassword')));
                $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[fullname]', $user['User']['name'], $mail['SystemMail']['message']);

                $mail['SystemMail']['message'] = str_replace('[link]', $url1, $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);

                $this->Email->to = $user['User']['email'];

                $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
                $this->Email->subject = $mail['SystemMail']['subject'];
                $this->Email->sendAs = 'html';

                $this->Email->template = 'default';

                $this->set('message', $mail['SystemMail']['message']);
                $this->set('title', $mail['SystemMail']['title']);

                try {
                    $this->Email->send();
                    $enailStaus = 1;
                    // success message
                } catch (Exception $e) {
                    // failure message details echo $e->getMessage();
                    $enailStaus = 0;
                }

                if ($enailStaus) {
                    $this->Session->setFlash(__('An email has been sent with instructions for reset your password!', true), 'default', array('class' => 'success'));
                    $flag = 1;
                } else {

                    $this->Session->setFlash(__('An error occurred. Please try again!', true), 'default', array('class' => 'error'));
                }
            } else {
                $this->Session->setFlash(__('No user was found with the submitted email!'), 'default', array('class' => 'error'));
            }
            if ($flag == 1) {
                if (isset($this->params['slug'])) {
                    $this->redirect(array('controller' => 'users', 'action' => 'login', 'slug' => $this->params['slug']));
                } else {
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                }
            }
        }
    }

    public function reset_password($key) {
        //pr($this->params);
        $flag = 0;
        $this->set('title_for_layout', __('Reset Password'));
        if ($this->request->is('post')) {
            $user = $this->User->find('first', array('conditions' => array('User.activation_key' => $key)));
            if (!empty($user)) {
                $this->User->id = $user['User']['id'];
                $this->request->data['User']['activation_key'] = md5(uniqid());
                if ($this->User->save($this->request->data)) {
                    $this->Session->setFlash(__('Your password has been reset successfully!'), 'default', array('class' => 'success'));
                    $flag = 1;
                } else {
                    $this->Session->setFlash(__('An error occurred. Please try again!'), 'default', array('class' => 'error'));
                }
            }
            if ($flag == 1) {
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        } else {
            $user = $this->User->find('first', array('conditions' => array('User.activation_key' => $key)));
            if (empty($user)) {
                $this->Session->setFlash(__('Your forgot password link has been expired. Please try again!'), 'default', array('class' => 'error'));
                $this->redirect(array('controller' => 'users', 'action' => 'login'));
            }
        }
    }

    public function my_profile() {

        $this->set('title_for_layout', __('My Profile'));

        if ($this->Auth->User('role_id') == 3) {
            $this->Week->bindModel(array('hasOne' => array('Schedule' => array('foreignKey' => 'week_id', 'conditions' => array('Schedule.user_id' => $this->Auth->User('id'), 'Schedule.status' => 1)))));
            $schedules = $this->Week->find('all', array('order' => array('Week.id' => 'asc')));

            $this->set(compact('schedules'));
            $this->loadModel('AppointmentService');
            $this->AppointmentService->bindModel(array(
                  'belongsTo' => array(
                      'Service'=>array(
                          'foreignKey' => 'service_id', 'className' => 'Service', 
                      )
                   )
            ));
            $this->Appointment->recursive=2;
            $this->Appointment->bindModel(array(
                'hasMany'=>array(
                     'AppointmentService'=>array(
                         'foreignKey' => 'appointment_id', 'className' => 'AppointmentService',
                     )
                ),
                'belongsTo' => array(                   
                    'User' => array(
                        'foreignKey' => 'customer_id', 'className' => 'User',
                        'fields' => array('name', 'id','phone', 'show_notes', 'notes')
                    ),
                    'Barber' => array(
                        'foreignKey' => 'barber_id',
                        'className' => 'User',
                        'fields' => array('name', 'phone')
                    ), 'Slot')
                    ), false);


            $Appointment = $this->Appointment->find('all', array(
                'conditions' => array(
                    'Appointment.barber_id' => $this->Auth->User('id'),
                    'Appointment.status' =>0,
			'Appointment.date >=' => date("Y-m-d"),
                ),
                'order' => array('Appointment.date' => 'asc','Slot.time_24' => 'asc')
                    )
            );
            
//            $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'fields' => array('name', 'id', 'show_notes', 'notes')), 'Slot')), false);
//            $this->User->bindModel(array('hasMany' => array('Appointment' => array('foreignKey' => 'barber_id'))), false);

            $render = 'barber_my_profile';
        } else {
            $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'className' => 'User', 'fields' => array('name', 'id','phone')), 'Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'phone')), 'Slot')), false);
			
             $Appointment = $this->Appointment->find('all', array(
                'conditions' => array(
                    'Appointment.customer_id' => $this->Auth->User('id'),
                    'Appointment.status' =>0,
					'Appointment.date >=' => date("Y-m-d"),
                ),
                'order' => array('Appointment.date' => 'asc','Slot.time_24' => 'asc')
                    )
            );
             
            // $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'barber_id', 'fields' => array('name', 'id')), 'Slot')), false);
          //  $this->User->bindModel(array('hasMany' => array('Appointment' => array('foreignKey' => 'customer_id'))), false);
            $render = 'my_profile';
        }
        $this->User->recursive = 2;
        $this->User->unbindModel(array('belongsTo' => array('Role', 'Country')), false);
        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $this->Auth->User('id')
            )
                )
        );
//          $user = $this->User->findById($this->Auth->User('id'));
        
        $this->set(compact('user','Appointment'));
        $this->render($render);
    }

    public function my_account() {
        $this->set('title_for_layout', __('My Account'));
    }

    public function register() {
        if (!empty($this->request->data)) {


            $this->request->data['User']['activation_key'] = md5(uniqid());
            $this->request->data['User']['role_id'] = 4;
            $this->request->data['User']['status'] = 1;
            if ($res = $this->User->save($this->request->data)) {
                $id = $res['User']['id'];
                $url = Router::url(array('controller' => 'users', 'action' => 'activate', $id, $this->request->data['User']['activation_key']), true);
                $acturl = "<a href='$url'>Click Here</a> and $url";

                $site_url = SITE_FULL_URL;

                $site_url = "<a href='$site_url'>$site_url</a>";


                $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'Registration')));
                $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[first_name]', ucfirst($this->request->data['User']['name']), $mail['SystemMail']['message']);
                //$mail['SystemMail']['message'] = str_replace('[activation_link]', $acturl, $mail['SystemMail']['message']);
                //$mail['SystemMail']['message'] = str_replace('[url]', $url, $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[link]', $site_url, $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[site_url]', SITE_FULL_URL, $mail['SystemMail']['message']);
                $this->Email->to = $this->request->data['User']['email'];

                $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
                $this->Email->subject = $mail['SystemMail']['subject'];
                $this->Email->sendAs = 'html';

                $this->Email->template = 'default';

                $this->set('message', $mail['SystemMail']['message']);
                $this->set('title', $mail['SystemMail']['title']);
                try {
                    $this->Email->send();
                    $enailStaus = 1;
                    // success message
                } catch (Exception $e) {
                    // failure message details echo $e->getMessage();
                    $enailStaus = 1;
                }
                $this->Session->setFlash(__('Registration successfully complete.', true), 'default', array('class' => 'success'));
                $user = $this->User->findById($res['User']['id']);
                $this->Session->write('Auth', $user);
                if (isset($this->params['slug'])) {
                    $this->Session->write('Auth.User.barber', $this->params['slug']);
                }
                return $this->redirect('my_account');
            } else {
                $this->Session->setFlash(__('Something went wrong. Please try again.'), 'default', array('class' => 'error'));
            }
        }
    }

    public function activate($id = null, $key = null) {
        if ($id == null || $key == null) {
            $this->redirect(array('action' => 'login'));
        }
        if ($this->User->hasAny(array(
                    'User.id' => $id,
                    'User.activation_key' => $key,
                    'User.status' => 0,
                ))) {

            $user = $this->User->findById($id);
            $this->User->id = $user['User']['id'];
            $this->User->id;
            $this->User->saveField('status', 1);
            $this->User->saveField('activation_key', md5(uniqid()));

            $this->Session->setFlash(__('Account activated successfully.'), 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash(__('Activation link has been expired. Please try again.'), 'default', array('class' => 'error'));
        }
        $this->redirect('login');
    }

    public function change_password() {
        $this->set('title_for_layout', __('Change Password'));
        $this->User->recursive = -1;
        $user = $this->User->findById($this->Auth->User('id'));
        $this->set(compact('user'));
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['User']['id'] = $this->Session->read('Auth.User.id');
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Your password has been change successfully.'), 'default', array('class' => 'success'));
                $this->redirect(array('controller' => 'users', 'action' => 'my_profile'));
            } else {
                $this->Session->setFlash(__('Password does not update. Please try again or contact system Administrator.'), 'default', array('class' => 'error'));
            }
        }
    }

    public function edit_profile() {
        if ($this->Auth->User('role_id') == 3) {
            return $this->redirect(array('controller' => 'users', 'action' => 'my_profile'));
        }
        $this->set('title_for_layout', __('Edit Profile'));
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->User->id = $this->Session->read('Auth.User.id');
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('Your profile has been updated successfully.'), 'default', array('class' => 'success'));
                $this->redirect(array('controller' => 'users', 'action' => 'my_profile'));
            } else {
                $this->Session->setFlash(__('Password does not update. Please try again or contact system Administrator.'), 'default', array('class' => 'error'));
            }
        }
        $this->User->recursive = -1;
        $this->request->data = $this->User->findById($this->Auth->User('id'));
    }

    public function check_password() {
        $this->layout = false;
        $this->autoRender = false;
        $field = $this->request->data['field'];
        $fieldValue = $this->request->data['User'][$field];
        $passwordCheck = AuthComponent::password($fieldValue);
        $count = $this->User->find('count', array('conditions' => array('User.password' => $passwordCheck, 'User.id' => $this->Session->read('Auth.User.id'))));

        if ($count > 0) {
            exit('true');
        } else {
            exit('false');
        }
    }

    function notes() {
        if ($this->request->is('put')) {

            if (isset($this->request->data['User']['show_notes'])) {
                $this->request->data['User']['show_notes'] = 1;
            } else {
                $this->request->data['User']['show_notes'] = 0;
            }
            if ($this->User->save($this->request->data, false)) {
                $this->Session->setFlash(__('Your notes has been updated successfully.'), 'default', array('class' => 'success'));
            }
        }
        $this->request->data = $this->User->findById($this->Auth->User('id'), array('id', 'notes', 'show_notes'));
    }

    public function waiting_list() {
        if ($this->Session->read('Auth.User.role_id') == 4 && $this->Session->read('Auth.User.barber') == '') {
            $this->Auth->logout();
            $this->Session->setFlash(__('Invalid barber shop. Please try with barber shop login url.', true), 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        $currentDay = date('l');
        $currentDayData = $this->Week->findByName($currentDay);
        $week_id = $currentDayData['Week']['id'];
        $barber = $this->Session->read('Auth.User.barber');
        $barberAdmin = $this->User->findByShopSlugAndStatus($barber, 1);

        if (!empty($barberAdmin)) {
            $this->Schedule->bindModel(array('belongsTo' => array('User' => array('fields' => array('id', 'name', 'image', 'parent_id')))));
            $barber_users = $this->Schedule->find('all', array('conditions' => array('User.parent_id' => $barberAdmin['User']['id'], 'User.status' => 1, 'Schedule.week_id' => $week_id, 'Schedule.status' => 1, 'Schedule.working' => 1)));

            if (empty($barber_users)) {
                $this->Session->setFlash(__('Today barbers not working. Please try with another shop url.', true), 'default', array('class' => 'error'));
                $this->redirect(array('controller' => 'users', 'action' => 'my_account'));
            }
            $this->Appointment->bindModel(array('belongsTo' => array('Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'id', 'parent_id', 'image')), 'User' => array('foreignKey' => 'customer_id', 'fields' => array('name', 'id')), 'Slot')), false);

            $barber_ids = Set::extract('/User/id', $barber_users);

            $reservations = $this->Appointment->find('all', array('conditions' => array('Appointment.barber_id' => $barber_ids, 'Appointment.status' => 0), 'order' => array('Slot.time' => 'asc')));

            $reservation_user = $reservation_users = array();
            foreach ($reservations as $reservation) {
                $reservation_user['barber_name'] = $reservation['Barber']['name'];
                $reservation_user['barber_image'] = $reservation['Barber']['image'];
                $reservation_user['name'] = $reservation['User']['name'];
                $reservation_user['time_order'] = strtotime($reservation['Slot']['time']);
                $reservation_user['date'] = $reservation['Appointment']['date'];
                $reservation_user['time'] = $reservation['Slot']['time'];
                $reservation_users[] = $reservation_user;
            }
            $this->WalkinAppointment->bindModel(array('hasMany' => array('WalkinAppointmentBarber')));
            $this->WalkinAppointmentBarber->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'barber_id', 'fields' => array('User.image', 'User.id', 'User.name')))));
            $this->WalkinAppointment->recursive = 2;
            $walkins = $this->WalkinAppointment->find('all', array('conditions' => array('WalkinAppointment.created >='=>date('Y-m-d'),'WalkinAppointment.shop_id' => $barberAdmin['User']['id']), 'order' => array('WalkinAppointment.created' => 'asc')));

            $walkin_user = $walkin_users = array();

            foreach ($walkins as $walkin) {

                if (!empty($walkin['WalkinAppointmentBarber'])) {
                    $walkin_user['WalkinAppointmentBarber'] = $walkin['WalkinAppointmentBarber'];
                    $walkin_user['name'] = $walkin['WalkinAppointment']['name'];
                    $walkin_user['time_order'] = strtotime(date('h:i A', strtotime($walkin['WalkinAppointment']['created'])));
                    $walkin_user['time'] = date('h:i A', strtotime($walkin['WalkinAppointment']['created']));
                    $walkin_users[] = $walkin_user;
                }
            }

            //$reservation_walkins = array_merge($reservation_users, $walkin_users);
            $reservation_walkins = $walkin_users;
            if (!empty($reservation_walkins)) {
                usort($reservation_walkins, function($a, $b) {
                    return $a['time_order'] - $b['time_order'];
                });
            }
            $this->set('reservation_walkins', $reservation_walkins);
        } else {
            $this->Session->setFlash(__('The barber shop not active yet. Please try with another shop url.', true), 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'users', 'action' => 'my_account'));
        }
    }

    public function admin_advertisement() {
        $this->loadModel('User');
        $this->set('title_for_layout', sprintf(__('Advertisement')));

        $con = array();
        $con = array('User.id' => $this->Auth->user('id'));
        $this->Advertisement->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));

        $this->paginate = array('conditions' => $con);
        $this->Advertisement->recursive = 1;
        $data = $this->paginate('Advertisement');
        $this->set(compact('data'));
    }

    public function admin_add_advertisement($id = null) {

        if ($id == null) {
            $rowcount = $this->Advertisement->find('count', array('conditions' => array('Advertisement.user_id' => $this->Auth->user('id'))));
            if ($rowcount >= 10) {
                $this->Session->setFlash('You can add maximum 10 advertisement.', 'default', array('class' => 'error'));
                return $this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'admin_advertisement'));
            }
        }
        $this->loadModel('User');
        $this->set('title_for_layout', sprintf(__('Advertisement')));
        if (!empty($this->request->data)) {
            $destination = WWW_ROOT . 'uploads' . DS . 'advertisement' . DS;
//            pr($this->request->data); die;
            if (isset($this->request->data['Advertisement']['newimage']) && !empty($this->request->data['Advertisement']['newimage']['name'])) {
                $image = $this->request->data['Advertisement']['newimage'];
                $file_name = $image['name'];
                $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                $name = pathinfo($file_name, PATHINFO_FILENAME);
                $advertisements = 'advertisements' . time() . "_" . rand(000000, 999999) . '.' . $ext;
                $old_Image = $this->request->data['Advertisement']['image'];
                $this->request->data['Advertisement']['image'] = $advertisements;


                $this->Advertisement->validate = array('newimage' => array(
                        'required' => array(
                            'rule' => array('extension', array('jpeg', 'jpg', 'png', 'gif')),
                            'message' => 'Select jpeg,jpg,png,gif image only.',
                        )
                ));
                if ($id == null) {
                    $this->Advertisement->create();
                    $this->request->data['Advertisement']['user_id'] = $this->Auth->user('id');
                }
                if ($this->Advertisement->validates()) {
                    if ($user = $this->Advertisement->save($this->request->data)) {
                        if (isset($advertisements) && $advertisements != '') {
                            $attachments_path = $destination . DS . $advertisements;
                            $data = move_uploaded_file($image['tmp_name'], $attachments_path);
                            if ($old_Image != "") {
                                if (file_exists($destination . DS . $old_Image)) {
                                    unlink($destination . DS . $old_Image);
                                }
                            }
                        }

                        if ($id == null) {
                            $this->Session->setFlash('The advertisement has been saved', 'default', array('class' => 'success'));
                        } else {
                            $this->Session->setFlash('The advertisement has been updated', 'default', array('class' => 'success'));
                        }
                        $this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'admin_advertisement'));
                    }
                }
            }
        } else {
            if ($id != null) {

                $this->request->data = $Advertisement = $this->Advertisement->findById($id);
//                   pr($Advertisement); die;
            }
        }

        $this->set(compact('Advertisement'));
    }

    public function admin_delete_advertisement($id = null) {

        $this->Advertisement->id = $id;
        if (!$this->Advertisement->exists()) {
            $this->Session->setFlash('Invalid Advertisement.', 'default', array('class' => 'error'));
            return $this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'admin_advertisement'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Advertisement->delete()) {
            $this->Session->setFlash('The advertisement has been deleted.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The advertisement could not be deleted. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'admin_advertisement'));
    }

    public function admin_changes_status_advertisement($id = null, $status = null) {

        $this->Advertisement->id = $id;
        if (!$this->Advertisement->exists()) {
            $this->Session->setFlash('Invalid Advertisement.', 'default', array('class' => 'error'));
            return $this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'admin_advertisement'));
        }
        $this->Advertisement->id = $id;
        $this->request->data['Advertisement']['status'] = $status;
        if ($this->Advertisement->save($this->request->data)) {
            $this->Session->setFlash('The advertisement has been updated.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The advertisement could not be updated.', 'default', array('class' => 'error'));
        }
        return $this->redirect(array('admin' => true, 'controller' => 'users', 'action' => 'admin_advertisement'));
    }
	
	public function barberschedule(){
		$this->layout = 'default';
		$this->set('title_for_layout', 'Schedules');
        $id = $this->Auth->user('id');
        $this->Week->bindModel(array('hasOne' => array('Schedule' => array('foreignKey' => 'week_id', 'conditions' => array('Schedule.user_id' => $id, 'Schedule.status' => 1)))));
        $schedules = $this->Week->find('all', array('order' => array('Week.id' => 'asc')));
        //pr($schedules);die;
        $this->set(compact('schedules'));
	}
	
	
	public function users_barber_add_lunch() {
		if ($this->request->is('post')) {
            $this->LunchBreak->deleteAll(array('schedule_id' => $this->request->data['LunchBreak']['schedule_id'], 'user_id' => $this->Session->read('Auth.User.id')));
			if(isset($this->request->data['LunchBreak']['slot_id'])) {
				foreach ($this->request->data['LunchBreak']['slot_id'] as $slot_id) {
					$this->LunchBreak->create();
					$lunch['LunchBreak']['schedule_id'] = $this->request->data['LunchBreak']['schedule_id'];
					$lunch['LunchBreak']['user_id'] = $this->Session->read('Auth.User.id');
					$lunch['LunchBreak']['slot_id'] = $slot_id;
					$this->LunchBreak->save($lunch);
				}
			}
            $this->Session->setFlash('Lunch break has been added.', 'default', array('class' => 'success'));
            return $this->redirect(array('controller' => 'users', 'action' => 'barberschedule', 'barber' => false));
        } else {
            $this->redirect(array('controller' => 'users', 'action' => 'barberschedule', 'barber' => false));
        }
    }
	
	public function barbervacation() {
		
        $this->layout = 'default';
        $this->set('title_for_layout', 'Vacations');
        $id = $this->Session->read('Auth.User.id');
        $conditions = array('Vacation.user_id' => $id,'Vacation.to_date >=' => date("Y-m-d 00:00:00"));
        $order = array('Vacation.from_date');
        $page = '1';
		$this->paginate = array('conditions' => $conditions, 'limit' => '50', 'order' => $order, 'page' => $page);
		//pr($this->paginate('Vacation'));die;
		$this->set('vacations', $this->paginate('Vacation'));
    }
    public function seat_appointment($id) {

        $this->Appointment->id = $id;
        $appointments = array();
        if (!$this->Appointment->exists()) {
            $this->Session->setFlash('Invalid Appointment.', 'default', array('class' => 'error'));
            return $this->redirect(Router::url($this->referer(), true));
        }
            
        $this->request->allowMethod('post', 'get');
        
        $this->request->data['Appointment']['status'] = 1;
        if ($this->Appointment->save($this->request->data)) {
           
            $this->loadModel('SystemMail');
            $res=$this->Appointment->findById($id);
            
            $admin=$this->User->findById($this->Auth->user('parent_id'));
            $customer=$this->User->findById($res['Appointment']['customer_id']);
            $url = $admin['User']['review_url'];
            $acturl = "<a href='$url'>Click Here</a> and $url";
            $site_url = SITE_FULL_URL;
            $site_url = "<a href='$site_url'>$site_url</a>";
            
            $shop_url=SITE_FULL_URL . $admin['User']['shop_slug'];
            $shop_name= ucfirst($admin['User']['shop_name']);
                        
            $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'Feedbacks')));
            $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
            $mail['SystemMail']['message'] = str_replace('[username]', ucfirst($customer['User']['name']), $mail['SystemMail']['message']);
            
            $mail['SystemMail']['message'] = str_replace('[LINK]', $url, $mail['SystemMail']['message']);
            $mail['SystemMail']['message'] = str_replace('[SHOP_LINK]', $shop_url, $mail['SystemMail']['message']);
            $mail['SystemMail']['message'] = str_replace('[SHOP_NAME]', $shop_name, $mail['SystemMail']['message']);
            
            $mail['SystemMail']['message'] = str_replace('[site_url]', SITE_FULL_URL, $mail['SystemMail']['message']);
            $this->Email->to = $customer['User']['email'];

            $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
            $this->Email->subject = $mail['SystemMail']['subject'];
            $this->Email->sendAs = 'html';

            $this->Email->template = 'default';

            $this->set('message', $mail['SystemMail']['message']);
            $this->set('title', $mail['SystemMail']['title']);
            try {
                $this->Email->send();
                $enailStaus = 1;
                // success message
            } catch (Exception $e) {
                // failure message details echo $e->getMessage();
                $enailStaus = 1;
            }
            $this->Session->setFlash('The Appointment has been seated.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The Appointment could not be seated.', 'default', array('class' => 'error'));
        }
        return $this->redirect(Router::url($this->referer(), true));
    }
    
    public function waitingListBarber(){
        $this->layout = 'default';
        $this->set('title_for_layout', 'Waiting List');
        $id = $this->Auth->user('id'); 
        $parentid = $this->Auth->user('parent_id');
        
        $walkins = $this->WalkinAppointmentBarbers->find('all', array('contain' => array('User'),'conditions' => array('WalkinAppointments.created >='=>date('Y-m-d'),'barber_id' => $id,'WalkinAppointments.status'=>'1'),'fields' => array('WalkinAppointmentBarbers.barber_id','User.first_name','User.last_name','User.id', 'WalkinAppointments.id','WalkinAppointments.name','WalkinAppointments.created')));
        $this->set('watinglists', $walkins);
    }
}
