<?php
/**
 * SessionComponent. Provides access to Sessions from the Controller layer
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
 * @package       Cake.Controller.Component
 * @since         CakePHP(tm) v 0.10.0.1232
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Component', 'Controller');

/**
 * The CakePHP SessionComponent provides a way to persist client data between 
 * page requests. It acts as a wrapper for the `$_SESSION` as well as providing 
 * convenience methods for several `$_SESSION` related functions.
 *
 * @package       Cake.Controller.Component
 * @link http://book.cakephp.org/2.0/en/core-libraries/components/sessions.html
 * @link http://book.cakephp.org/2.0/en/development/sessions.html
 */
class CommonComponent extends Component {
    
    
    public function site_setting($id = null){
        
        //$Setting = &ClassRegistry::init("Setting");
        $site_setting = ClassRegistry::init("Setting")->find('first', array('conditions' => array('Setting.key' => $id)));
        return $site_setting['Setting']['value'];
        
    }
    function getUserImage($img=null,$w=100,$h=100,$crop=1,$type='front') {
       if($img!='' && file_exists(WWW_ROOT.'uploads'.DS.'users'.DS.$img)){ 
           $img=SITE_FULL_URL."thumbnail/thumbnail.php?file=../uploads/users/{$img}&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";
       }else{
           $img=SITE_FULL_URL."thumbnail/thumbnail.php?file=../img/no-user.png&w={$w}&h={$h}&el=0&gd=2&color=FFFFFF&crop={$crop}&tp=1";
           if($type=='admin'){
           $img=SITE_FULL_URL.'img/no-image.png';    
           }
       }
       return $img;
    }
    function encryptData($data, $secret = 'secret') {
//        $key = md5(utf8_encode($secret), true);
//        $key .= substr($key, 0, 8);
//        $blockSize = mcrypt_get_block_size('tripledes', 'ecb');
//        $len = strlen($data);
//        $pad = $blockSize - ($len % $blockSize);
//        $data .= str_repeat(chr($pad), $pad);
//        $enc = mcrypt_encrypt('tripledes', $key, $data, 'ecb');
        return base64_encode($data);
    }
    function decryptData($data, $secret = 'secret') {
//        $key = md5(utf8_encode($secret), true);
//        $key .= substr($key, 0, 8);        
//        $data = base64_decode($data);
//        $data= urldecode($data);
//        $data = mcrypt_decrypt('tripledes', $key, $data, 'ecb');
//        $block = mcrypt_get_block_size('tripledes', 'ecb');
//        $len = strlen($data);
//        $pad = ord($data[$len - 1]);
//        return substr($data, 0, strlen($data) - $pad);
        return base64_decode($data);
    }
    function createQueryString($params) {
        $paramsJoined = array();

        foreach ($params as $param => $value) {
            $paramsJoined[] = "$param=$value";
        }

        return $query = implode('&', $paramsJoined);
    }
    function check_barber_free($user_id,$week_id,$seldate) {
        $this->Schedule=ClassRegistry::init("Schedule");
        $this->LunchBreak=ClassRegistry::init("LunchBreak");
        $this->Appointment=ClassRegistry::init("Appointment");
        $this->ReserveSlot=ClassRegistry::init("ReserveSlot");
        $this->AppointmentSlot=ClassRegistry::init("AppointmentSlot");
        $this->Slot=ClassRegistry::init("Slot");
        $this->Vacation=ClassRegistry::init("Vacation");
        $id = $user_id;
        $schedule_info = $this->Schedule->find('first', array('conditions' => array('Schedule.user_id' => $id, 'Schedule.week_id' => $week_id, 'Schedule.status' => 1,'Schedule.working' => 1)));
        if(!empty($schedule_info)){
        $scheduleId = $schedule_info['Schedule']['id'];
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
        if (!empty($booked_app)) {
            $slotIds = array_merge($slotIds, $booked_app);
        }
        $currentTime = date('00:00:00');
        $available_slots = $this->Slot->find('list', array('conditions' => array('Slot.schedule_id' => $scheduleId, 'Slot.time_24 >=' => $currentTime, 'NOT' => array('Slot.id' => $slotIds)), 'fields' => array('id', 'time'), 'order' => array('time_24' => 'asc')));

        $vacations = $this->Vacation->find('all', array('conditions' => array('user_id' => $id, 'DATE_FORMAT(from_date,"%Y-%m-%d") <=' => $seldate, 'DATE_FORMAT(to_date,"%Y-%m-%d") >=' => $seldate)));


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
        //  pr($available_slots);
        $avl_slots = array_values($available_slots);
        return (isset($avl_slots[0]) && $avl_slots[0] != '') ? 1 : 0;
        }
        return 0;
    }
    function check_barber_next_av($user_id,$week_id,$seldate) {
        $this->Schedule=ClassRegistry::init("Schedule");
        $this->LunchBreak=ClassRegistry::init("LunchBreak");
        $this->Appointment=ClassRegistry::init("Appointment");
        $this->ReserveSlot=ClassRegistry::init("ReserveSlot");
        $this->AppointmentSlot=ClassRegistry::init("AppointmentSlot");
        $this->Slot=ClassRegistry::init("Slot");
        $this->Vacation=ClassRegistry::init("Vacation");
        $id = $user_id;
        $result=array('next'=>0);   
        $schedule_info = $this->Schedule->find('first', array('conditions' => array('Schedule.user_id' => $id, 'Schedule.week_id' => $week_id, 'Schedule.status' => 1,'Schedule.working' => 1)));
        if(!empty($schedule_info)){
        $scheduleId = $schedule_info['Schedule']['id'];
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
        if (!empty($booked_app)) {
            $slotIds = array_merge($slotIds, $booked_app);
        }
        $currentTime = date('00:00:00');
        $available_slots = $this->Slot->find('list', array('conditions' => array('Slot.schedule_id' => $scheduleId, 'Slot.time_24 >=' => $currentTime, 'NOT' => array('Slot.id' => $slotIds)), 'fields' => array('id', 'time'), 'order' => array('time_24' => 'asc')));

        $vacations = $this->Vacation->find('all', array('conditions' => array('user_id' => $id, 'DATE_FORMAT(from_date,"%Y-%m-%d") <=' => $seldate, 'DATE_FORMAT(to_date,"%Y-%m-%d") >=' => $seldate)));


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
        
        $avl_slots = array_values($available_slots);
       
        if(isset($avl_slots[0]) && $avl_slots[0] != ''){
          $result=array('next'=>1,'date'=>date('m/d/Y', strtotime($seldate)),'slot'=>$avl_slots[0]);   
        }else{
          $result=array('next'=>0);   
        }
        }
        return $result;
    }
}
