<?php
class WalkinAppointments extends AppModel {

    public $name = 'WalkinAppointments';
    public $belongsTo = array('User'=>array('foreignKey'=>'shop_id'));
    

}
