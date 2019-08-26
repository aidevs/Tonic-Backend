<?php

class User extends AppModel {

    // User Roles 1 -> Admin 1 -> Site User
    private $_id;
    public $name = 'User';
    public $belongsTo = array('Role', 'Country');
    public $validate = array(
        'name' => array(
            'name' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter first name.',
            ),
        ),
        'email' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter email address.',
            ),
            'email' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address.',
            ),
            'isUnique' => array(
                'rule' => 'isUnique',
                'message' => 'Email address already in use.'
            ),
        ),
        'password' => array(
            'rule' => 'notBlank', //array('minLength', 6)
            'on' => 'create',
            'message' => 'Passwords must be at least 6 characters long.',
        ),
        'confirm_password' => array(
            'rule' => 'identical',
        ),
    );

    public function beforeSave($options = array()) {
        if (isset($this->data['User']['password']) && !empty($this->data['User']['password'])) {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
        }
        if ((isset($this->data['User']['first_name']) && !empty($this->data['User']['first_name']))) {
            $last_name=(isset($this->data['User']['last_name']) && !empty($this->data['User']['last_name']))? ' ' . $this->data['User']['last_name']:'';
            $this->data['User']['name'] = $this->data['User']['first_name'] .$last_name;
        }
        if (isset($this->data['User']['shop_name']) && !empty($this->data['User']['shop_name'])) {
            $this->data['User']['shop_slug'] = str_replace(' ', '', trim(strtolower($this->data['User']['shop_name'])));
        }
        return true;
    }

    function beforeDelete($cascade = false) {
        $this->_id = $this->read(null, $this->id);
        return true;
    }

    function afterDelete($cascade = false) {
        parent::afterDelete();
        if ($this->_id['User']['role_id'] == 3) {
            $this->Schedule = ClassRegistry::init("Schedule");
            $this->Slot = ClassRegistry::init("Slot");
            
            $this->Vacation = ClassRegistry::init("Vacation");
            $this->BarberService = ClassRegistry::init("BarberService");
            $this->LunchBreak = ClassRegistry::init("LunchBreak");
            $this->ReserveSlot = ClassRegistry::init("ReserveSlot");
            $this->AppointmentService = ClassRegistry::init("AppointmentService");
            $this->AppointmentSlot = ClassRegistry::init("AppointmentSlot");
            $this->Walkin = ClassRegistry::init("Walkin");
            $this->Appointment = ClassRegistry::init("Appointment");
            $this->WalkinAppointment = ClassRegistry::init("WalkinAppointment");
            $this->WalkinAppointmentBarber = ClassRegistry::init("WalkinAppointmentBarber");
            
            $appointments = $this->Appointment->find('list', array('conditions' => array('Appointment.barber_id' => $this->_id['User']['id']), 'fields' => array('Appointment.id')));
            $schedule = $this->Schedule->find('list', array('conditions' => array('Schedule.user_id' => $this->_id['User']['id']), 'fields' => array('Schedule.id')));
            $this->Slot->deleteAll(array('Slot.schedule_id' => $schedule));
            $this->Schedule->deleteAll(array('Schedule.user_id' => $this->_id['User']['id']));
            
            $this->Vacation->deleteAll(array('Vacation.user_id' => $this->_id['User']['id']));
            $this->BarberService->deleteAll(array('BarberService.user_id' => $this->_id['User']['id']));
            $this->LunchBreak->deleteAll(array('LunchBreak.user_id' => $this->_id['User']['id']));
            $this->ReserveSlot->deleteAll(array('ReserveSlot.user_id' => $this->_id['User']['id']));
            
            $this->AppointmentService->deleteAll(array('AppointmentService.appointment_id' => $appointments));
            $this->AppointmentSlot->deleteAll(array('AppointmentSlot.appointment_id' => $appointments));
            
            $this->Walkin->deleteAll(array('Walkin.barber_id' => $this->_id['User']['id']));
            $this->WalkinAppointmentBarber->deleteAll(array('WalkinAppointmentBarber.barber_id' => $this->_id['User']['id']));
            
            $this->Appointment->deleteAll(array('Appointment.id' => $appointments));
        }
        if ($this->_id['User']['role_id'] == 2) {
            $this->Schedule = ClassRegistry::init("Schedule");
            $this->Slot = ClassRegistry::init("Slot");
            $this->Walkin = ClassRegistry::init("Walkin");
            $this->Appointment = ClassRegistry::init("Appointment");
            $this->WalkinAppointment = ClassRegistry::init("WalkinAppointment");
            $this->WalkinAppointmentBarber = ClassRegistry::init("WalkinAppointmentBarber");
            $this->User = ClassRegistry::init("User");

            $barbers = $this->User->find('list', array('conditions' => array('User.parent_id' => $this->_id['User']['id']), 'fields' => array('User.id')));
            $schedule = $this->Schedule->find('list', array('conditions' => array('Schedule.user_id' => $barbers), 'fields' => array('Schedule.id')));
            $this->Slot->deleteAll(array('Slot.schedule_id' => $schedule));
            $this->Schedule->deleteAll(array('Schedule.user_id' => $barbers));
            $wakins = $this->WalkinAppointmentBarber->find('list', array('conditions' => array('WalkinAppointmentBarber.barber_id' => $barbers), 'fields' => array('WalkinAppointmentBarber.walkin_appointment_id')));
            $this->Walkin->deleteAll(array('Walkin.barber_id' => $barbers));
            $this->Appointment->deleteAll(array('Appointment.barber_id' => $barbers));
            $this->WalkinAppointment->deleteAll(array('WalkinAppointment.id' => $wakins));
            $this->WalkinAppointmentBarber->deleteAll(array('WalkinAppointmentBarber.barber_id' => $barbers));
            $this->User->deleteAll(array('User.id' => $barbers));
        }
        return TRUE;
    }

    public function identical($check) {
        if (isset($this->data['User']['password'])) {
            //pr($this->request->data);die;
            if ($this->data['User']['password'] == $check['confirm_password']) {
                return true;
            } else {
                return __('Confirm password did not match. Please, try again.');
            }
        }
        return true;
    }

    public function getPin($user_id) {
        $pins = $this->find('list', array('conditions' => array('parent_id' => $user_id), 'order' => array('pin' => 'desc'), 'fields' => array('pin')));
        $generated = array();
        for ($i = 0; $i <= 99; $i++) {
            $generated[] = (strlen($i) < 2) ? $i + 10 : $i;
        }
        $generated = array_diff($generated, $pins);
        shuffle($generated);
        $position = array_rand($generated, 1);
        return $position;
    }

    public function getPinList($user_id, $id = null) { 
        $pins=array();
        if ($id == null ) {
            $pins = $this->find('list', array('conditions' => array('parent_id' => $user_id), 'order' => array('pin' => 'desc'), 'fields' => array('pin')));
        } else {
             
            $pins = $this->find('list', array('conditions' => array('parent_id' => $user_id, 'id != ' => '84'), 'order' => array('pin' => 'desc'), 'fields' => array('pin')));
			}
//pr($pins); die;
        $generated = array();
        for ($i = 0; $i <= 99; $i++) {
            $generated[(strlen($i) < 2) ? '0'.$i: $i] = (strlen($i) < 2) ? '0'.$i: $i;
        }
        
		$generated = array_diff($generated, $pins);
		
        return $generated;
    }

}
