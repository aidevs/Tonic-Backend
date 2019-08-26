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
    public $uses = array('User', 'Schedule', 'Slot', 'Appointment', 'Week', 'LunchBreak', 'Vacation', 'ReserveSlot', 'Setting');
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
                $order = array('User.phone' => $this->request->data['order'][0]['dir']);
                break;
            case 3:
                $order = array('User.gender' => $this->request->data['order'][0]['dir']);
                break;
            case 4:
                $order = array('User.pin' => $this->request->data['order'][0]['dir']);
                break;
            case 5:
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
        $this->loadModel('Service');
        $this->loadModel('BarberService');
        $this->set('title_for_layout', 'Add Barber');
        $barber_limit_info = $this->Setting->find('first', array('conditions' => array('Setting.key' => 'Site.barber_limit')));

        $barber_count = $this->User->find('count', array('conditions' => array('User.parent_id' => $this->Auth->user('id'))));

        //echo $barber_count;die;

        $barber_info = $this->User->find('first', array('conditions' => array('User.id' => $this->Auth->user('id'))));


        if (empty($barber_info['User']['unlimited_barber']) && $barber_count >= $barber_limit_info['Setting']['value']) {
            //echo $this->Auth->user('unlimited_barber');die;
            $this->Session->setFlash('You have reached the maximum limit of barbers. Please contact admin to increase your limit.', 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
        }

        if (!empty($this->request->data)) {
            $this->request->data['User']['role_id'] = 3;
            $password = mt_rand(100000, 999999);
            $pin = $this->User->getPin($this->Auth->user('id'));
            if($this->Auth->user('unlimited_barber')==0){
                $this->request->data['User']['password'] = $this->Auth->user('password');
                $password='Same as your admin password.';
            }else{
            $this->request->data['User']['password'] = $password;
            }
//            $this->request->data['User']['pin'] = $pin;
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
                $this->BarberService->create();
                $data = array();
                $serviceArr = array();
                if (isset($this->request->data['User']['service_id']) && !empty($this->request->data['User']['service_id'])) {
                    $i = $time = 0;
                    foreach ($this->request->data['User']['service_id'] as $service_id) {
                        $data[$i]['BarberService']['service_id'] = $service_id;
                        $data[$i]['BarberService']['user_id'] = $res['User']['id'];
                        $i++;
                    }
                }
                
                if ($this->BarberService->saveMany($data)) {
                    
                }
               
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
                $mail['SystemMail']['message'] = str_replace('[pin]', $this->request->data['User']['pin'], $mail['SystemMail']['message']);
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
                    $this->Session->setFlash('The barber has been saved', 'default', array('class' => 'success'));
                } else {
                    $this->Session->setFlash('The barber has been saved but email not send.', 'default', array('class' => 'success'));
                }
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The barber could not be saved', 'default', array('class' => 'error'));
            }
        }

        $pin = $this->User->getPinList($this->Auth->user('id'), null);

        $this->set('pin', $pin);
        $service = $this->Service->find('list', array('conditions' => array('Service.user_id'=>$this->Auth->user('id'),'Service.status' => 1), 'order' => array('Service.name' => 'asc'), 'fields' => array('Service.id', 'Service.service_name_time')));
        $this->set('services', $service);
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
        $pin = $this->User->getPinList($this->Auth->user('id'), $id);

        $user_id = $this->Auth->user('id');
        $pins = $this->User->find('list', array('conditions' => array('parent_id' => $user_id, 'pin !=' => $user['User']['pin'], 'id != ' => '84'), 'order' => array('pin' => 'desc'), 'fields' => array('pin')));
        $generated = array();
        for ($i = 0; $i <= 99; $i++) {
            $generated[(strlen($i) < 2) ? '0' . $i : $i] = (strlen($i) < 2) ? '0' . $i : $i;
        }
        //$generated = array_diff($generated, $pins);


        $this->set('assigned_pins', $pins);

        //$this->set('pin', $pin);
        $this->set('pin', $generated);

        $this->request->data = $user;
    }

    public function admin_delete($id = null) {

        $user = $this->User->findById($id);
        if (empty($user)) {
            $this->Session->setFlash('Invalid barber.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($user['User']['deleted'] == 1) {
            $msg = 'un-archived';
            $deleted = 0;
            $status = 1;
        } else {
            $msg = 'archived';
            $deleted = 1;
            $status = 0;
        }
        if ($this->User->updateAll(array('User.deleted' => $deleted, 'User.status' => $status), array('User.id' => $id))) {
            $this->Session->setFlash('The barber has been ' . $msg . '.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The barber could not be ' . $msg . '. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(array('action' => 'index'));
    }
    public function admin_force_delete($id = null) {

        $user = $this->User->findById($id);
        if (empty($user)) {
            $this->Session->setFlash('Invalid barber.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->request->allowMethod('post', 'delete');
        
        if ($this->User->delete($id)) {
            $this->Session->setFlash('The barber has been deleted.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The barber could not be deleted. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(array('action' => 'index'));
    }

    function admin_schedule($id = null) {
        $user = $this->User->findByIdAndRoleIdAndParentId($id, 3, $this->Auth->user('id'));
        if (empty($user)) {
            $this->Session->setFlash('Invalid barber.', 'default', array('class' => 'error'));
            $this->redirect(array('action' => 'index'));
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
            $created_con = array('Appointment.date LIKE' => '%' . $this->request->data['created'] . '%');
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
                $order = array('Appointment.date' => $this->request->data['order'][0]['dir']);
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

    function calendar($barberId = null, $scheduleId = null, $selectDate = null) {
        $this->loadModel('AppointmentSlot');    
        if ($this->Session->read('Auth.User.role_id') == 4 && $this->Session->read('Auth.User.barber') == '') {
            $this->Auth->logout();
            $this->Session->setFlash(__('Invalid barber shop. Please try with barber shop login url.', true), 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        $data = [];
        if ($barberId != '') {
            if (isset($this->request->query['q'])) {
                $queryData = $this->Common->decryptData($this->request->query['q']);
                parse_str($queryData, $data);
                if (!isset($data['t']) || !isset($data['id'])) {
                    $this->Session->setFlash(__('Please choose service first.', true), 'default', array('class' => 'error'));
                    return $this->redirect(array('action' => 'service', $barberId));
                }
            }
        }

        $flag = 0;
        // ----------- check date select or not------------
        if (isset($selectDate) && $selectDate != null) {
            // ----------- check date  format ------------
            $dateInput = explode('-', $selectDate);

            if (count($dateInput) == 3) {
                if (checkdate($dateInput[1], $dateInput[2], $dateInput[0]) == TRUE) {
                    $flag = 1;
                }
            }
        }

        //-----------flag "0" current date "1" for selected date
        if ($flag == 1) {
            $timestamp = strtotime($selectDate);

            $currentDay = date('l', $timestamp);
            $currentDate = $selectDate;
        } else {
            $currentDay = date('l');
            $currentDate = date('Y-m-d');
        }

        $currentDayData = $this->Week->findByName($currentDay);
        $week_id = $currentDayData['Week']['id'];

        if ($barberId != '') {
            $this->set('barberID', $barberId);
            $this->User->recursive=-1;
           $barber_detail = $this->User->find('first',array('conditions' => array('User.id' => $barberId, 'User.status' => 1),'fields' => array('User.id', 'name', 'image', 'parent_id','service_time')));

            $barber_info = $this->Schedule->find('first', array('conditions' => array('Schedule.user_id' => $barberId,'Schedule.week_id' => $week_id, 'Schedule.status' => 1)));
            $barber_info = array_merge($barber_detail,$barber_info);
            if (empty($barber_info)) {
                //$this->Session->setFlash(__('The barber is not working on selected date. Please try with another barber.', true), 'default', array('class' => 'error'));
                // $this->redirect(array('controller' => 'barbers', 'action' => 'calendar'));
            }

            //$barber_info = $this->User->find('first', array('conditions' => array('User.id' => $barberId)));

            $this->set('barber_info', $barber_info);
        } else {
            $barberId = "";
            $this->set('barberID', $barberId);
        }

        if ($scheduleId != null) {
            $this->set('scheduleID', $scheduleId);
        }

        $barber = $this->Session->read('Auth.User.barber');
//        echo $barber ; //die;
        $barberAdmin = $this->User->findByShopSlugAndStatus($barber, 1);
        $seldate = date("Y-m-d");
         $dates=[];
        $window_hours=0;
        if ($this->Auth->user('window_hours')) {
            $window_hours = $this->Auth->user('window_hours');
        }
        $currentTime = date('H:i:s', strtotime('+' . $window_hours . ' hours'));
       
        if (!empty($barberAdmin)) {

            /* list of users , on leave today */
            //$vacation_user = $this->Vacation->find('list', array('conditions' => array('Vacation.from_date <=' => $currentDate . ' 00:00:00', 'Vacation.to_date >=' => $currentDate . ' 23:55:00'), 'fields' => array('id', 'user_id')));

            // $vacation_user = $this->Vacation->find('list', array('conditions' => array('Vacation.date' => $currentDate), 'fields' => array('id', 'user_id')));
//            pr($vacation_user); die;

            $this->Schedule->bindModel(array('belongsTo' => array('User' => array('fields' => array('id', 'name', 'image', 'parent_id','insta_url')))));

            //$barber_users = $this->Schedule->find('all', array('conditions' => array('User.parent_id' => $barberAdmin['User']['id'], 'User.status' => 1, 'Schedule.week_id' => $week_id, 'Schedule.status' => 1, 'Schedule.working' => 1, 'NOT' => array('Schedule.user_id' => $vacation_user))));
            $new_barber_users=array();
            $barber_users = $this->Schedule->find('all', array('conditions' => array('User.parent_id' => $barberAdmin['User']['id'], 'User.status' => 1, 'User.deleted' => 0, 'Schedule.status' => 1), 'group' => array('Schedule.user_id')));
            if ($barberId == '' && !empty($barber_users)) {
                foreach ($barber_users as $barber_user) {
                     $id=$barber_user['Schedule']['user_id'];
                     $schedule_info = $this->Schedule->find('first', array('conditions' => array('Schedule.user_id' => $barber_user['Schedule']['user_id'],'Schedule.week_id' => $week_id, 'Schedule.status' => 1)));
                     $scheduleId= isset($schedule_info['Schedule']['id'])?$schedule_info['Schedule']['id']:0;
                    $lunch_breaks = $this->LunchBreak->find('list', array('conditions' => array('LunchBreak.schedule_id' => $scheduleId, 'LunchBreak.user_id' => $id), 'fields' => array('id', 'slot_id')));

                    $appointments = $this->Appointment->find('all', array('conditions' => array('Appointment.status' => 0, 'Appointment.schedule_id' => $scheduleId, 'Appointment.date' => $seldate)));


                    $ReserveSlot = $this->ReserveSlot->find('list', array('conditions' => array('ReserveSlot.user_id' => $id, 'ReserveSlot.date' => $seldate), 'fields' => array('id', 'slot_id')));

                    $booked_app = Set::extract('/Appointment/slot_id', $appointments);
                    $booked_app_id = Set::extract('/Appointment/id', $appointments);
                    $app_slot_ids = $this->AppointmentSlot->find('list', array('conditions' => array('AppointmentSlot.appointment_id' => $booked_app_id), 'fields' => array('id', 'slot_id')));
                    $booked_app = array_merge($booked_app, $app_slot_ids);
                
                    //$slotIds = array_merge($slotIds, $lunch_breaks);
                    $slotIds = $lunch_breaks;
                    if ($ReserveSlot) {
                        $slotIds = array_merge($slotIds, $ReserveSlot);
                    }
                    if(!empty($booked_app)){
                         $slotIds = array_merge($slotIds, $booked_app);
                    }
                    
                  
                   
                     $available_slots = $this->Slot->find('list', array('conditions' => array('Slot.schedule_id' => $scheduleId, 'Slot.time_24 >=' => $currentTime, 'NOT' => array('Slot.id' => $slotIds)), 'fields' => array('id', 'time'), 'order' => array('time_24' => 'asc')));
                    
                    $vacations = $this->Vacation->find('all', array('conditions' => array('user_id' => $id, 'DATE_FORMAT(from_date,"%Y-%m-%d") <=' => $seldate, 'DATE_FORMAT(to_date,"%Y-%m-%d") >=' => $seldate)));


                    $schedule_info = $this->Schedule->find('first', array('conditions' => array('id' => $scheduleId, 'Schedule.working' => 1)));

                    if (empty($schedule_info)) {
                        $available_slots = array();
                    }

                    if (!empty($vacations)) {
                        foreach ($vacations as $vacation) {
                            $s_time = strtotime($vacation['Vacation']['from_date']);
                            $e_time = strtotime($vacation['Vacation']['to_date']);
                            foreach ($available_slots as $key => $value) {
                                $val = strtotime($seldate . ' ' . $value);
                                if ($val >= $s_time && $val < $e_time) {
                                    unset($available_slots[$key]);
                                }
                            }
                        }
                    }
                   
                    $avl_slots=array_values($available_slots);
                    $barber_user['User']['next_av']=(isset($avl_slots[0]) && $avl_slots[0]!='')?$avl_slots[0]:'';
                 
                    array_push($new_barber_users, $barber_user);
                }
                
            }else{
                
                $now = time();
                for ($i = 0; $i <= 29; $i++) {
                     $timestamp=strtotime("+$i day", $now);
                     $date = date ("Y-m-d",$timestamp);
                     $currentDay = date('l', $timestamp);
                    // $currentDayData = $this->Week->findByName($currentDay);
                    // $is_av=$this->Common->check_barber_free($barberId,$currentDayData['Week']['id'],$date);
                    // if($is_av==0){
                    //     array_push($dates,$date);   
                    // }
                }
            }
            $this->set('disabled_dates', json_encode($dates));


            if (empty($barber_users)) {
                $this->Session->setFlash(__('No barber available for this shop.', true), 'default', array('class' => 'error'));
                $this->redirect(array('controller' => 'users', 'action' => 'my_account'));
            }


            $this->set('barbers', $new_barber_users);

            $this->set('currentDate', $currentDate);
        } else {
            $this->Session->setFlash(__('The barber shop not active yet. Please try with another shop url.', true), 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'users', 'action' => 'my_account'));
        }
    }
    function my_calendar($barberId = null, $scheduleId = null, $selectDate = null) {

       $barberId = $this->Session->read('Auth.User.id');
        $data = [];
        if ($barberId != '') {
            if (isset($this->request->query['q'])) {
                $queryData = $this->Common->decryptData($this->request->query['q']);
                parse_str($queryData, $data);
                if (!isset($data['t']) || !isset($data['id'])) {
                    $this->Session->setFlash(__('Please choose service first.', true), 'default', array('class' => 'error'));
                    return $this->redirect(array('action' => 'my_service'));
                }
            }
        }

        $flag = 0;
        // ----------- check date select or not------------
        if (isset($selectDate) && $selectDate != null) {
            // ----------- check date  format ------------
            $dateInput = explode('-', $selectDate);

            if (count($dateInput) == 3) {
                if (checkdate($dateInput[1], $dateInput[2], $dateInput[0]) == TRUE) {
                    $flag = 1;
                }
            }
        }

        //-----------flag "0" current date "1" for selected date
        if ($flag == 1) {
            $timestamp = strtotime($selectDate);

            $currentDay = date('l', $timestamp);
            $currentDate = $selectDate;
        } else {
            $currentDay = date('l');
            $currentDate = date('Y-m-d');
        }

        $currentDayData = $this->Week->findByName($currentDay);
        $week_id = $currentDayData['Week']['id'];

        if ($barberId != '') {
            $this->set('barberID', $barberId);

            $this->Schedule->bindModel(array('belongsTo' => array('User' => array('fields' => array('id', 'name', 'image', 'parent_id','service_time')))));

            $barber_info = $this->Schedule->find('first', array('conditions' => array('User.id' => $barberId, 'User.status' => 1, 'Schedule.week_id' => $week_id, 'Schedule.status' => 1)));

            //$barber_info = $this->User->find('first', array('conditions' => array('User.id' => $barberId)));

            $this->set('barber_info', $barber_info);
        } else {
            $barberId = "";
            $this->set('barberID', $barberId);
        }

        if ($scheduleId != null) {
            $this->set('scheduleID', $scheduleId);
        }

        $barber = $this->Session->read('Auth.User.parent_id');
        $this->set('barbers', array());

            $this->set('currentDate', $currentDate);    
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
            if (isset($this->request->data['LunchBreak']['slot_id'])) {
                foreach ($this->request->data['LunchBreak']['slot_id'] as $slot_id) {
                    $this->LunchBreak->create();
                    $lunch['LunchBreak']['schedule_id'] = $this->request->data['LunchBreak']['schedule_id'];
                    $lunch['LunchBreak']['user_id'] = $this->Session->read('Auth.User.id');
                    $lunch['LunchBreak']['slot_id'] = $slot_id;
                    $this->LunchBreak->save($lunch);
                }
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

            if (!empty($this->request->data['Vacation']['from_date']) && !empty($this->request->data['Vacation']['to_date'])) {
                $this->Vacation->create();
                $VacationData['Vacation']['user_id'] = $this->Session->read('Auth.User.id');
                $VacationData['Vacation']['from_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Vacation']['from_date']));
                $VacationData['Vacation']['to_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Vacation']['to_date']));
                if($this->Vacation->save($VacationData)){
                   $this->_sendNotifyToAdmin($VacationData);
                   $this->Session->setFlash('Vacation Date has been added.', 'default', array('class' => 'success'));
                   return $this->redirect(array('action' => 'vacations', 'barber' => true));
                }else{
                    $this->Session->setFlash('Vacation Date has been not added.', 'default', array('class' => 'error')); 
                }
            } else {
                $this->Session->setFlash('Vacation Date has been not added.', 'default', array('class' => 'error'));
            }
        } else {
            
        }
    }

    public function barber_edit_vacation($id = null) {
        $this->layout = 'barber';
        $this->set('title_for_layout', 'Edit Vacation');
        $Vacation = $this->Vacation->findById($id);


        if (!$id || empty($Vacation)) {

            $this->Session->setFlash(__('Invalid Vacation', true));
            $this->redirect(array(array('controller' => 'barbers', 'action' => 'vacations')));
        }

        if (!empty($this->request->data)) {

            if (!empty($this->request->data['Vacation']['from_date']) && !empty($this->request->data['Vacation']['to_date'])) {

                $VacationData['Vacation']['id'] = $this->request->data['Vacation']['id'];
                $VacationData['Vacation']['from_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Vacation']['from_date']));
                $VacationData['Vacation']['to_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Vacation']['to_date']));
                if($this->Vacation->save($VacationData)){
                   if(strtotime($Vacation['Vacation']['from_date'])!=strtotime($this->request->data['Vacation']['from_date']) || strtotime($Vacation['Vacation']['to_date'])!=strtotime($this->request->data['Vacation']['to_date'])){
                        $this->_sendNotifyToAdmin($VacationData); 
                   }
                   $this->Session->setFlash('Vacation Date has been saved.', 'default', array('class' => 'success'));
                   return $this->redirect(array('action' => 'vacations', 'barber' => true));
                }else{
                   $this->Session->setFlash('Vacation Date has been not saved.', 'default', array('class' => 'error'));
                }
            } else {
                $this->Session->setFlash('Vacation Date has been not saved.', 'default', array('class' => 'error'));
            }
        }
        $this->request->data = $Vacation;
    }
    function _sendNotifyToAdmin($vacation) {        
        $user = $this->User->findByRoleIdAndId(2,$this->Session->read('Auth.User.parent_id'));
        $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'BarberVacationNotify')));
        $mail['SystemMail']['message'] = str_replace('[BARBERNAME]', $this->Session->read('Auth.User.name'), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[BARBERPHONE]',$this->Session->read('Auth.User.phone'), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[VACATION_FROM]', date("m/d/Y h:i A", strtotime($vacation['Vacation']['from_date'])), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[VACATION_TO]', date("m/d/Y h:i A", strtotime($vacation['Vacation']['to_date'])), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
        $to = $user['User']['email'];
        $this->Email->to = $to;
        $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
        $this->Email->subject = $mail['SystemMail']['subject'];
        $this->Email->sendAs = 'html';
        $this->Email->template = 'default';
        $this->set('message', $mail['SystemMail']['message']);
        $this->set('title', $mail['SystemMail']['subject']);
        if ($this->Email->send()) {
            
        }
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

    public function barbercalendar($selectDate = null) {
        $flag = 0;
        // ----------- check date select or not------------
        if (isset($selectDate) && $selectDate != null) {
            // ----------- check date  format ------------
            $dateInput = explode('-', $selectDate);
            if (count($dateInput) == 3) {
                if (checkdate($dateInput[1], $dateInput[2], $dateInput[0]) == TRUE) {
                    $flag = 1;
                }
            }
        }
        //-----------flag "0" current date "1" for selected date
        if ($flag == 1) {
            $timestamp = strtotime($selectDate);

            $currentDay = date('l', $timestamp);
            $currentDate = $selectDate;
        } else {
            $currentDay = date('l');
            $currentDate = date('Y-m-d');
        }


        $currentDayData = $this->Week->findByName($currentDay);
        $week_id = $currentDayData['Week']['id'];



        //$vacation_user = $this->Vacation->find('list', array('conditions' => array('Vacation.from_date <=' => $currentDate, 'Vacation.to_date >=' => $currentDate), 'fields' => array('id', 'user_id')));

        $vacation_user = $this->Vacation->find('list', array('conditions' => array('DATE_FORMAT(from_date,"%Y-%m-%d") <=' => $currentDate, 'DATE_FORMAT(to_date,"%Y-%m-%d") >=' => $currentDate), 'fields' => array('id', 'user_id')));


        $this->Schedule->bindModel(array('belongsTo' => array('User' => array('fields' => array('id', 'name', 'image', 'parent_id')))));

        //$barber_users = $this->Schedule->find('first', array('conditions' => array('User.id' => $this->Session->read('Auth.User.id'), 'User.status' => 1, 'Schedule.week_id' => $week_id, 'Schedule.status' => 1, 'Schedule.working' => 1, 'NOT' => array('Schedule.user_id' => $vacation_user))));
        $barber_users = $this->Schedule->find('first', array('conditions' => array('User.id' => $this->Session->read('Auth.User.id'), 'User.status' => 1, 'User.deleted' => 0, 'Schedule.week_id' => $week_id, 'Schedule.status' => 1, 'Schedule.working' => 1)));

        if (empty($barber_users)) {
            $this->Session->setFlash(__('Today you are not working. Please choose another date.', true), 'default', array('class' => 'error'));
//            $this->redirect(array('controller' => 'Users', 'action' => 'my_account'));
        }

        $this->set('barberUsers', $barber_users);
        $this->set('currentDate', $currentDate);
    }

    public function cancel_appointment($id) {

        $this->Appointment->id = $id;
        $appointments = array();
        if (!$this->Appointment->exists()) {
            $this->Session->setFlash('Invalid Appointment.', 'default', array('class' => 'error'));
            return $this->redirect(Router::url($this->referer(), true));
        }

        $this->request->allowMethod('post', 'get');

        $this->Appointment->bindModel(array('belongsTo' => array('Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'email','phone')), 'User' => array('foreignKey' => 'customer_id', 'fields' => array('name', 'email','phone')), 'Slot')), false);
        $appointments = $this->Appointment->find('first', array('conditions' => array('Appointment.id' => $id)));


        if ($this->Appointment->delete()) {
            $srv='';
            $this->loadModel('AppointmentServices');
            $this->loadModel('Service');
            $service_ids=$this->AppointmentServices->find('list',array('conditions'=>array('AppointmentServices.appointment_id'=>$id),'fields'=>array('AppointmentServices.id','AppointmentServices.service_id')));
            $services = $this->Service->find('all', array('conditions' => array('Service.id' => $service_ids)));  
            foreach ($services as $service) {
              $srv.='<tr><td height="15" bgcolor="#fafafa"></td>
                                    </tr>
                                    <tr>
                                      <td height="3" bgcolor="#ffffff"></td>
                                    </tr>
                                    <tr>
                                      <td height="15" bgcolor="#fafafa"></td>
                                    </tr>
                                    <tr>
                                      <td class="em_pad" valign="top" bgcolor="#fafafa" align="center">
                                         
                                     <table class="full" width="500" cellspacing="0" cellpadding="0" border="0" align="center">
                                    <tbody>
                                    <tr>
                                      <td style="font-size:15px; color:#4FA062; font-family:Lato,Arial, sans-serif; font-weight:700;" width="63%" valign="middle" align="left">
                                         Service Name<br>
                                    <span style="font-size:15px; color:#4FA062; font-family:Lato,Arial, sans-serif; font-weight:700;">Service Charge</span>
                                        
                                         
                                      </td>
                                      <td style="font-size:13px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:400;" width="25%" align="left">'.$service['Service']['name'].'<br>
                                    <span style="font-size:15px; color:#aaaaaa; font-family:Lato,Arial, sans-serif; font-weight:700;">$'.$service['Service']['cost'].'</span></td>
                                    
                                    </tr>
                                    </tbody>
                                    </table>                                         
                                      </td>
                                    </tr>';  
            }
            
           
            if (!empty($appointments) && $this->Auth->user('role_id') == 3) {
                $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'AppointmentCancel')));
                $mail['SystemMail']['message'] = str_replace('[first_name]', $appointments['User']['name'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[customer_phone]', $appointments['User']['phone'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[BarberName]', $appointments['Barber']['name'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[BARBERPHONE]',$appointments['Barber']['phone'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[Date]', date("m/d/Y", strtotime($appointments['Appointment']['date'])), $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[Time]', $appointments['Slot']['time'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[services]', $srv, $mail['SystemMail']['message']);
                $to = $appointments['User']['email'];
                $this->Email->to = $to;
                $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
                $this->Email->subject = $mail['SystemMail']['subject'];
                $this->Email->sendAs = 'html';
                $this->Email->template = 'default';
                $this->set('message', $mail['SystemMail']['message']);
                $this->set('title', $mail['SystemMail']['subject']);
                if ($this->Email->send()) {
                    
                }
            }

            if ($this->Auth->user('role_id') == 4) {

                if (!empty($appointments)) {
                    $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'userAppointmentCancel')));
                    $mail['SystemMail']['message'] = str_replace('[first_name]', $appointments['User']['name'], $mail['SystemMail']['message']);
                    $mail['SystemMail']['message'] = str_replace('[customer_phone]', $appointments['User']['phone'], $mail['SystemMail']['message']);
                    $mail['SystemMail']['message'] = str_replace('[BarberName]', $appointments['Barber']['name'], $mail['SystemMail']['message']);
                    $mail['SystemMail']['message'] = str_replace('[BARBERPHONE]',$appointments['Barber']['phone'], $mail['SystemMail']['message']);
                    $mail['SystemMail']['message'] = str_replace('[Date]', date("m/d/Y", strtotime($appointments['Appointment']['date'])), $mail['SystemMail']['message']);
                    $mail['SystemMail']['message'] = str_replace('[Time]', $appointments['Slot']['time'], $mail['SystemMail']['message']);
                    $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                    $mail['SystemMail']['message'] = str_replace('[services]', $srv, $mail['SystemMail']['message']);
                    $to = $appointments['User']['email'];
                    $this->Email->to = $to;
                    $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
                    $this->Email->subject = $mail['SystemMail']['subject'];
                    $this->Email->sendAs = 'html';
                    $this->Email->template = 'default';
                    $this->set('message', $mail['SystemMail']['message']);
                    $this->set('title', $mail['SystemMail']['subject']);
                    if ($this->Email->send()) {
                        
                    }

                    $bar_mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'barberAppointmentCancel')));
                    $bar_mail['SystemMail']['message'] = str_replace('[first_name]', $appointments['User']['name'], $bar_mail['SystemMail']['message']);
                    $bar_mail['SystemMail']['message'] = str_replace('[customer_phone]', $appointments['User']['phone'], $bar_mail['SystemMail']['message']);
                    $bar_mail['SystemMail']['message'] = str_replace('[BarberName]', $appointments['Barber']['name'], $bar_mail['SystemMail']['message']);
                    $bar_mail['SystemMail']['message'] = str_replace('[BARBERPHONE]',$appointments['Barber']['phone'], $bar_mail['SystemMail']['message']);
                    $bar_mail['SystemMail']['message'] = str_replace('[Date]', date("m/d/Y", strtotime($appointments['Appointment']['date'])), $bar_mail['SystemMail']['message']);
                    $bar_mail['SystemMail']['message'] = str_replace('[Time]', $appointments['Slot']['time'], $bar_mail['SystemMail']['message']);
                    $bar_mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $bar_mail['SystemMail']['message']);
                    $bar_mail['SystemMail']['message'] = str_replace('[services]', $srv, $bar_mail['SystemMail']['message']);
                    $to = $appointments['Barber']['email'];
                    $this->Email->to = $to;
                    $this->Email->from = $bar_mail['SystemMail']['senderName'] . '<' . $bar_mail['SystemMail']['senderEmail'] . '>';
                    $this->Email->subject = $bar_mail['SystemMail']['subject'];
                    $this->Email->sendAs = 'html';
                    $this->Email->template = 'default';
                    $this->set('message', $bar_mail['SystemMail']['message']);
                    $this->set('title', $bar_mail['SystemMail']['subject']);
                    if ($this->Email->send()) {
                        
                    }
                }
            }


            $this->Session->setFlash('The Appointment has been deleted.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The Appointment could not be deleted. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(Router::url($this->referer(), true));
    }

    public function cancel_appointment_barber($id) {

        $this->Appointment->id = $id;
        $appointments = array();
        if (!$this->Appointment->exists()) {
            $this->Session->setFlash('Invalid reservations.', 'default', array('class' => 'error'));
            return $this->redirect(Router::url($this->referer(), true));
        }
        $this->request->allowMethod('post', 'get');

        if ($this->Appointment->delete()) {


            $this->Session->setFlash('The reservation has been deleted.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The reservation could not be deleted. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(Router::url($this->referer(), true));
    }

    function barbers($selectDate = null) {

        if ($this->Session->read('Auth.User.role_id') == 4 && $this->Session->read('Auth.User.barber') == '') {
            $this->Auth->logout();
            $this->Session->setFlash(__('Invalid barber shop. Please try with barber shop login url.', true), 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'users', 'action' => 'login'));
        }
        $flag = 0;
        // ----------- check date select or not------------
        if (isset($selectDate) && $selectDate != null) {
            // ----------- check date  format ------------
            $dateInput = explode('-', $selectDate);
            if (count($dateInput) == 3) {
                if (checkdate($dateInput[1], $dateInput[2], $dateInput[0]) == TRUE) {
                    $flag = 1;
                }
            }
        }
        //-----------flag "0" current date "1" for selected date
        if ($flag == 1) {
            $timestamp = strtotime($selectDate);

            $currentDay = date('l', $timestamp);
            $currentDate = $selectDate;
        } else {
            $currentDay = date('l');
            $currentDate = date('Y-m-d');
        }


        $currentDayData = $this->Week->findByName($currentDay);
        $week_id = $currentDayData['Week']['id'];
        $barber = $this->Session->read('Auth.User.barber');
//        echo $barber ; //die;
        $barberAdmin = $this->User->findByShopSlugAndStatus($barber, 1);
        if (!empty($barberAdmin)) {

            /* list of users , on leave today */
            $vacation_user = $this->Vacation->find('list', array('conditions' => array('Vacation.from_date <=' => $currentDate . ' 00:00:00', 'Vacation.to_date >=' => $currentDate . ' 23:55:00'), 'fields' => array('id', 'user_id')));

            // $vacation_user = $this->Vacation->find('list', array('conditions' => array('Vacation.date' => $currentDate), 'fields' => array('id', 'user_id')));
//            pr($vacation_user); die;
            $this->Schedule->bindModel(array('belongsTo' => array('User' => array('fields' => array('id', 'name', 'image', 'parent_id')))));

            $barber_users = $this->Schedule->find('all', array('conditions' => array('User.parent_id' => $barberAdmin['User']['id'], 'User.status' => 1, 'User.deleted' => 0, 'Schedule.week_id' => $week_id, 'Schedule.status' => 1, 'Schedule.working' => 1, 'NOT' => array('Schedule.user_id' => $vacation_user))));

            if (empty($barber_users)) {
                $this->Session->setFlash(__('Today barbers not working. Please choose another date.', true), 'default', array('class' => 'error'));
                //$this->redirect(array('controller' => 'barbers', 'action' => 'calendar'));
            }

            $this->set('barbers', $barber_users);
            $this->set('currentDate', $currentDate);
        } else {
            $this->Session->setFlash(__('The barber shop not active yet. Please try with another shop url.', true), 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'users', 'action' => 'my_account'));
        }
    }

    public function admin_vacations() {
        $this->User->recursive = 0;
        $this->set('title_for_layout', 'Barber\'s Vacation Manager');
        $this->set('total_users', $this->User->find('count', array('conditions' => array('User.role_id' => 3, 'User.parent_id' => $this->Auth->user('id')))));
    }

    public function admin_vacations_list() {
        $this->layout = false;

        $name_con = $vacation_from_con = $vacation_to_con = array();

        //echo date("Y-m-d H:i:s",strtotime($this->request->data['vacation_from']));


        if (isset($this->request->data['name']) && $this->request->data['name'] != '') {
            $name_con = array('User.name LIKE' => '%' . $this->request->data['name'] . '%');
        }
        if (isset($this->request->data['vacation_from']) && $this->request->data['vacation_from'] != '') {
            $vacation_from_con = array('Vacation.from_date >=' => date("Y-m-d H:i:s", strtotime($this->request->data['vacation_from'])));
        }
        if (isset($this->request->data['vacation_to']) && $this->request->data['vacation_to'] != '') {
            $vacation_to_con = array('Vacation.to_date <=' => date("Y-m-d H:i:s", strtotime($this->request->data['vacation_to'])));
        }


        switch ($this->request->data['order'][0]['column']) {
            case 1:
                $order = array('User.name' => $this->request->data['order'][0]['dir']);
                break;
            case 2:
                $order = array('Vacation.from_date' => $this->request->data['order'][0]['dir']);
                break;
            case 3:
                $order = array('Vacation.to_date' => $this->request->data['order'][0]['dir']);
                break;
            case 4:
                $order = array('Vacation.from_date' => $this->request->data['order'][0]['dir']);
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

        $con = array_merge($name_con, $vacation_from_con, $vacation_to_con, array('User.role_id' => 3, 'User.parent_id' => $this->Auth->user('id')));

        //pr($con);die;
        //$this->User->unbindModel(array('belongsTo' => array('User')), false);
        //$this->Vacation->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
        $this->Vacation->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))), false);


        $this->paginate = array('conditions' => $con, 'limit' => $this->request->data['length'], 'order' => $order, 'page' => $page);
        $this->Vacation->recursive = 3;

        //pr($this->paginate('Vacation'));die;
        $this->set('users', $this->paginate('Vacation'));
    }

    public function service($id = null) {
        $this->loadModel('BarberService');
        $this->User->bindModel(array('belongsTo' => array('Parent' => array('foreignKey' => 'parent_id', 'className' => 'User', 'fields' => array('shop_slug')))));
        $this->User->recursive = 0;
        $barber = $this->User->find('first', array('conditions' => array('User.id' => $id, 'Parent.shop_slug' => $this->Auth->user('barber')), 'fields' => array('User.id')));
        if (empty($barber)) {
            $this->Session->setFlash(__('Invalid barber.', true), 'default', array('class' => 'error'));
            $this->redirect(array('controller' => 'users', 'action' => 'my_account'));
        }

        if ($this->request->is('post')) {
            $date = $this->request->data['date'];
            $timestamp = strtotime($date);
            $currentDay = date('l', $timestamp);
            $currentDayData = $this->Week->findByName($currentDay);
            $schedule = $this->Schedule->find('first', array('conditions' => array('Schedule.user_id' => $id,'Schedule.week_id' => $currentDayData['Week']['id'], 'Schedule.status' => 1)));
            if(!empty($schedule)){
                $data['t'] = $this->request->data['time'];
            $data['p'] = $this->request->data['total'];
            $data['id'] = implode(',', $this->request->data['service_id']);
            $data = $this->Common->createQueryString($data);
            $queryData = $this->Common->encryptData($data);
            $this->redirect(array('action' => 'calendar', $id,$schedule['Schedule']['id'],$date,'?' => array('q' => $queryData))); 
            }else{
                $data['t'] = $this->request->data['time'];
                $data['p'] = $this->request->data['total'];
                $data['id'] = implode(',', $this->request->data['service_id']);
                $data = $this->Common->createQueryString($data);
                $queryData = $this->Common->encryptData($data);
                $this->redirect(array('action' => 'calendar', $id, 0, $date, '?' => array('q' => $queryData)));
            }
           
        }

        $service = $this->BarberService->find('all', array('conditions' => array('Service.status' => 1, 'BarberService.user_id' => $barber['User']['id']), 'fields' => array('Service.*', 'User.image')));
        //pr($service);die;
        $this->set('services', $service);
    }
    public function my_service() {
        $this->loadModel('BarberService');
        if ($this->request->is('post')) {
            $data['t'] = $this->request->data['time'];
            $data['p'] = $this->request->data['total'];
            $data['id'] = implode(',', $this->request->data['service_id']);
            $data = $this->Common->createQueryString($data);
            $queryData = $this->Common->encryptData($data);
            $this->redirect(array('action' => 'my_calendar', $id, '?' => array('q' => $queryData)));
        }

        $service = $this->BarberService->find('all', array('conditions' => array('Service.status' => 1, 'BarberService.user_id' => $this->Auth->user('id')), 'fields' => array('Service.*', 'User.image')));
        //pr($service);die;
        $this->set('services', $service);
    }

    function admin_services($id = null) {
        $this->layout = false;
        $this->loadModel('Service');
        $this->loadModel('BarberService');
        if ($this->request->is('post')) {
            $this->BarberService->create();
            $data = array();
            $serviceArr=array();
            if (isset($this->request->data['BarberService']['service_id']) && !empty($this->request->data['BarberService']['service_id'])) {
                $i = $time = 0;
                foreach ($this->request->data['BarberService']['service_id'] as $service_id) {
                    $serArr = explode('-', $service_id);
                    $data[$i]['BarberService']['service_id'] = $serArr[0];
                    $data[$i]['BarberService']['user_id'] = $id;
                    array_push($serviceArr,$serArr[1]);                    
                    $i++;
                }
            }
            $this->BarberService->deleteAll(array('BarberService.user_id' => $id));
            if ($this->BarberService->saveMany($data)) {                
                $arr1 = array(30);
                $arr2 = array(60);
                $diff = array_unique(array_diff($serviceArr, $arr1, $arr2));
                if (empty($diff)) {
                    $time = 30;
                } else {
                    $time = 15;
                }
                if ($this->User->UpdateAll(array('User.service_time' => $time), array('User.id' => $id))) {
                    $schedules = $this->Schedule->findAllByUserIdAndStatusAndWorking($id, 1, 1);
                    if (!empty($schedules)) {
                        foreach ($schedules as $schedule) {
                            if ($schedule['Schedule']['slot_time'] != $time) {
                                $slots = $this->getServiceScheduleSlots($time, 0, $schedule['Schedule']['start_time'], $schedule['Schedule']['end_time']);
                                if (!empty($slots)) {
                                    $oldSchedule = $schedule['Schedule']['id'];
                                    unset($schedule['Schedule']['id']);
                                    unset($schedule['Schedule']['created']);
                                    $schedule['Schedule']['slot_time'] = $time;
                                    $this->Schedule->create();
                                    if ($newSchedule = $this->Schedule->save($schedule)) {
                                        foreach ($slots as $key => $slot) {
                                            $slotsData['Slot']['schedule_id'] = $newSchedule['Schedule']['id'];
                                            $slotsData['Slot']['time'] = $slot;
                                            $slotsData['Slot']['time_24'] = $key;
                                            $this->Slot->create();
                                            $this->Slot->save($slotsData);
                                        }
                                        $this->Schedule->updateAll(array('Schedule.status' => 0), array('Schedule.id' => $oldSchedule));
                                    }
                                }
                            }
                        }
                    }
                    $this->Session->setFlash(__('The barber service has been saved.'), 'default', array('class' => 'success'));
                    $response = array('error' => 0);
                } else {
                    $response = array('error' => 1, 'msg' => '<div class="alert alert-danger fade in"><button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button><i class="fa-lg fa fa-warning"></i>Barber service could not be saved. Please try again.</div>');
                }
            } else {
                $response = array('error' => 1, 'msg' => '<div class="alert alert-danger fade in"><button aria-hidden="true" data-dismiss="alert" class="close" type="button"></button><i class="fa-lg fa fa-warning"></i>Barber service could not be saved. Please try again.</div>');
            }
            echo json_encode($response);
            exit;
        }
        $barber_services = $this->BarberService->find('all', array('conditions' => array('BarberService.user_id' => $id), 'fields' => array('BarberService.service_id', 'Service.*')));
        $barber_service = array();
        foreach ($barber_services as $ser) {
            $barber_service[] = $ser['Service']['id'] . '-' . $ser['Service']['time'];
        }
        $this->request->data['BarberService']['service_id'] = $barber_service;
        $user= $this->User->find('first',array('conditions'=>array('User.id'=>$id),'fields'=>array('User.parent_id')));
        $service = $this->Service->find('list', array('conditions' => array('Service.user_id'=>$user['User']['parent_id'],'Service.status' => 1), 'order' => array('Service.name' => 'asc'), 'fields' => array('Service.service_id_time', 'Service.service_name_time')));
        $this->set('services', $service);
    }

}
