<?php
class Walkin extends AppModel {

    public $name = 'Walkin';
    public $belongsTo = array('User'=>array('foreignKey'=>'barber_id'));
    

    public function beforeSave($options = array()) {
            $this->data['Walkin']['time'] = date('h:i A');
            $this->data['Walkin']['date'] = date('Y-m-d');            
        return true;
    }
    
}
