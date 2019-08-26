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
class BarbersController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Barbers';

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('User', 'Schedule', 'Slot', 'Appointment', 'Week', 'LunchBreak', 'Vacation');
    var $helper = array('Common');
    var $components = array('Common', 'Email');

    /**
     * Displays a view
     *
     * @param mixed What page to display
     * @return void
     */
    function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('barber_login');
        if ($this->Auth->user('role_id') == 1) {
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
    }

    public function admin_index() {
        $this->User->recursive = 0;
        $this->set('title_for_layout', 'Barbers Manager');
        $this->set('total_users', $this->User->find('count', array('conditions' => array('User.role_id' => 3, 'User.parent_id' => $this->Auth->user('id')))));
    }

    public function admin_list() {
        $this->layout = false;

        if (isset($this->request->data['customActionType']) && $this->request->data['customActionType'] == 'group_action') {
            $this->User->updateAll(array('User.status' => $this->request->data['customActionName']), array('User.id' => $this->request->data['id']));
        }

        $phone_con = $specialty_con = $con = $email_con = $name_con = $gender_con = $status_con = array();

        if (isset($this->request->data['name']) && $this->request->data['name'] != '') {
            $name_con = array('User.name LIKE' => '%' . $this->request->data['name'] . '%');
        }
        if (isset($this->request->data['email']) && $this->request->data['email'] != '') {
            $email_con = array('User.email LIKE' => '%' . $this->request->data['email'] . '%');
        }
        if (isset($this->request->data['specialty']) && $this->request->data['specialty'] != '') {
            $specialty_con = array('User.specialty LIKE' => '%' . $this->request->data['shop_name'] . '%');
        }
        if (isset($this->request->data['phone']) && $this->request->data['phone'] != '') {
            $phone_con = array('User.phone' => $this->request->data['phone']);
        }
        if (isset($this->request->data['gender']) && $this->request->data['gender'] != '') {
            $gender_con = array('User.gender' => $this->request->data['gender']);
        }
        if (isset($this->request->data['pin']) && $this->request->data['pin'] != '') {
            $gender_con = array('User.pin' => $this->request->data['pin']);
        }

        if (isset($this->request->data['status']) && $this->request->data['status'] != '') {
            $status_con = array('User.status' => $this->request->data['status']);
        }

        switch ($this->request->data['order'][0]['column']) {
            case 1:
                $order = array('User.name' => $this->request->data['order'][0]['dir']);
                break;
            case 2:
                $order = array('User.specialty' => $this->request->data['order'][0]['dir']);
                break;
            case 3:
                $order = array('User.phone' => $this->request->data['order'][0]['dir']);
                break;
            case 4:
                $order = array('User.gender' => $this->request->data['order'][0]['dir']);
                break;
            case 5:
                $order = array('User.pin' => $this->request->data['order'][0]['dir']);
                break;
            case 6:
                $order = array('User.status' => $this->request->data['order'][0]['dir']);
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

        $con = array_merge($phone_con, $specialty_con, $email_con, $name_con, $gender_con, $status_con, array('User.role_id' => 3, 'User.parent_id' => $this->Auth->user('id')));
        $this->User->bindModel(array('hasMany' => array('Appointment' => array('foreignKey' => 'barber_id', 'limit' => 1, 'fields' => array('id')))));
        $this->paginate = array('conditions' => $con, 'limit' => $this->request->data['length'], 'order' => $order, 'page' => $page);
        $this->User->recursive = 1;
        $this->set('users', $this->paginate('User'));
    }

    public function admin_add() {
        $this->set('title_for_layout', 'Add Barber');
        if (!empty($this->request->data)) {
            $this->request->data['User']['role_id'] = 3;
            $password = mt_rand(100000, 999999);
            $pin = $this->User->getPin();
            $this->request->data['User']['password'] = $password;
            $this->request->data['User']['pin'] = $pin;
            $this->request->data['User']['parent_id'] = $this->Auth->user('id');
            $this->request->data['User']['status'] = (isset($this->request->data['User']['status'])) ? $this->request->data['User']['status'] : 0;
            $image = $this->request->data['User']['image'];
            $image_name = '';
            if (isset($image['name']) && !empty($image['name'])) {
                $destination = WWW_ROOT . 'uploads' . DS . 'users' . DS;
                $image_name = $this->getFileName($this->request->data);
            }
            $this->request->data['User']['image'] = $image_name;
            if ($res = $this->User->save($this->request->data)) {
                if (isset($image['name']) && !empty($image['name'])) {
                    move_uploaded_file($image['tmp_name'], $destination . $image_name);
                }
                $shop_url = Router::url(array('admin' => false, 'controller' => 'users', 'action' => 'login', 'slug' => $this->Auth->user('shop_slug')), true);
                $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'BarberRegistration')));
                $mail['SystemMail']['message'] = str_replace('[first_name]', $res['User']['name'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[email]', $this->request->data['User']['email'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[password]', $password, $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[shop_url]', $shop_url, $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[pin]', $pin, $mail['SystemMail']['message']);
                $to = $this->request->data['User']['email'];
                $this->Email->to = $to;
                $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
                $this->Email->subject = $mail['SystemMail']['subject'];
                $this->Email->sendAs = 'html';
                $this->Email->template = 'default';
                $this->set('message', $mail['SystemMail']['message']);
                $this->set('title', $mail['SystemMail']['subject']);
                if ($this->Email->send()) {
                    $this->Session->setFlash('The barber has been saved', 'default', array('class' => 'success'));
                } else {
                    $this->Session->setFlash('The barber has been saved but email not send.', 'default', array('class' => 'success'));
                }
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The barber could not be saved', 'default', array('class' => 'error'));
            }
        }
    }

    public function admin_edit($id = null) {
        $this->set('title_for_layout', 'Edit Barber');
        $user = $this->User->findByIdAndRoleIdAndParentId($id, 3, $this->Auth->user('id'));
        if (!$id || empty($user)) {
            $this->Session->setFlash(__('Invalid barber', true));
            $this->redirect(array('action' => 'index'));
        }
        if (!empty($this->request->data)) {
            $this->request->data['User']['status'] = (isset($this->request->data['User']['status'])) ? $this->request->data['User']['status'] : 0;
            $image = $this->request->data['User']['image'];
            $image_name = $this->request->data['User']['old_image'];
            if (isset($image['name']) && !empty($image['name'])) {
                $destination = WWW_ROOT . 'uploads' . DS . 'users' . DS;
                $image_name = $this->getFileName($this->request->data);
            }
            $this->request->data['User']['image'] = $image_name;
            if ($this->User->save($this->request->data)) {
                if (isset($image['name']) && !empty($image['name'])) {
                    if (move_uploaded_file($image['tmp_name'], $destination . $image_name)) {
                        if ($this->request->data['User']['old_image'] != '' && file_exists(WWW_ROOT . 'uploads' . DS . 'users' . DS . $this->request->data['User']['old_image'])) {
                            @unlink(WWW_ROOT . 'uploads' . DS . 'users' . DS . $this->request->data['User']['old_image']);
                        }
                    }
                }
                $this->Session->setFlash('The barber has been saved', 'default', array('class' => 'success'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The barber could not be saved', 'default', array('class' => 'error'));
            }
        }
        $this->request->data = $user;
    }

    public function admin_delete($id = null) {

        $this->User->id = $id;
        if (!$this->User->exists()) {
            $this->Session->setFlash('Invalid barber.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->User->delete()) {
            $this->Session->setFlash('The barber has been deleted.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The barber could not be deleted. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    function admin_schedule($id) {
        $user = $this->User->findByIdAndRoleIdAndParentId($id, 3, $this->Auth->user('id'));
        if (empty($user)) {
            $this->Session->setFlash('Invalid barber.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->set('title_for_layout', $user['User']['name'] . ' Schedule');

        $this->Week->bindModel(array('hasOne' => array('Schedule' => array('foreignKey' => 'week_id', 'conditions' => array('Schedule.user_id' => $id, 'Schedule.status' => 1)))));
        $schedules = $this->Week->find('all', array('order' => array('Week.id' => 'asc')));
        //pr($schedules);die;
        $this->set(compact('schedules'));
    }

    public function admin_add_schedule($id = null, $week_id = null) {
        $user = $this->User->findByIdAndRoleIdAndParentId($id, 3, $this->Auth->user('id'));
        $week = $this->Week->findById($week_id);
        $existSchedule = $this->Schedule->findByWeekIdAndUserId($week_id, $id);
        if (empty($user) || empty($week) || !empty($existSchedule)) {
            $this->Session->setFlash('Invalid Schedule.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'schedule', $id, $week_id));
        }
        $this->set('title_for_layout', $user['User']['name'] . ' schedule for ' . $week['Week']['name']);
        if (!empty($this->request->data)) {
            $this->request->data['Schedule']['user_id'] = $id;
            $slots = $this->getServiceScheduleSlots($this->request->data['Schedule']['slot_time'], 0, $this->request->data['Schedule']['start_time'], $this->request->data['Schedule']['end_time']);
            if (empty($slots)) {
                $this->Session->setFlash('The barber schedule could not be created. Please, try again.', 'default', array('class' => 'error'));
                return $this->redirect(array('action' => 'add_schedule', $id, $week_id));
            }
            if ($schedule = $this->Schedule->save($this->request->data)) {

                foreach ($slots as $key => $slot) {
                    $slotsData['Slot']['schedule_id'] = $schedule['Schedule']['id'];
                    $slotsData['Slot']['time'] = $slot;
                    $slotsData['Slot']['time_24'] = $key;
                    $this->Slot->create();
                    $this->Slot->save($slotsData);
                }
                $this->Session->setFlash('The barber schedule has been created.', 'default', array('class' => 'success'));
                return $this->redirect(array('action' => 'schedule', $id, $week));
            } else {
                $this->Session->setFlash('The barber schedule could not be created. Please, try again.', 'default', array('class' => 'error'));
                return $this->redirect(array('action' => 'schedule', $id, $week));
            }
        }
        $this->request->data = $this->Schedule->findByUserIdAndStatusAndWeekId($id, 1, $week_id);
        $this->set(compact('week', 'user'));
    }

    public function admin_re_schedule($id = null, $week_id = null) {
        $user = $this->User->findByIdAndRoleIdAndParentId($id, 3, $this->Auth->user('id'));
        $week = $this->Week->findById($week_id);
        if ($week['Week']['name'] == date('l')) {
            $this->Session->setFlash('Current day can&#39;t be resceduled.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'schedule', $id, $week));
        }
        if (empty($user) || empty($week)) {
            $this->Session->setFlash('Invalid Schedule.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'schedule', $id, $week_id));
        }
        $this->set('title_for_layout', $user['User']['name'] . ' re-schedule for ' . $week['Week']['name']);
        if (!empty($this->request->data)) {
            $this->request->data['Schedule']['user_id'] = $id;
            $slots = $this->getServiceScheduleSlots($this->request->data['Schedule']['slot_time'], 0, $this->request->data['Schedule']['start_time'], $this->request->data['Schedule']['end_time']);
            if (empty($slots)) {
                $this->Session->setFlash('The barber schedule could not be re-schedule. Please, try again.', 'default', array('class' => 'error'));
                return $this->redirect(array('action' => 're_schedule', $id, $week_id));
            }
            $this->Schedule->updateAll(array('Schedule.status' => 0), array('Schedule.user_id' => $id, 'Schedule.week_id' => $week_id));
            if ($schedule = $this->Schedule->save($this->request->data)) {
                foreach ($slots as $key => $slot) {
                    $slotsData['Slot']['schedule_id'] = $schedule['Schedule']['id'];
                    $slotsData['Slot']['time'] = $slot;
                    $slotsData['Slot']['time_24'] = $key;
                    $this->Slot->create();
                    $this->Slot->save($slotsData);
                }
                $this->Session->setFlash('The barber schedule has been re-schedule.', 'default', array('class' => 'success'));
                return $this->redirect(array('action' => 'schedule', $id, $week));
            } else {
                $this->Session->setFlash('The barber schedule could not be re-schedule. Please, try again.', 'default', array('class' => 'error'));
                return $this->redirect(array('action' => 'schedule', $id, $week));
            }
        }
        $this->set(compact('week', 'user'));
    }

    public function admin_appointments($id) {
        $user = $this->User->findByIdAndRoleIdAndParentId($id, 3, $this->Auth->user('id'));
        if (empty($user)) {
            $this->Session->setFlash('Invalid barber.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->set('title_for_layout', $user['User']['name'] . ' Appointments');
        $this->set('total_appointments', $this->Appointment->find('count', array('conditions' => array('Appointment.barber_id' => $id))));
    }

    function admin_appointments_list($id) {
        $this->layout = false;
        $con = $created_con = $slot_time_con = $name_con = $status_con = array();
        if (isset($this->request->data['name']) && $this->request->data['name'] != '') {
            $name_con = array('User.name LIKE' => '%' . $this->request->data['name'] . '%');
        }
        if (isset($this->request->data['slot_time']) && $this->request->data['slot_time'] != '') {
            $slot_time_con = array('Slot.time LIKE' => '%' . $this->request->data['slot_time'] . '%');
        }
        if (isset($this->request->data['created']) && $this->request->data['created'] != '') {
            $created_con = array('Appointment.created LIKE' => '%' . $this->request->data['created'] . '%');
        }
        if (isset($this->request->data['status']) && $this->request->data['status'] != '') {
            $status_con = array('Appointment.status' => $this->request->data['status']);
        }

        switch ($this->request->data['order'][0]['column']) {
            case 0:
                $order = array('User.name' => $this->request->data['order'][0]['dir']);
                break;
            case 1:
                $order = array('Slot.time' => $this->request->data['order'][0]['dir']);
                break;
            case 2:
                $order = array('Appointment.created' => $this->request->data['order'][0]['dir']);
                break;
            case 3:
                $order = array('Appointment.status' => $this->request->data['order'][0]['dir']);
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

        $con = array_merge($created_con, $slot_time_con, $name_con, $status_con, array('Appointment.barber_id' => $id));
        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'fields' => array('name', 'id')), 'Slot')), false);
        $this->paginate = array('conditions' => $con, 'limit' => $this->request->data['length'], 'order' => $order, 'page' => $page);
        $this->set('users', $this->paginate('Appointment'));
    }

    function calendar($selectDate = null) {

// $dateInput = explode('/', $date);
//        if (count($dateInput) == 3) {
//            return checkdate($dateInput[0], $dateInput[1], $dateInput[2]);
//        } else {
//            return FALSE;
//        }
        if ($this->Session->read('Auth.User.role_id') == 4 && $this->Session->read('Auth.User.barber') == '') {
            $this->Auth->logout();
            $this->Session->setFlash(__('Invalid barber shop. Please try with barber shop login url.', true), 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        $currentDay = date('l');
        $currentDate = date('Y-m-d');
        if (isset($this->request->data['newdate']) && $this->request->data['newdate'] != '') {
            $this->Session->write('CalenderDate', $this->request->data['newdate']);
            $currentDate = $this->request->data['newdate'];
        }

        if (!empty($this->Session->read('CalenderDate'))) {
            $currentDate = date($this->Session->read('CalenderDate'));
        } else {
            $currentDate = date('Y-m-d');
        }

        $currentDayData = $this->Week->findByName($currentDay);
        $week_id = $currentDayData['Week']['id'];
        $barber = $this->Session->read('Auth.User.barber');
        $barberAdmin = $this->User->findByShopSlugAndStatus($barber, 1);
        if (!empty($barberAdmin)) {
            /* list of users , on leave today */
            $vacation_user = $this->Vacation->find('list', array('conditions' => array('Vacation.date' => $currentDate), 'fields' => array('id', 'user_id')));

            $this->Schedule->bindModel(array('belongsTo' => array('User' => array('fields' => array('id', 'name', 'image', 'parent_id')))));

            $barber_users = $this->Schedule->find('all', array('conditions' => array('User.parent_id' => $barberAdmin['User']['id'], 'User.status' => 1, 'Schedule.week_id' => $week_id, 'Schedule.status' => 1, 'Schedule.working' => 1, 'NOT' => array('Schedule.user_id' => $vacation_user))));
//            pr($barber_users);
//            die;
            if (empty($barber_users)) {
                $this->Session->setFlash(__('Today barbers not working. Please try with another shop url.', true), 'default', array('class' => 'error'));
                $this->redirect(array('controller' => 'users', 'action' => 'my_account'));
            }

            $this->set('barbers', $barber_users);
            $this->set('currentDate', $currentDate);
        } else {
            $this->Session->setFlash(__('The barber shop not active yet. Please try with another shop url.', true), 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'users', 'action' => 'my_account'));
        }
    }

            

    function confirmation() {
        
    }

    public function barber_login() {
        $this->layout = 'barber_login';
        $this->set('title_for_layout', 'Barber Login');
        if ($this->Session->read('Auth.User')) {
            $this->redirect(array('controller' => 'barbers', 'action' => 'schedules', 'barber' => true));
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
                $this->redirect(array('controller' => 'barbers', 'action' => 'schedules', 'barber' => true));
            } else {
                $this->Session->setFlash(__('Invalid email or password.'), 'default', array('class' => 'error'));
            }
        }
        $cookie = $this->Cookie->read('Auth');
        if (isset($cookie) && !empty($cookie)) {
            $this->request->data['User']['email'] = $cookie['User']['email'];
            $this->request->data['User']['password'] = $cookie['User']['password'];
            $this->request->data['User']['remmber_me'] = 1;
        }
        $this->render(false);
    }

    public function barber_logout() {
        $this->Session->setFlash('Logout Successfully.', 'default', array('class' => 'success'));
        $this->Auth->logout();
        $this->redirect(array('barber' => true, 'controller' => 'barbers', 'action' => 'login'));
    }

    public function barber_schedules() {
        $this->layout = 'barber';
        $this->set('title_for_layout', 'Schedules');
        $id = $this->Auth->user('id');
        $this->Week->bindModel(array('hasOne' => array('Schedule' => array('foreignKey' => 'week_id', 'conditions' => array('Schedule.user_id' => $id, 'Schedule.status' => 1)))));
        $schedules = $this->Week->find('all', array('order' => array('Week.id' => 'asc')));
        //pr($schedules);die;
        $this->set(compact('schedules'));
    }

    public function barber_add_lunch() {
        if ($this->request->is('post')) {
            $this->LunchBreak->deleteAll(array('schedule_id' => $this->request->data['LunchBreak']['schedule_id'], 'user_id' => $this->Session->read('Auth.User.id')));
            foreach ($this->request->data['LunchBreak']['slot_id'] as $slot_id) {
                $this->LunchBreak->create();
                $lunch['LunchBreak']['schedule_id'] = $this->request->data['LunchBreak']['schedule_id'];
                $lunch['LunchBreak']['user_id'] = $this->Session->read('Auth.User.id');
                $lunch['LunchBreak']['slot_id'] = $slot_id;
                $this->LunchBreak->save($lunch);
            }
            $this->Session->setFlash('Lunch break has been added.', 'default', array('class' => 'success'));
            return $this->redirect(array('controller' => 'barbers', 'action' => 'schedules', 'barber' => true));
        } else {
            $this->redirect(array('controller' => 'barbers', 'action' => 'schedules', 'barber' => true));
        }
    }

    public function barber_vacations() {
        $this->layout = 'barber';
        $this->set('title_for_layout', 'Vacations');
        $id = $this->Session->read('Auth.User.id');
        $conditions = array('Vacation.user_id' => $id);
        $order = array('Vacation.date');
        $page = '1';
        $this->paginate = array('conditions' => $conditions, 'limit' => '50', 'order' => $order, 'page' => $page);
        $this->set('vacations', $this->paginate('Vacation'));
    }

    /* Function to Add Barber Vacations days */

    public function barber_add_vacation() {
        $this->layout = 'barber';
        $this->set('title_for_layout', 'Vacations');

        if ($this->request->is('post')) {
            foreach ($this->request->data['Vacation']['date'] as $Date) {
                if (!empty($Date)) {
                    $this->Vacation->create();
                    $VacationData['Vacation']['user_id'] = $this->Session->read('Auth.User.id');
                    $VacationData['Vacation']['date'] = date('Y-m-d', strtotime($Date));
                    $this->Vacation->save($VacationData);
                }
            }
            $this->Session->setFlash('Vacation Date has been added.', 'default', array('class' => 'success'));
            return $this->redirect(array('action' => 'vacations', 'barber' => true));
        } else {
            
        }
    }

    public function barber_edit_vacation($id = null) {
        $this->layout = 'barber';
        $this->set('title_for_layout', 'Edit Vacation');
        $Vacation = $this->Vacation->findById($id);

        if (!$id || empty($Vacation)) {
            $this->Session->setFlash(__('Invalid barber', true));
            $this->redirect(array(array('controller' => 'barbers', 'action' => 'vacations')));
        }

        if (!empty($this->request->data)) {

            foreach ($this->request->data['Vacation']['date'] as $Date) {
                if (!empty($Date)) {

                    $VacationData['Vacation']['id'] = $this->request->data['Vacation']['id'];
                    $VacationData['Vacation']['date'] = date('Y-m-d', strtotime($Date));

                    $this->Vacation->save($VacationData);
                }
            }
            $this->Session->setFlash('Vacation Date has been Updated.', 'default', array('class' => 'success'));
            return $this->redirect(array('controller' => 'barbers', 'action' => 'vacations', 'barber' => true));
        }
        $this->request->data = $Vacation;
    }

    public function barber_delete_date($id = null) {

        $this->Vacation->id = $id;
        if (!$this->Vacation->exists()) {
            $this->Session->setFlash('Invalid Vacation Date.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'vacations'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Vacation->delete()) {
            $this->Session->setFlash('The Vacation Date has been deleted.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The Vacation Date could not be deleted. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(array('action' => 'vacations'));
    }

}
