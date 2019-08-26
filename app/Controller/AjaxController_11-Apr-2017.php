<?php

App::uses('AppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class AjaxController extends AppController {

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array('Text', 'Js', 'Time');
    public $components = array('Common', 'Image');

    /**
     *
     * Model
     *  
     */
    public $uses = array('User', 'Appointment', 'Slot', 'Schedule', 'LunchBreak', 'ReserveSlot', 'Vacation');

    /**
     * Components
     *
     * @var array
     */
    //public $components = array('Common', 'Schedule');

    public function beforeFilter() {
        parent::beforeFilter();
        if (!$this->request->is('ajax') && $this->request->is('get')) {
            $this->redirect('/');
            exit;
        }
        $this->Auth->allow(array('check_field'));
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

    public function check_field() {
        $this->layout = false;
        $this->autoRender = false;
        $field = $this->request->data['field'];
        $fieldValue = $this->request->data['User'][$field];
        $count = $this->User->find('count', array('conditions' => array('User.' . $field => $fieldValue)));
        if ($count > 0) {
            exit('false');
        } else {
            exit('true');
        }
    }

    public function get_barber_schedule($id, $scheduleId, $seldate = null) {

        $schedule_hour = Configure::read('Site.scheduleHour');
        $today = date('Y-m-d');
        if ($this->Auth->user('window_hours')) {
            $window_hours = $this->Auth->user('window_hours');
        }

		
		if(!empty($seldate)){
			$seldate = date("Y-m-d",strtotime($seldate));
		}
		
        $currentTime = date('H:i:s', strtotime('+' . $window_hours . ' hours'));

        $time_after_add_schedule_hour = date('H:i:00', strtotime('+' . $schedule_hour . ' days'));

        $this->layout = false;

        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'className' => 'User', 'fields' => array('image', 'name', 'id')), 'Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'phone')), 'Slot')), false);
        $lunch_breaks = $this->LunchBreak->find('list', array('conditions' => array('LunchBreak.schedule_id' => $scheduleId, 'LunchBreak.user_id' => $id), 'fields' => array('id', 'slot_id')));

//        $appointments = $this->Appointment->find('all', array('conditions' => array('Appointment.status' => 0, 'Appointment.barber_id' => $id, 'Appointment.schedule_id' => $scheduleId, 'Appointment.date' => $seldate), 'order' => array('Slot.time_24' => 'asc')));
        $appointments = $this->Appointment->find('all', array('conditions' => array('Appointment.status' => 0, 'Appointment.schedule_id' => $scheduleId, 'Appointment.date' => $seldate), 'order' => array('Slot.time_24' => 'asc')));


        $ReserveSlot = $this->ReserveSlot->find('list', array('conditions' => array('ReserveSlot.user_id' => $id, 'ReserveSlot.date' => $seldate), 'fields' => array('id', 'slot_id')));

        if ($this->Session->read('Auth.User.role_id') == 4) {

//            $appointmentsVew = $this->Appointment->find('all', array('conditions' => array('Appointment.status' => 0, 'Appointment.customer_id' => $this->Session->read('Auth.User.id'), 'Appointment.barber_id' => $id, 'Appointment.schedule_id' => $scheduleId, 'Appointment.date' => $seldate), 'order' => array('Slot.time' => 'asc')));

            $appointmentsVew = $this->Appointment->find('all', array('conditions' => array('Appointment.status' => 0, 'Appointment.customer_id' => $this->Session->read('Auth.User.id'), 'Appointment.date' => $seldate), 'order' => array('Slot.time_24' => 'asc')));
        } else {
            $appointmentsVew = $appointments;
        }
		
        $booked_app = Set::extract('/Appointment/slot_id', $appointments);

		$this->set("booked_appointment",$booked_app);
		$this->set("seldate",$seldate);
		
		$barber_info = $this->User->find('first', array('conditions' => array('User.id' => $id)));
		$this->set('barber_info', $barber_info);
		
        //$slotIds = array_merge($slotIds, $lunch_breaks);
        $slotIds = $lunch_breaks;
		if ($ReserveSlot) {
            $slotIds = array_merge($slotIds, $ReserveSlot);
        }
		
        if (strtotime($seldate) == strtotime($today)) {
            $available_slots = $this->Slot->find('list', array('conditions' => array('Slot.schedule_id' => $scheduleId, 'Slot.time_24 >=' => $currentTime, 'NOT' => array('Slot.id' => $slotIds)), 'fields' => array('id', 'time'), 'order' => array('time_24' => 'asc')));
        } else {
            $available_slots = $this->Slot->find('list', array('conditions' => array('Slot.schedule_id' => $scheduleId, 'NOT' => array('Slot.id' => $slotIds)), 'fields' => array('id', 'time'), 'order' => array('time_24' => 'asc')));
        }
        $vacations = $this->Vacation->find('all', array('conditions' => array('user_id' => $id, 'DATE_FORMAT(from_date,"%Y-%m-%d") <=' => $seldate, 'DATE_FORMAT(to_date,"%Y-%m-%d") >=' => $seldate)));
		
		
		$schedule_info = $this->Schedule->find('first', array('conditions' => array('id' => $scheduleId, 'Schedule.working' => 1)));
		
		if(empty($schedule_info)){
			$available_slots = array();
		}
		
		$slot_time = 0;
		if(!empty($schedule_info)){
			$schedule_slot = $schedule_info['Schedule']['slot_time'];
		}
		
		
		
		if (!empty($vacations)) {
			foreach($vacations as $vacation) {
				$from_date = $vacation['Vacation']['from_date'];
				$to_date = $vacation['Vacation']['to_date'];
				
				$from_dt_arr = explode(" ",$from_date);
				$to_dt_arr = explode(" ",$to_date);
				
				$from_dt_arr[1] = date("h:i A",strtotime($from_dt_arr[1]));
				$to_dt_arr[1] = date("h:i A",strtotime($to_dt_arr[1]));
				
				
				
				$slot_to_remove = array();
				
				if($from_dt_arr[0] == $seldate && $to_dt_arr[0] == $seldate){
					if(!empty($available_slots)){
						if(in_array($from_dt_arr[1], $available_slots) && in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								if(strtotime($slot) >= strtotime($from_dt_arr[1])) {
									$slot_to_remove[] = $slot;
									if(strtotime($slot) == strtotime($to_dt_arr[1])) {
										break;
									}
								}
							}
							
						}
						else if(in_array($from_dt_arr[1], $available_slots) && !in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								if(strtotime($slot) >= strtotime($from_dt_arr[1])) {
									$slot_to_remove[] = $slot;
									
									$to_tm_arr = explode(":",date("H",strtotime($to_dt_arr[1])));
									$slot_tm_arr = explode(":",date("H",strtotime($slot)));
									
									$similar_time = "";
									if($to_tm_arr[0] == $slot_tm_arr[0]){
										$similar_time = date("h:i A",strtotime($to_dt_arr[1]));
									}
									
									if(!empty($similar_time)) {
									
										$i =0;
										while($i != 1){
											if(!in_array($similar_time,$available_slots)) {
												$similar_time = date("H:i",strtotime($similar_time));
												$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
												$similar_time = date("h:i A",strtotime($similar_time));
											}
											else {
												$i = 1;
											}
										}
										
										$similar_time = date("H:i",strtotime($similar_time));
										//echo date("H:i",strtotime($similar_time));die;
										$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
									}
									if(isset($similar_time_str)){
										if(strtotime($slot) == strtotime($similar_time_str)) {
											break;
										}
									}
								}
							}
						}
						
						else if(!in_array($from_dt_arr[1], $available_slots) && in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								$front_tm_arr = explode(":",date("H",strtotime($from_dt_arr[1])));
								$slot_tm_arr = explode(":",date("H",strtotime($slot)));
								
								$similar_time = "";
								if($front_tm_arr[0] == $slot_tm_arr[0]){
									$similar_time = date("h:i A",strtotime($from_dt_arr[1]));
								}
								
								$similar_time_str = "";
								if(!empty($similar_time)) {
									
									$i =0;
									while($i != 1){
										if(!in_array($similar_time,$available_slots)) {
											$similar_time = date("H:i",strtotime($similar_time));
											$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
											$similar_time = date("h:i A",strtotime($similar_time));
										}
										else {
											$i = 1;
										}
									}
									
									$similar_time = date("H:i",strtotime($similar_time));
									//echo date("H:i",strtotime($similar_time));die;
									$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
									
									
								}
								if(!empty($similar_time_str)){
									if(strtotime($slot) >= strtotime($similar_time_str)) {
										$slot_to_remove[] = $slot;
										if(strtotime($slot) == strtotime($to_dt_arr[1])) {
											break;
										}
									}
								}
								else {
									if(strtotime($from_dt_arr[1]) < strtotime(reset($available_slots)) && strtotime($to_dt_arr[1]) > strtotime($slot)){
										$slot_to_remove[] = $slot;
									}
								}
							}
							
						}
						else if(!in_array($from_dt_arr[1], $available_slots) && !in_array($to_dt_arr[1], $available_slots)) {
						
							foreach($available_slots as $slot) {
								$front_tm_arr = explode(":",date("H",strtotime($from_dt_arr[1])));
								$slot_tm_arr = explode(":",date("H",strtotime($slot)));
								$similar_time = "";
								if($front_tm_arr[0] == $slot_tm_arr[0]){
									$similar_time = date("h:i A",strtotime($from_dt_arr[1]));
								}
								$similar_time_str = "";
								if(!empty($similar_time)) {
									
									$i =0;
									while($i != 1){
										if(!in_array($similar_time,$available_slots)) {
											$similar_time = date("H:i",strtotime($similar_time));
											$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
											$similar_time = date("h:i A",strtotime($similar_time));
										}
										else {
											$i = 1;
										}
									}
									
									$similar_time = date("H:i",strtotime($similar_time));
									//echo date("H:i",strtotime($similar_time));die;
									$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
									
									
								}
								if(!empty($similar_time_str)){
									foreach($available_slots as $slot) {
										if(strtotime($slot) >= strtotime($similar_time_str)) {
											
											$slot_to_remove[] = $slot;
											
											$to_tm_arr = explode(":",date("H",strtotime($to_dt_arr[1])));
											$slot_tm_arr = explode(":",date("H",strtotime($slot)));
											
											$similar_to_time = "";
											if($to_tm_arr[0] == $slot_tm_arr[0]){
												$similar_to_time = date("h:i A",strtotime($to_dt_arr[1]));
												
											}
											$similar_to_time_str = "";
											if(!empty($similar_to_time)) {
										
												$i =0;
												while($i != 1){
													if(!in_array($similar_to_time,$available_slots)) {
														$similar_to_time = date("H:i",strtotime($similar_to_time));
														$similar_to_time = date("H:i",strtotime($similar_to_time. '+ 1 minute'));
														$similar_to_time = date("h:i A",strtotime($similar_to_time));
													}
													else {
														$i = 1;
													}
												}
												
												$similar_to_time = date("H:i",strtotime($similar_to_time));
												//echo date("H:i",strtotime($similar_to_time));die;
												$similar_to_time_str = date("H:i",strtotime($similar_to_time. '- '.$schedule_slot.' minute'));	
											}
											if(strtotime($slot) == strtotime($similar_to_time_str)) {
												break;
											}
										}
									}
									break;
								}
								else {
									foreach($available_slots as $slot) {
										if(strtotime($from_dt_arr[1]) < strtotime(reset($available_slots)) && strtotime($to_dt_arr[1]) > strtotime(end($available_slots))) {
											$slot_to_remove[] = $slot;
										}
									}
								}
							}
						}
					}
					$available_slots = array_diff($available_slots, $slot_to_remove);
					
				}
				else if($from_dt_arr[0] == $seldate && $to_dt_arr[0] != $seldate){
					
					if(!empty($available_slots)){
						$slot_to_remove = array();
						if(in_array($from_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								if(strtotime($slot) >= strtotime($from_dt_arr[1])) {
									$slot_to_remove[] = $slot;
									if(strtotime($slot) == strtotime(end($available_slots))) {
										break;
									}
								}
							}
						}
						else if(!in_array($from_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								$front_tm_arr = explode(":",date("H",strtotime($from_dt_arr[1])));
								$slot_tm_arr = explode(":",date("H",strtotime($slot)));
								
								
								
								$similar_time = "";
								if($front_tm_arr[0] == $slot_tm_arr[0]){
									$similar_time = date("h:i A",strtotime($from_dt_arr[1]));
								}
								
								$similar_time_str = "";
								if(!empty($similar_time)) {
									
									$i =0;
									while($i != 1){
										if(!in_array($similar_time,$available_slots)) {
											$similar_time = date("H:i",strtotime($similar_time));
											$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
											$similar_time = date("h:i A",strtotime($similar_time));
										}
										else {
											$i = 1;
										}
									}
									
									$similar_time = date("H:i",strtotime($similar_time));
									//echo date("H:i",strtotime($similar_time));die;
									$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
									
								}
								if(!empty($similar_time_str)){
									foreach($available_slots as $slot) {
										if(strtotime($slot) >= strtotime($similar_time_str)) {
											$slot_to_remove[] = $slot;
											if(strtotime($slot) == strtotime(end($available_slots))) {
												break;
											}
										}
									}	
								}
								else {
									foreach($available_slots as $slot) {
										if(strtotime($from_dt_arr[1]) < strtotime(reset($available_slots))){
											$slot_to_remove[] = $slot;
										}
										/* if(strtotime($from_dt_arr[1]) > strtotime(end($available_slots))){
											$slot_to_remove[] = $slot;
										} */
									}
								}
							}
						}
					}
					$available_slots = array_diff($available_slots, $slot_to_remove);
				}
				else if($from_dt_arr[0] != $seldate && $to_dt_arr[0] == $seldate){
				
					/* echo $seldate;
					pr($from_dt_arr);
					pr($to_dt_arr);die; */
				
					if(!empty($available_slots)){
						$slot_to_remove = array();
						if(in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								$slot_to_remove[] = $slot;
								if(strtotime($slot) == strtotime($to_dt_arr[1])) {
									break;
								}								
							}
						}
						else if(!in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								if(strtotime($to_dt_arr[1]) < strtotime($slot)){
									break;
								}
								$slot_to_remove[] = $slot;
								
								$to_tm_arr = explode(":",date("H",strtotime($to_dt_arr[1])));
								$slot_tm_arr = explode(":",date("H",strtotime($slot)));
								
								$similar_time = "";
								if($to_tm_arr[0] == $slot_tm_arr[0]){
									$similar_time = date("h:i A",strtotime($to_dt_arr[1]));
								}
								$similar_time_str = "";
								if(!empty($similar_time)) {
									$i =0;
									while($i != 1){
										if(!in_array($similar_time,$available_slots)) {
											$similar_time = date("H:i",strtotime($similar_time));
											$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
											$similar_time = date("h:i A",strtotime($similar_time));
										}
										else {
											$i = 1;
										}
									}
									
									$similar_time = date("H:i",strtotime($similar_time));
									//echo date("H:i",strtotime($similar_time));die;
									$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
								}
								if(strtotime($slot) == strtotime($similar_time_str)) {
									break;
								}
							}
						}
					}
					$available_slots = array_diff($available_slots, $slot_to_remove);
				}
				else {
					$available_slots = array();
				}
			}
		}
		
		
        $this->set(compact('appointments', 'available_slots', 'appointmentsVew'));
        $this->render('/Barbers/get_barber_schedule');
    }

    public function get_barber_schedule_barber($id, $scheduleId, $seldate = null) {

        $schedule_hour = Configure::read('Site.scheduleHour');
        $today = date('Y-m-d');
        $window_hours = 3;

        if ($this->Auth->user('window_hours')) {
            $window_hours = $this->Auth->user('window_hours');
        }
		
		if(!empty($seldate)){
			$seldate = date("Y-m-d",strtotime($seldate));
		}

        $currentTime = date('H:i:s', strtotime('+' . $window_hours . ' hours'));

        $time_after_add_schedule_hour = date('H:i:00', strtotime('+' . $schedule_hour . ' days'));

        $this->layout = false;

        //  $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'className' => 'User', 'fields' => array('image', 'name', 'id')), 'Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'phone')), 'Slot')), false);
        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'fields' => array('image', 'name', 'phone', 'id')), 'Slot')), false);
        $lunch_breaks = $this->LunchBreak->find('list', array('conditions' => array('LunchBreak.schedule_id' => $scheduleId, 'LunchBreak.user_id' => $id), 'fields' => array('id', 'slot_id')));

        $appointments = $this->Appointment->find('all', array('conditions' => array('Appointment.status' => 0, 'Appointment.barber_id' => $id, 'Appointment.schedule_id' => $scheduleId, 'Appointment.date' => $seldate), 'order' => array('Slot.time_24' => 'asc')));

        $slotIds = Set::extract('/Appointment/slot_id', $appointments);
		
        $slotIds = array_merge($slotIds, $lunch_breaks);

        if (strtotime($seldate) == strtotime($today)) {
            $available_slots = $this->Slot->find('list', array('conditions' => array('Slot.schedule_id' => $scheduleId, 'Slot.time_24 >=' => $currentTime, 'NOT' => array('Slot.id' => $slotIds)), 'fields' => array('id', 'time'), 'order' => array('time_24' => 'asc')));
        } else {
            $available_slots = $this->Slot->find('list', array('conditions' => array('Slot.schedule_id' => $scheduleId, 'NOT' => array('Slot.id' => $slotIds)), 'fields' => array('id', 'time'), 'order' => array('time_24' => 'asc')));
        }
		
		$vacations = $this->Vacation->find('all', array('conditions' => array('user_id' => $id, 'DATE_FORMAT(from_date,"%Y-%m-%d") <=' => $seldate, 'DATE_FORMAT(to_date,"%Y-%m-%d") >=' => $seldate)));
		
		$schedule_info = $this->Schedule->find('first', array('conditions' => array('id' => $scheduleId)));
		
		$slot_time = 0;
		if(!empty($schedule_info)){
			$schedule_slot = $schedule_info['Schedule']['slot_time'];
		}

		
        /* $Vacation = $this->Vacation->find('first', array('conditions' => array('Vacation.user_id' => $id, 'Vacation.from_date <=' => $seldate, 'Vacation.to_date >=' => $seldate))); */
		
        
		if (!empty($vacations)) {
			foreach($vacations as $vacation) {
				$from_date = $vacation['Vacation']['from_date'];
				$to_date = $vacation['Vacation']['to_date'];
				
				$from_dt_arr = explode(" ",$from_date);
				$to_dt_arr = explode(" ",$to_date);
				
				$from_dt_arr[1] = date("h:i A",strtotime($from_dt_arr[1]));
				$to_dt_arr[1] = date("h:i A",strtotime($to_dt_arr[1]));
				
				$slot_to_remove = array();
				
				if($from_dt_arr[0] == $seldate && $to_dt_arr[0] == $seldate){
					if(!empty($available_slots)){
						
						if(in_array($from_dt_arr[1], $available_slots) && in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								if(strtotime($slot) >= strtotime($from_dt_arr[1])) {
									$slot_to_remove[] = $slot;
									if(strtotime($slot) == strtotime($to_dt_arr[1])) {
										break;
									}
								}
							}
							
						}
						else if(in_array($from_dt_arr[1], $available_slots) && !in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								if(strtotime($slot) >= strtotime($from_dt_arr[1])) {
									$slot_to_remove[] = $slot;
									
									$to_tm_arr = explode(":",date("H",strtotime($to_dt_arr[1])));
									$slot_tm_arr = explode(":",date("H",strtotime($slot)));
									
									$similar_time = "";
									if($to_tm_arr[0] == $slot_tm_arr[0]){
										$similar_time = date("h:i A",strtotime($to_dt_arr[1]));
									}
									
									if(!empty($similar_time)) {
									
										$i =0;
										while($i != 1){
											if(!in_array($similar_time,$available_slots)) {
												$similar_time = date("H:i",strtotime($similar_time));
												$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
												$similar_time = date("h:i A",strtotime($similar_time));
											}
											else {
												$i = 1;
											}
										}
										
										$similar_time = date("H:i",strtotime($similar_time));
										//echo date("H:i",strtotime($similar_time));die;
										$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
									}	
									if(strtotime($slot) == strtotime($similar_time_str)) {
										break;
									}
									
								}
							}
						}
						
						else if(!in_array($from_dt_arr[1], $available_slots) && in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								$front_tm_arr = explode(":",date("H",strtotime($from_dt_arr[1])));
								$slot_tm_arr = explode(":",date("H",strtotime($slot)));
								
								$similar_time = "";
								if($front_tm_arr[0] == $slot_tm_arr[0]){
									$similar_time = date("h:i A",strtotime($from_dt_arr[1]));
								}
								
								$similar_time_str = "";
								if(!empty($similar_time)) {
									
									$i =0;
									while($i != 1){
										if(!in_array($similar_time,$available_slots)) {
											$similar_time = date("H:i",strtotime($similar_time));
											$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
											$similar_time = date("h:i A",strtotime($similar_time));
										}
										else {
											$i = 1;
										}
									}
									
									$similar_time = date("H:i",strtotime($similar_time));
									//echo date("H:i",strtotime($similar_time));die;
									$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
									
									
								}
								if(!empty($similar_time_str)){
									if(strtotime($slot) >= strtotime($similar_time_str)) {
										$slot_to_remove[] = $slot;
										if(strtotime($slot) == strtotime($to_dt_arr[1])) {
											break;
										}
									}
								}
							}
							
						}
						else if(!in_array($from_dt_arr[1], $available_slots) && !in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								$front_tm_arr = explode(":",date("H",strtotime($from_dt_arr[1])));
								$slot_tm_arr = explode(":",date("H",strtotime($slot)));
								$similar_time = "";
								if($front_tm_arr[0] == $slot_tm_arr[0]){
									$similar_time = date("h:i A",strtotime($from_dt_arr[1]));
								}
								$similar_time_str = "";
								if(!empty($similar_time)) {
									
									$i =0;
									while($i != 1){
										if(!in_array($similar_time,$available_slots)) {
											$similar_time = date("H:i",strtotime($similar_time));
											$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
											$similar_time = date("h:i A",strtotime($similar_time));
										}
										else {
											$i = 1;
										}
									}
									
									$similar_time = date("H:i",strtotime($similar_time));
									//echo date("H:i",strtotime($similar_time));die;
									$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
									
									
								}
								if(!empty($similar_time_str)){
									foreach($available_slots as $slot) {
										if(strtotime($slot) >= strtotime($similar_time_str)) {
											
											$slot_to_remove[] = $slot;
											
											$to_tm_arr = explode(":",date("H",strtotime($to_dt_arr[1])));
											$slot_tm_arr = explode(":",date("H",strtotime($slot)));
											
											$similar_to_time = "";
											if($to_tm_arr[0] == $slot_tm_arr[0]){
												$similar_to_time = date("h:i A",strtotime($to_dt_arr[1]));
												
											}
											$similar_to_time_str = "";
											if(!empty($similar_to_time)) {
										
												$i =0;
												while($i != 1){
													if(!in_array($similar_to_time,$available_slots)) {
														$similar_to_time = date("H:i",strtotime($similar_to_time));
														$similar_to_time = date("H:i",strtotime($similar_to_time. '+ 1 minute'));
														$similar_to_time = date("h:i A",strtotime($similar_to_time));
													}
													else {
														$i = 1;
													}
												}
												
												$similar_to_time = date("H:i",strtotime($similar_to_time));
												//echo date("H:i",strtotime($similar_to_time));die;
												$similar_to_time_str = date("H:i",strtotime($similar_to_time. '- '.$schedule_slot.' minute'));	
											}
											if(strtotime($slot) == strtotime($similar_to_time_str)) {
												break;
											}
										}
									}
									break;
								}
							}
						}
					}
					$available_slots = array_diff($available_slots, $slot_to_remove);
					
				}
				else if($from_dt_arr[0] == $seldate && $to_dt_arr[0] != $seldate){
					if(!empty($available_slots)){
						$slot_to_remove = array();
						if(in_array($from_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								if(strtotime($slot) >= strtotime($from_dt_arr[1])) {
									$slot_to_remove[] = $slot;
									if(strtotime($slot) == strtotime(end($available_slots))) {
										break;
									}
								}
							}
						}
						else if(!in_array($from_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								$front_tm_arr = explode(":",date("H",strtotime($from_dt_arr[1])));
								$slot_tm_arr = explode(":",date("H",strtotime($slot)));
								
								$similar_time = "";
								if($front_tm_arr[0] == $slot_tm_arr[0]){
									$similar_time = date("h:i A",strtotime($from_dt_arr[1]));
								}
								$similar_time_str = "";
								if(!empty($similar_time)) {
									
									$i =0;
									while($i != 1){
										if(!in_array($similar_time,$available_slots)) {
											$similar_time = date("H:i",strtotime($similar_time));
											$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
											$similar_time = date("h:i A",strtotime($similar_time));
										}
										else {
											$i = 1;
										}
									}
									
									$similar_time = date("H:i",strtotime($similar_time));
									//echo date("H:i",strtotime($similar_time));die;
									$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
									
								}
								if(!empty($similar_time_str)){
									foreach($available_slots as $slot) {
										if(strtotime($slot) >= strtotime($similar_time_str)) {
											$slot_to_remove[] = $slot;
											if(strtotime($slot) == strtotime(end($available_slots))) {
												break;
											}
										}
									}	
								}	
							}
						}
					}
					$available_slots = array_diff($available_slots, $slot_to_remove);
				}
				else if($from_dt_arr[0] != $seldate && $to_dt_arr[0] == $seldate){
					if(!empty($available_slots)){
						$slot_to_remove = array();
						if(in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								$slot_to_remove[] = $slot;
								if(strtotime($slot) == strtotime($to_dt_arr[1])) {
									break;
								}								
							}
						}
						else if(!in_array($to_dt_arr[1], $available_slots)) {
							foreach($available_slots as $slot) {
								$slot_to_remove[] = $slot;
								
								$to_tm_arr = explode(":",date("H",strtotime($to_dt_arr[1])));
								$slot_tm_arr = explode(":",date("H",strtotime($slot)));
								
								$similar_time = "";
								if($to_tm_arr[0] == $slot_tm_arr[0]){
									$similar_time = date("h:i A",strtotime($to_dt_arr[1]));
								}
								$similar_time_str = "";
								if(!empty($similar_time)) {
									
									$i =0;
									while($i != 1){
										if(!in_array($similar_time,$available_slots)) {
											$similar_time = date("H:i",strtotime($similar_time));
											$similar_time = date("H:i",strtotime($similar_time. '+ 1 minute'));
											$similar_time = date("h:i A",strtotime($similar_time));
										}
										else {
											$i = 1;
										}
									}
									
									$similar_time = date("H:i",strtotime($similar_time));
									//echo date("H:i",strtotime($similar_time));die;
									$similar_time_str = date("H:i",strtotime($similar_time. '- '.$schedule_slot.' minute'));
								}
								if(strtotime($slot) == strtotime($similar_time_str)) {
									break;
								}
							}
						}
					}
					
					$available_slots = array_diff($available_slots, $slot_to_remove);
				}
				else {
					$available_slots = array();
				}
			}
		}
		
        $this->set(compact('appointments', 'available_slots'));
        $this->render('/Barbers/get_barber_schedule_barber');
    }

    public function book_schedule() {
        $today = date('Y-m-d');
        $seldate = $this->request->data['date'];

        $apointment = $this->Appointment->findByBarberIdAndScheduleIdAndSlotIdAndDate($this->request->data['barber_id'], $this->request->data['schedule_id'], $this->request->data['slot_id'], $this->request->data['date']);
		
        $barber = $this->Session->read('Auth.User.barber');
        $barberAdmin = $this->User->findByShopSlugAndStatus($barber, 1);
        $barbers = $this->User->find('list', array('conditions' => array('User.parent_id' => $barberAdmin['User']['id'], 'User.status' => 1), 'fields' => array('User.id')));


        $barberDetail = $this->User->findById($this->request->data['barber_id']);

        $already_apointment = $this->Appointment->findByBarberIdAndCustomerIdAndStatusAndDate($barbers, $this->Auth->user('id'), 0, $this->request->data['date']);
        //$already_apointment = $this->Appointment->findByBarberIdAndCustomerIdAndStatusAndScheduleIdAndDate($barbers, $this->Auth->user('id'), 0, $this->request->data['schedule_id'], $this->request->data['date']);
        // $already_apointment = $this->Appointment->findByBarberIdAndCustomerIdAndStatusAndScheduleIdAndSlotIdAndDate($barbers, $this->Auth->user('id'), 0, $this->request->data['schedule_id'], $this->request->data['slot_id'], $this->request->data['date']);

        $response = array();
        $this->request->data['customer_id'] = $this->Auth->user('id');
        $slot_detail = $this->Slot->findById($this->request->data['slot_id']);
		
        $currentTime = strtotime(date('h:i A'));
        $slot_detail['Slot']['time'];
		$slot_detail['Slot']['date'] = $this->request->data['date'];
        if ($this->Auth->user('window_hours')) {
            $window_hours = $this->Auth->user('window_hours');
        }

        $allowedTime = date('h:i A', strtotime('+' . $window_hours . ' hours', $currentTime));
        $slotTime = strtotime($slot_detail['Slot']['time']);
        $allowedTime = strtotime($allowedTime);

        if (strtotime($seldate) == strtotime($today)) {
            if ($slotTime < $currentTime) {
                $response = array('error' => 1, 'msg' => 'You can not book old slot. Please try again.');
                echo json_encode($response);
                exit;
            }

            if ($slotTime <= $allowedTime) {
                $response = array('error' => 1, 'msg' => 'You can not book a slot for next 3 hours. Please try for other time.');
                echo json_encode($response);
                exit;
            }
        }
		
        if($slot_detail['Slot']['schedule_id']!= $this->request->data['schedule_id']){
             $response = array('error' => 1, 'msg' => 'Appointment already booked by another customer.');
             echo json_encode($response);
             exit;
        }
		
        if (empty($apointment)) {
            if ($appointment = $this->Appointment->save($this->request->data)) {
				
                $this->_sendEmailToCustomer($appointment, $slot_detail, $barberDetail);
                $this->_sendEmailToBarber($slot_detail, $barberDetail);

                $response = array('error' => 0, 'msg' => 'You have successfully booked your appointment.');
            } else {
                $response = array('error' => 1, 'msg' => 'Unable to book appointment. Please try again.');
            }
        } else {
            $response = array('error' => 1, 'msg' => 'Appointment already booked by another customer.');
        }
        echo json_encode($response);
        exit;
    }

    function _sendEmailToCustomer($appointment, $slot, $barberDetail) {
        $site_url = SITE_FULL_URL . $this->Session->read('Auth.User.barber') . '/login';

        $site_url = "<a style='text-decoration:none;color:#646464;' href='$site_url'>Click Here </a>";
        $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'AppointmentConfirmation')));
        $mail['SystemMail']['message'] = str_replace('[fullname]', $this->Auth->user('name'), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[timeslot]', $slot['Slot']['time'], $mail['SystemMail']['message']);
		if(!empty($appointment)) {
			$mail['SystemMail']['message'] = str_replace('[appdate]', date("m/d/Y",strtotime($appointment['Appointment']['date'])), $mail['SystemMail']['message']);
		}
        $mail['SystemMail']['message'] = str_replace('[BARBERNAME]', $barberDetail['User']['name'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[BARBERPHONE]', $barberDetail['User']['phone'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[LINK] ', $site_url, $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
//           $url = Router::url(array('controller' => 'users', 'action' => 'activate', $id, $userDetail['User']['activation_key']), true);
        $to = $this->Auth->user('email');
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

    function set_working() {
        $this->Schedule->updateAll(array('Schedule.working' => $this->request->data['status']), array('Schedule.user_id' => $this->request->data['user_id'], 'Schedule.week_id' => $this->request->data['week_id']));
        exit;
    }

    function change_profile_image() {
        $destination = WWW_ROOT . 'uploads' . DS . 'users' . DS;
        $file = $_FILES['uploadfile'];
        $image_date = getimagesize($file['tmp_name']);
        $image_size = filesize($file['tmp_name']);
        if ($image_size > 2097152) {
            $respons = array('error' => 1, 'msg' => 'Image too large. Please use less then 2MB image.');
        } else {
            list($width, $height, $type, $attr) = getimagesize($file['tmp_name']);
            $filename = time() . str_replace(' ', '', $file['name']);
            $final_destination = $destination . $filename;
            $this->User->recursive = -1;
            $old_data = $this->User->findById($this->Session->read('Auth.User.id'));
            if (move_uploaded_file($file['tmp_name'], $final_destination)) {
                $this->Image->resample($final_destination);
                $this->User->id = $this->Session->read('Auth.User.id');
                $this->request->data['User']['image'] = $filename;
                if ($this->User->save($this->request->data)) {
                    @unlink($destination . $old_data['User']['image']);
                    $respons = array('error' => 0, 'image' => $filename);
                } else {
                    $respons = array('error' => 1, 'msg' => 'An error accured. Please try again.');
                }
            } else {
                $respons = array('error' => 1, 'msg' => 'An error accured. Please try again.');
            }
        }
        echo json_encode($respons);
        exit;
    }

    function change_schedule() {
		$respons = array();
        if (!empty($this->request->data)) {
			$st_time = strtotime($this->request->data['Schedule']['start_time']);
            $end_time = strtotime($this->request->data['Schedule']['end_time']);
            if ($st_time >= $end_time) {
                $respons = array('error' => 1, 'msg' => 'Start time should be less than end time.');
            } else {

                $slots = $this->getServiceScheduleSlots($this->request->data['Schedule']['slot_time'], 0, $this->request->data['Schedule']['start_time'], $this->request->data['Schedule']['end_time']);


                if (empty($slots)) {
                    $respons = array('error' => 1, 'msg' => 'Schedule could not be created. Please, try again.');
                }
                $schedule_exist = $this->Schedule->find('count', array('conditions' => array('Schedule.status' => 1, 'Schedule.user_id' => $this->request->data['Schedule']['user_id'], 'Schedule.week_id' => $this->request->data['Schedule']['week_id'], 'Schedule.start_time' => $this->request->data['Schedule']['start_time'], 'Schedule.end_time' => $this->request->data['Schedule']['end_time'], 'Schedule.slot_time' => $this->request->data['Schedule']['slot_time'])));

                if ($schedule_exist > 0) {
                    $this->Session->setFlash('Schedule has been saved.', 'default', array('class' => 'success'));
                    $respons = array('error' => 0, 'msg' => 'Schedule has been created.');
                    echo json_encode($respons);
                    exit;
                }
                if (isset($this->request->data['Schedule']['id'])) {
                    $this->Schedule->updateAll(array('Schedule.status' => 0), array('Schedule.user_id' => $this->request->data['Schedule']['user_id'], 'Schedule.week_id' => $this->request->data['Schedule']['week_id']));
                    unset($this->request->data['Schedule']['working']);
                    unset($this->request->data['Schedule']['id']);
                }
                if ($schedule = $this->Schedule->save($this->request->data)) {
                    foreach ($slots as $key => $slot) {
                        $slotsData['Slot']['schedule_id'] = $schedule['Schedule']['id'];
                        $slotsData['Slot']['time'] = $slot;
                        $slotsData['Slot']['time_24'] = $key;
                        $this->Slot->create();
                        $this->Slot->save($slotsData);
                    }
                    $this->Session->setFlash('Schedule has been saved.', 'default', array('class' => 'success'));
					$this->render('/Users/barberschedule');
                    $respons = array('error' => 0, 'msg' => 'Schedule has been created.');
                } else {
                    $respons = array('error' => 1, 'msg' => 'Schedule could not be created. Please, try again.');
                }
            }
        }
        echo json_encode($respons);
        exit;
    }

    public function barber_add_lunch($scheduleId) {
        $this->layout = false;
        $slots = $this->Slot->find('all', array('conditions' => array('Slot.schedule_id' => $scheduleId,), 'fields' => array('id', 'time'), 'order' => array('time_24' => 'asc')));
        $lunch_breaks = $this->LunchBreak->find('list', array('conditions' => array('LunchBreak.schedule_id' => $scheduleId, 'LunchBreak.user_id' => $this->Auth->user('id')), 'fields' => array('id', 'slot_id')));
        $this->set(compact('slots', 'lunch_breaks'));
        $this->render('/Barbers/barber_add_lunch');
    }

    public function book_barber_schedule() {
 
        $res = $this->User->findByEmail($this->request->data['email']);
		
        if (!empty($res)) {
            
        } else {
            $userDetail = array();
            $userDetail['User']['activation_key'] = md5(uniqid());
            $userDetail['User']['role_id'] = 4;
            $userDetail['User']['status'] = 1;
            $userDetail['User']['name'] = $this->request->data['name'];
            $userDetail['User']['phone'] = $this->request->data['phone'];
            $userDetail['User']['email'] = $this->request->data['email'];
            $userDetail['User']['password'] = $this->generateCode(7);
			
            if ($res = $this->User->save($userDetail)) {
                $id = $res['User']['id'];
              
               $shop= $this->User->findById($this->Session->read('Auth.User.parent_id'));
               $site_url = SITE_FULL_URL . $shop['User']['shop_slug'] . '/login';
//
                $site_url = "<a style='text-decoration:none;color:#646464;' href='$site_url'>Click here</a>";

              
                
                $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'UserRegistrationByBarber')));
                $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[first_name]', ucfirst($userDetail['User']['name']), $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[email]', $userDetail['User']['email'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[password]', $userDetail['User']['password'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[link]', $site_url, $mail['SystemMail']['message']);
                $this->Email->to = $userDetail['User']['email'];

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
            }
        }


        $today = date('Y-m-d');
        $seldate = $this->request->data['date'];
        $apointment = $this->Appointment->findByBarberIdAndScheduleIdAndSlotIdAndDate($this->request->data['user_id'], $this->request->data['schedule_id'], $this->request->data['slot_id'], $this->request->data['date']);
        $response = array();

        $slot_detail = $this->Slot->findById($this->request->data['slot_id']);
        $currentTime = strtotime(date('h:i A'));
        $slot_detail['Slot']['time'];
        $slot_detail['Slot']['date'] = $this->request->data['date'];

        if ($this->Auth->user('window_hours')) {
            $window_hours = $this->Auth->user('window_hours');
        }
        $allowedTime = date('h:i A', strtotime('+' . $window_hours . ' hours', $currentTime));

        $slotTime = strtotime($slot_detail['Slot']['time']);
        $allowedTime = strtotime($allowedTime);

        if (strtotime($seldate) == strtotime($today)) {
            if ($slotTime < $currentTime) {
                $response = array('error' => 1, 'msg' => 'You can not book old slot. Please try again.');
                echo json_encode($response);
                exit;
            }

            if ($slotTime <= $allowedTime) {
                $response = array('error' => 1, 'msg' => 'You can not book a slot for next ' . $window_hours . ' hours. Please try for other time.');
                echo json_encode($response);
                exit;
            }
        }

        if (!empty($already_apointment)) {
            $response = array('error' => 1, 'msg' => 'You have already book a slot.');
            echo json_encode($response);
            exit;
        }
		
        if (empty($apointment)) {

            $apointmetDataAttay = array();
            $apointmetDataAttay['barber_id'] = $this->request->data['user_id'];
            $apointmetDataAttay['customer_id'] = $res['User']['id'];
            $apointmetDataAttay['schedule_id'] = $this->request->data['schedule_id'];
            $apointmetDataAttay['slot_id'] = $this->request->data['slot_id'];
            $apointmetDataAttay['date'] = $this->request->data['date'];
            $apointmetDataAttay['booked_by'] = 2;
            if ($appointment = $this->Appointment->save($apointmetDataAttay)) {
				//$barberDetail = $this->User->findById($apointmetDataAttay['barber_id']);
                $customerDetail = $this->User->findById($res['User']['id']);
                $this->_sendEmailToCustomer_barber($appointment,$slot_detail, $customerDetail);
                $this->_sendEmailToBarber_barber($slot_detail, $customerDetail);
                $response = array('error' => 0, 'msg' => 'You have successfully booked your appointment.');
            } else {
                $response = array('error' => 1, 'msg' => 'Unable to book appointment. Please try again.');
            }
        } else {
            $response = array('error' => 1, 'msg' => 'Appointment already booked by another customer.');
        }
        echo json_encode($response);
        exit;
    }

    function generateCode($Length) {

        $characters = '0123456789ABCDEF0123456789ABCDEFGHIJKL0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZMNOPQRSTUVWXYZGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $Length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

	function _sendEmailToBarber($slot, $barberDetail) {
        $site_url = SITE_FULL_URL;

        $site_url = "<a style='text-decoration:none;color:#646464;' href='$site_url'>Click Here </a>";
        $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'BarberAppointmentConfirmation')));
        $mail['SystemMail']['message'] = str_replace('[fullname]', $this->Auth->user('name'), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[contactnumber]', $this->Auth->user('phone'), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[timeslot]', $slot['Slot']['time'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[dateslot]', date("m/d/Y",strtotime($slot['Slot']['date'])), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[BARBERNAME]', $barberDetail['User']['name'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[BARBERPHONE]', $barberDetail['User']['phone'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[LINK] ', $site_url, $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
//           $url = Router::url(array('controller' => 'users', 'action' => 'activate', $id, $userDetail['User']['activation_key']), true);
        //$to = $this->Auth->user('email');
        $to = $barberDetail['User']['email'];
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
	
	function _sendEmailToBarber_barber($slot, $barberDetail) {
        $site_url = SITE_FULL_URL;

        $site_url = "<a style='text-decoration:none;color:#646464;' href='$site_url'>Click Here </a>";
        $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'BarberAppointmentConfirmation')));
        $mail['SystemMail']['message'] = str_replace('[BARBERNAME]', $this->Auth->user('name'), $mail['SystemMail']['message']);
        //$mail['SystemMail']['message'] = str_replace('[contactnumber]', $this->Auth->user('phone'), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[timeslot]', $slot['Slot']['time'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[dateslot]', $slot['Slot']['date'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[fullname]', $barberDetail['User']['name'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[contactnumber]', $barberDetail['User']['phone'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[LINK] ', $site_url, $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
//           $url = Router::url(array('controller' => 'users', 'action' => 'activate', $id, $userDetail['User']['activation_key']), true);
        //$to = $this->Auth->user('email');
        $to = $this->Auth->user('email');
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
	
	function _sendEmailToCustomer_barber($appointment,$slot, $customerDetail) {
        $site_url = SITE_FULL_URL . $this->Session->read('Auth.User.barber') . '/login';

        $site_url = "<a style='text-decoration:none;color:#646464;' href='$site_url'>Click Here </a>";
        $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'AppointmentConfirmation')));
        $mail['SystemMail']['message'] = str_replace('[BARBERNAME]', $this->Auth->user('name'), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[timeslot]', $slot['Slot']['time'], $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[fullname]', $customerDetail['User']['name'], $mail['SystemMail']['message']);
		if(!empty($appointment)) {
			$mail['SystemMail']['message'] = str_replace('[appdate]', date("m/d/Y",strtotime($appointment['Appointment']['date'])), $mail['SystemMail']['message']);
		}
        $mail['SystemMail']['message'] = str_replace('[BARBERPHONE]', $this->Auth->user('phone'), $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[LINK] ', $site_url, $mail['SystemMail']['message']);
        $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
//           $url = Router::url(array('controller' => 'users', 'action' => 'activate', $id, $userDetail['User']['activation_key']), true);
        $to = $customerDetail['User']['email'];
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
	
	public function users_barber_add_lunch($scheduleId) {
        $this->layout = false;
        $slots = $this->Slot->find('all', array('conditions' => array('Slot.schedule_id' => $scheduleId,), 'fields' => array('id', 'time'), 'order' => array('time_24' => 'asc')));
        $lunch_breaks = $this->LunchBreak->find('list', array('conditions' => array('LunchBreak.schedule_id' => $scheduleId, 'LunchBreak.user_id' => $this->Auth->user('id')), 'fields' => array('id', 'slot_id')));
        $this->set(compact('slots', 'lunch_breaks'));
        $this->render('/Users/barber_add_lunch');
    }
	
	
	function user_barber_change_schedule() {
		$respons = array();
        if (!empty($this->request->data)) {
			$st_time = strtotime($this->request->data['Schedule']['start_time']);
            $end_time = strtotime($this->request->data['Schedule']['end_time']);
            if ($st_time >= $end_time) {
                $respons = array('error' => 1, 'msg' => 'Start time should be less than end time.');
            } else {

                $slots = $this->getServiceScheduleSlots($this->request->data['Schedule']['slot_time'], 0, $this->request->data['Schedule']['start_time'], $this->request->data['Schedule']['end_time']);


                if (empty($slots)) {
                    $respons = array('error' => 1, 'msg' => 'Schedule could not be created. Please, try again.');
                }
                $schedule_exist = $this->Schedule->find('count', array('conditions' => array('Schedule.status' => 1, 'Schedule.user_id' => $this->request->data['Schedule']['user_id'], 'Schedule.week_id' => $this->request->data['Schedule']['week_id'], 'Schedule.start_time' => $this->request->data['Schedule']['start_time'], 'Schedule.end_time' => $this->request->data['Schedule']['end_time'], 'Schedule.slot_time' => $this->request->data['Schedule']['slot_time'], 'Schedule.working' => $this->request->data['Schedule']['working'])));
				
                if ($schedule_exist > 0) {
                    $this->Session->setFlash('Schedule has been saved.', 'default', array('class' => 'success'));
                    $respons = array('error' => 0, 'msg' => 'Schedule has been created.');
                    echo json_encode($respons);
                    exit;
                }
				
				if (isset($this->request->data['Schedule']['id'])) {
                    $this->Schedule->updateAll(array('Schedule.status' => 0), array('Schedule.user_id' => $this->request->data['Schedule']['user_id'], 'Schedule.week_id' => $this->request->data['Schedule']['week_id']));
                    //unset($this->request->data['Schedule']['working']);
                    unset($this->request->data['Schedule']['id']);
					$this->Session->setFlash('Schedule has been updated.', 'default', array('class' => 'success'));
					$respons = array('error' => 0, 'msg' => 'Schedule has been updated.');
                }
				
                if ($schedule = $this->Schedule->save($this->request->data)) {
                    foreach ($slots as $key => $slot) {
                        $slotsData['Slot']['schedule_id'] = $schedule['Schedule']['id'];
                        $slotsData['Slot']['time'] = $slot;
                        $slotsData['Slot']['time_24'] = $key;
                        $this->Slot->create();
                        $this->Slot->save($slotsData);
                    }
                    $this->Session->setFlash('Schedule has been saved.', 'default', array('class' => 'success'));
					$this->render('/Users/barberschedule');
                    $respons = array('error' => 0, 'msg' => 'Schedule has been created.');
                } else {
                    $respons = array('error' => 1, 'msg' => 'Schedule could not be created. Please, try again.');
                }
            }
        }
        echo json_encode($respons);
        exit;
    }
	
	public function user_barber_add_vacation() {
		if ($this->request->is('post')) {
			
            if (!empty($this->request->data['Vacation']['from_date']) && !empty($this->request->data['Vacation']['to_date'])) {
            	$VacationData['Vacation']['user_id'] = $this->Session->read('Auth.User.id');
                $VacationData['Vacation']['from_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Vacation']['from_date']));
                $VacationData['Vacation']['to_date'] = date('Y-m-d H:i:s', strtotime($this->request->data['Vacation']['to_date']));

                $query = $this->Vacation->query( 
                	"select * from vacations where user_id = ".$this->Session->read('Auth.User.id')." and   
				    (
				        ( '".$VacationData['Vacation']['from_date']."' between from_date and to_date  ) or
				        ('".$VacationData['Vacation']['to_date']."' between from_date and to_date  ) 
				        
				    )"
            	);
                //pr($query); die;
                if( empty($query) ){
                	$this->Vacation->create();
	                $this->Vacation->save($VacationData);
	                $this->Session->setFlash('Vacation Date has been added.', 'default', array('class' => 'success'));
	                $this->render('/Users/barbervacation');
					$respons = array('error' => 0, 'msg' => 'Vacation Date has been added.');
                }else{
                	$respons = array('error' => 1, 'msg' => 'Vacation slot is already selected.');
                }

                
            } else {
				$respons = array('error' => 1, 'msg' => 'Vacation Date has been not added. Please, try again.');
            }
        } else {
            $respons = array('error' => 1, 'msg' => 'Enter valid fields.');
        }
		
		echo json_encode($respons);
        exit;
    }
	
	public function user_barber_delete_vacation($id = null){
		$this->Vacation->id = $id;
		
		if (!$this->Vacation->exists()) {
            $respons = array('error' => 1, 'msg' => 'Invalid vacation data.');
        }
		$this->request->allowMethod('post', 'delete');
		if ($this->Vacation->delete()) {
			$respons = array('error' => 0, 'msg' => 'Vacation deleted successfully.');
		}
		echo json_encode($respons);
        exit;
	}
	
	public function edit_user_barber_add_vacation() {
		if ($this->request->is('post')) {
			$vacation_id = $this->request->data['vacation_id'];
			if (!empty($this->request->data['vacation_from']) && !empty($this->request->data['vacation_to'])) {
				$VacationData['from_date'] = "'".date("Y-m-d H:i:s",strtotime($this->request->data['vacation_from']))."'";
				$VacationData['to_date'] = "'".date("Y-m-d H:i:s",strtotime($this->request->data['vacation_to']))."'";

				if( $VacationData['from_date'] >= $VacationData['to_date'] ){
					$respons = array('error' => 1, 'msg' => 'From Date should be less then until date.');
				}else{
					 $query = $this->Vacation->query( 
		                	"select * from vacations where id != ".$vacation_id." and user_id = ".$this->Session->read('Auth.User.id')." and   
						    (
						        ( ".$VacationData['from_date']." between from_date and to_date  ) or
						        ( ".$VacationData['to_date']." between from_date and to_date  ) 
						        
						    )"
		            	);

					 if( empty($query) ){
	                	$this->Vacation->updateAll($VacationData, array('Vacation.id' => $vacation_id));
                		$respons = array('error' => 0, 'msg' => 'Vacation Date has been updated.');	
	                }else{
	                	$respons = array('error' => 1, 'msg' => 'Vacation slot is already selected.');
	                }


					
				}

            } else {
				$respons = array('error' => 1, 'msg' => 'Vacation Date has been not updated. Please, try again.');
            }
        } else {
            $respons = array('error' => 1, 'msg' => 'Enter valid fields.');
        }
		
		echo json_encode($respons);
        exit;
    }
	
	//Change Appointment Status
	public function appointmentStatusForm(){
		$json = array();
		if ($this->request->is('AJAX')) {
			$appointment_id = $this->request->data['appointment_id'];
			$appointment_info = $this->Appointment->find('first', array('conditions' => array('Appointment.id' => $appointment_id)));
			if(!empty($appointment_info)){
				$json['status'] = $appointment_info['Appointment']['status'];
			}
		}
		echo json_encode($json);
		exit;
	}
	
	public function changeAppointmentStatus(){
		$json = array();
		if ($this->request->is('AJAX')) {
			$post = $this->request->data;
			$id = $post['reservationStatus']['id'];
			$status = $post['reservationStatus']['status'];
			$this->Appointment->updateAll(array('Appointment.status' => $status), array('Appointment.id' => $id));
			$json['status'] = $status;
			$json['id'] = $id;
		}
		echo json_encode($json);
		exit;
	}
	
	public function deleteReservationRecord(){
		$respons = array();
		if ($this->request->is('AJAX')) {
			$appointment_id = $this->request->data['appointment_id'];
			//$appointment_info = $this->Appointment->find('first', array('conditions' => array('Appointment.id' => $appointment_id)));
			
			$this->Appointment->id = $appointment_id;
		
			if (!$this->Appointment->exists()) {
				$respons = array('error' => 1, 'msg' => 'Invalid Appointment data.');
			}
			$this->request->allowMethod('post', 'delete');
			if ($this->Appointment->delete()) {
				$respons = array('error' => 0, 'msg' => 'Appointment deleted successfully.');
			}
		}
		echo json_encode($respons);
		exit;
	}
}
