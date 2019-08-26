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
    public $uses = array('User', 'Schedule', 'Slot', 'Appointment', 'Walkin');
    var $helper = array('Common');
    var $components = array('Common', 'Email');

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
                $order = array('User.dob' => $this->request->data['order'][0]['dir']);
                break;
            case 6:
                $order = array('Appointment.date' => $this->request->data['order'][0]['dir']);
                break;
            case 7:
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
            $created_con = array('Walkin.date LIKE' => '%' . $this->request->data['created'] . '%');
        }
        if (isset($this->request->data['barber_name']) && $this->request->data['barber_name'] != '') {
            $barber_name_con = array('User.name LIKE' => '%' . $this->request->data['barber_name'] . '%');
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
        $this->paginate = array('conditions' => $con, 'limit' => $this->request->data['length'], 'order' => $order, 'page' => $page);
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

}
