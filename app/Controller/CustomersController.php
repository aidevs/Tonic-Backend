<?php

App::uses('AppController', 'Controller');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CustomersController
 *
 * @author kipl67
 */
class CustomersController extends AppController {

    /**
     * Controller name
     *
     * @var string
     */
    public $name = 'Customers';

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('User', 'Schedule', 'Slot', 'Appointment', 'Walkin','WalkinAppointments','WalkinAppointmentBarbers');
    var $helper = array('Common');
    var $components = array('Common', 'Email','Export.Export');

    function beforeFilter() {
        parent::beforeFilter();
        if ($this->Auth->user('role_id') == 1) {
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
    }

    function admin_reservations() {

        $this->set('title_for_layout', 'Reservations Customers');
        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'barber_id'))));
        $this->set('total_appointments', $this->Appointment->find('count', array('conditions' => array('User.parent_id' => $this->Auth->user('id')))));
    }

    function admin_list() {
        $this->layout = false;
        $con = $barber_name_con = $created_con = $slot_time_con = $name_con = $status_con = array();
        if (isset($this->request->data['name']) && $this->request->data['name'] != '') {
            $name_con = array('User.name LIKE' => '%' . $this->request->data['name'] . '%');
        }
        if (isset($this->request->data['barber_name']) && $this->request->data['barber_name'] != '') {
            $barber_name_con = array('Barber.name LIKE' => '%' . $this->request->data['barber_name'] . '%');
        }
        if (isset($this->request->data['email']) && $this->request->data['email'] != '') {
            $name_con = array('User.email LIKE' => '%' . $this->request->data['email'] . '%');
        }
        if (isset($this->request->data['phone']) && $this->request->data['phone'] != '') {
            $name_con = array('User.phone LIKE' => '%' . $this->request->data['phone'] . '%');
        }
        if (isset($this->request->data['slot_time']) && $this->request->data['slot_time'] != '') {
            $slot_time_con = array('Slot.time LIKE' => '%' . $this->request->data['slot_time'] . '%');
        }
        if (isset($this->request->data['created']) && $this->request->data['created'] != '') {
            $created_con = array('Appointment.date LIKE' => '%' . date("Y-m-d",strtotime($this->request->data['created'])) . '%');
        }
        if (isset($this->request->data['status']) && $this->request->data['status'] != '') {
            $status_con = array('Appointment.status' => $this->request->data['status']);
        }

        switch ($this->request->data['order'][0]['column']) {
            case 0:
                $order = array('User.name' => $this->request->data['order'][0]['dir']);
                break;
            case 1:
                $order = array('Barber.name' => $this->request->data['order'][0]['dir']);
                break;
             case 2:
                $order = array('User.email' => $this->request->data['order'][0]['dir']);
                break;
               case 3:
                $order = array('User.phone' => $this->request->data['order'][0]['dir']);
                break;
            case 4:
                $order = array('Slot.time' => $this->request->data['order'][0]['dir']);
                break;
               case 5:
                $order = array('Appointment.date' => $this->request->data['order'][0]['dir']);
                break;
            case 6:
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

        $con = array_merge($barber_name_con, $created_con, $slot_time_con, $name_con, $status_con, array('Barber.parent_id' => $this->Auth->user('id')));
        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'fields' => array('name', 'id', 'email', 'phone','dob')), 'Barber' => array('className' => 'User', 'foreignKey' => 'barber_id', 'fields' => array('name', 'id')), 'Slot')), false);
        $this->paginate = array('conditions' => $con, 'limit' => $this->request->data['length'], 'order' => $order, 'page' => $page);
        $this->set('users', $this->paginate('Appointment'));
            
    }
    public function admin_export_reservations() {
         ini_set("memory_limit", "-1");
         set_time_limit(0);
         $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'fields' => array('name', 'id', 'email', 'phone','dob')), 'Barber' => array('className' => 'User', 'foreignKey' => 'barber_id', 'fields' => array('name', 'id')), 'Slot')), false);
        $appointments=$this->Appointment->find('all', array('conditions' => array('Barber.parent_id' => $this->Auth->user('id'))));
        //pr($appointments);die;
        $data=array();
        foreach ($appointments as $appointment) {
          $status=$appointment['Appointment']['status'];  
          $label_text = ($status == 1) ? 'Seat': (($status==2)?'Dismiss':'Pending');
          $data[]=array(
              'Customer'=>$appointment['User']['name'],                                         
              'Barber'=>$appointment['Barber']['name'],                                         
              'Customer Email'=>$appointment['User']['email'],                                         
              'Customer Phone'=>$appointment['User']['phone'],                                         
              'Time'=>$appointment['Slot']['time'],                                         
              'Date'=>date('m/d/Y', strtotime($appointment['Appointment']['date'])),   
              'Status'=>$label_text, 
          );
        }
        $this->Export->exportCsv($data, 'reservations.csv');
    }  
    public function admin_export_walkins() {
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $con = array('User.parent_id' => $this->Auth->user('id'));
        $walkins= $this->Walkin->find('all', array('conditions' => $con, 'order' =>array('date'=>'desc'),'fields'=>array('User.name','User.parent_id','Walkin.*')));
      
        $data = array();
        foreach ($walkins as $appointment) {
            $status = $appointment['Walkin']['status'];
            $label_text = ($status == 1) ? 'Seat' : 'Dismiss';
            $data[] = array(
                'Rejected or Accepted by' => $appointment['User']['name'],
                'Customer Name' => $appointment['Walkin']['name'],
                'Date' => date('m/d/Y', strtotime($appointment['Walkin']['date'])),
                'Status' => $label_text,
            );
        }
        $this->Export->exportCsv($data, 'walkins.csv');
    }
    public function admin_walkin() {
        $this->set('title_for_layout', 'Walk-In Customers');
        $this->set('total_walkins', $this->Walkin->find('count', array('conditions' => array('User.parent_id' => $this->Auth->user('id')))));
    }

    function admin_walkin_list() {
        $this->layout = false;
        $con = $barber_name_con = $created_con = $slot_time_con = $name_con = $status_con = array();
        if (isset($this->request->data['name']) && $this->request->data['name'] != '') {
            $name_con = array('Walkin.name LIKE' => '%' . $this->request->data['name'] . '%');
        }
        if (isset($this->request->data['slot_time']) && $this->request->data['slot_time'] != '') {
            $slot_time_con = array('Walkin.time LIKE' => '%' . $this->request->data['slot_time'] . '%');
        }
        if (isset($this->request->data['created']) && $this->request->data['created'] != '') {
            //$created_con = array('Walkin.date LIKE' => '%' . $this->request->data['created'] . '%');
            $created_con = array('Walkin.date LIKE' => '%' . date("Y-m-d",strtotime($this->request->data['created'])) . '%');
        }
        if (isset($this->request->data['barber_name']) && $this->request->data['barber_name'] != '') {
            $barber_name_con = array('User.name LIKE' => '%' . $this->request->data['barber_name'] . '%');
        }
        if (isset($this->request->data['status']) && $this->request->data['status'] != '') {
            $status_con = array('Walkin.status' =>$this->request->data['status']);
        }


        switch ($this->request->data['order'][0]['column']) {
            case 0:
                $order = array('Walkin.name' => $this->request->data['order'][0]['dir']);
                break;
            case 1:
                $order = array('User.name' => $this->request->data['order'][0]['dir']);
                break;
            case 2:
                $order = array('Walkin.time' => $this->request->data['order'][0]['dir']);
                break;
            case 3:
                $order = array('Walkin.date' => $this->request->data['order'][0]['dir']);
                break;
            case 4:
                $order = array('Walkin.status' => $this->request->data['order'][0]['dir']);
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

        $con = array_merge($barber_name_con, $created_con, $slot_time_con, $name_con, $status_con, array('User.parent_id' => $this->Auth->user('id')));
        $this->paginate = array('conditions' => $con, 'limit' => $this->request->data['length'],'order' => $order, 'page' => $page);
        $this->set('users', $this->paginate('Walkin'));
    }

    public function admin_walkin_delete($id = null) {
        $this->Walkin->id = $id;
        if (!$this->Walkin->exists()) {
            $this->Session->setFlash('Invalid Walk-In customer.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'walkin'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Walkin->delete()) {
            $this->Session->setFlash('The Walk-In customer has been deleted.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The Walk-In customer could not be deleted. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(array('action' => 'walkin'));
    }
    public function admin_pending_walkin() {
        $this->set('title_for_layout', 'Pending Walk-In Customers');
        $this->set('total_walkins', $this->WalkinAppointments->find('count', array('conditions' => array('shop_id' => $this->Auth->user('id')))));
    }
    function admin_pending_walkin_list() {
        $this->layout = false;
        $con = $created_con =  $name_con = array();
        if (isset($this->request->data['name']) && $this->request->data['name'] != '') {
            $name_con = array('WalkinAppointments.name LIKE' => '%' . $this->request->data['name'] . '%');
        }
        if (isset($this->request->data['status']) && $this->request->data['status'] != '') {
            $status = strtolower($this->request->data['status']) == 'pending' ? '1' :"0";
            $name_con = array('WalkinAppointments.status LIKE' => '%' . $status . '%');
        }
        
        if (isset($this->request->data['created']) && $this->request->data['created'] != '') {
            $created_con = array('WalkinAppointments.created LIKE' => '%' . date('Y-m-d',  strtotime($this->request->data['created'])) . '%');
        }


        switch ($this->request->data['order'][0]['column']) {
            case 0:
                $order = array('WalkinAppointments.name' => $this->request->data['order'][0]['dir']);
                break;
            case 1:
                $order = array('WalkinAppointments.created' => $this->request->data['order'][0]['dir']);
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

        $con = array_merge( $created_con, $name_con, array('shop_id' => $this->Auth->user('id')));
        $this->paginate = array('conditions' => $con, 'limit' => $this->request->data['length'], 'order' => $order, 'page' => $page);
        $this->set('users', $this->paginate('WalkinAppointments'));
    }
    public function admin_pending_walkin_delete($id = null) {
        $this->WalkinAppointments->id = $id;
        if (!$this->WalkinAppointments->exists()) {
            $this->Session->setFlash('Invalid Pending Walk-In.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'pending_walkin'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->WalkinAppointments->delete()) {
            $this->Session->setFlash('The Pending Walk-In has been deleted.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The Pending Walk-In could not be deleted. Please, try again.', 'default', array('class' => 'error'));
        }
        return $this->redirect(array('action' => 'walkin'));
    }
    public function admin_pending_walkin_dismiss($id = null,$status=0) {
        $this->WalkinAppointments->id = $id;
        if (!$this->WalkinAppointments->exists()) {
            $this->Session->setFlash('Invalid Pending Walk-In.', 'default', array('class' => 'error'));
            return $this->redirect(array('action' => 'pending_walkin'));
        }
        $this->request->allowMethod('post', 'delete');
        
        $walkins['WalkinAppointments']['id'] = $id;
        $walkins['WalkinAppointments']['status'] = $status;
        if ($this->WalkinAppointments->save($walkins)) {
            $msg = $status == '0' ? ' dismissed' : " set to Pending";
            $this->Session->setFlash('The Walk-in has been '.$msg, 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The Walk-in could not be '.$msg, 'default', array('class' => 'error'));
        }
        return $this->redirect(array('action' => 'pending_walkin'));
    }
    public function admin_seat_walkin($walkinid) {
        $this->layout = false;
        $barbers = $this->User->find('list', array('conditions' => array('User.role_id' => 3, 'User.parent_id' => $this->Auth->user('id'))));
        $walkins = $this->WalkinAppointmentBarbers->find('all', array('contain' => array('User'),'conditions' => array('walkin_appointment_id' => $walkinid),'fields' => array('WalkinAppointmentBarbers.barber_id','User.first_name','User.last_name','User.id', 'WalkinAppointments.id','WalkinAppointments.name')));
        $this->set(compact('walkins', 'walkins'));
        $this->set(compact('barbers', 'barbers'));
        $this->render('/Customers/admin_seat_walkin');
    }
    public function admin_add_walkin(){
        if ($this->request->is('post')) {
            $wakin['Walkin']['barber_id']=$this->request->data['barber_id'];
            $wakin['Walkin']['name']=$this->request->data['name'];
            $wakin['Walkin']['time']=date('h:i A');
            $wakin['Walkin']['date']=date('Y-m-d');
            $this->Walkin->save($wakin);
            $this->WalkinAppointmentBarbers->deleteAll(array('WalkinAppointmentBarbers.walkin_appointment_id'=>$this->request->data['walkin_id']));
            $this->WalkinAppointments->delete($this->request->data['walkin_id']);
            $this->Session->setFlash('Walk In has been added.', 'default', array('class' => 'success'));
            return $this->redirect(array('controller' => 'customers', 'action' => 'pending_walkin'));
        } else {
            $this->redirect(array('controller' => 'customers', 'action' => 'pending_walkin'));
        }
    }
    public function pending_walkin_dismiss($id = null) {
        $this->WalkinAppointments->id = $id;
        if (!$this->WalkinAppointments->exists()) {
            $this->Session->setFlash('Invalid Walk-In.', 'default', array('class' => 'error'));
            return $this->redirect(Router::url($this->referer(), true));
        }
        $walkins['WalkinAppointments']['id'] = $id;
        $walkins['WalkinAppointments']['status'] = 0;
        if ($this->WalkinAppointments->save($walkins)) {
            $this->Session->setFlash('The Walk-in has been dismissed.', 'default', array('class' => 'success'));
        } else {
            $this->Session->setFlash('The Walk-in could not be dismissed.', 'default', array('class' => 'error'));
        }
        return $this->redirect(Router::url($this->referer(), true));
    }
    public function seat_walkin($id = null) {
        if($id != null){
            $walkin = $this->WalkinAppointments->find('all', array('conditions' => array('WalkinAppointments.id' => $id)));
            $wakin['Walkin']['barber_id']=$this->Auth->user('id');
            $wakin['Walkin']['name']=$walkin[0]['WalkinAppointments']['name'];
            $wakin['Walkin']['time']=date('h:i A');
            $wakin['Walkin']['date']=date('Y-m-d');
            $this->Walkin->save($wakin);
            $this->WalkinAppointmentBarbers->deleteAll(array('WalkinAppointmentBarbers.walkin_appointment_id'=>$id));
            $this->WalkinAppointments->delete($id);
            $this->Session->setFlash('Walk In has been added.', 'default', array('class' => 'success'));
        }
        return $this->redirect(Router::url($this->referer(), true));
    }

}
