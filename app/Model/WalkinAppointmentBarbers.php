<?php
class WalkinAppointmentBarbers extends AppModel {

    public $name = 'WalkinAppointmentBarbers';
    public $belongsTo = array('WalkinAppointments'=>array('foreignKey'=>'walkin_appointment_id'),'User'=>array('foreignKey'=>'barber_id'));
    

}
