<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class PagesController extends AppController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('User', 'Appointment', 'WalkinAppointment', 'WalkinAppointmentBarber', 'Advertisement');
    var $helper = array('Common');

    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow(array('tv'));
    }

    /**
     * Displays a view
     *
     * @return void
     * @throws NotFoundException When the view file could not be found
     * 	or MissingViewException in debug mode.
     */
    public function tv() {
        if (!$this->request->is('ajax')) {
            $this->layout = 'tv';
            $this->set('title_for_layout', __($this->current_shop['User']['shop_name'] . ' TV', true));
        } else {
            $this->layout = false;
        }

        $slug = $this->params['slug'];
        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'fields' => array('name', 'id')), 'Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'id', 'parent_id', 'image')), 'Slot')), false);
        $reservations = $this->Appointment->find('all', array('conditions' => array('Appointment.status' => 0, 'Appointment.date' => date('Y-m-d'), 'Barber.parent_id' => $this->current_shop['User']['id']), 'order' => array('Slot.time_24' => 'asc')));


        $this->WalkinAppointment->bindModel(array('hasMany' => array('WalkinAppointmentBarber')));
        $this->WalkinAppointmentBarber->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'barber_id', 'fields' => array('User.image')))));
        $this->WalkinAppointment->recursive = 2;
        $walkins = $this->WalkinAppointment->find('all', array('conditions' => array('WalkinAppointment.created >='=>date('Y-m-d'),'WalkinAppointment.shop_id' => $this->current_shop['User']['id']), 'order' => array('WalkinAppointment.created' => 'asc')));
//           pr($reservations);die;
        $adminAddList = array();
        $shopAddList = array();
        $this->Advertisement->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'user_id'))));
        $AdminShop = $this->Advertisement->find('all', array('conditions' => array('User.role_id' => 1), 'fields' => array('image')));
        if (!empty($AdminShop)) {
            foreach ($AdminShop as $value) {

                if ($value['Advertisement']['image']!= "") {
                    if (file_exists(WWW_ROOT . 'uploads' . DS . 'advertisement' . DS .$value['Advertisement']['image'])) {
                        array_push($adminAddList, SITE_FULL_URL . 'uploads/advertisement/'.$value['Advertisement']['image']);
                    }
                }
            }
        }
        $currentShopAD = $this->Advertisement->find('all', array('conditions' => array('Advertisement.user_id' => $this->current_shop['User']['id']), 'fields' => array('image')));
        if (!empty($currentShopAD)) {
            foreach ($currentShopAD as $value) { 
                 if ($value['Advertisement']['image']!= "") {
                    if (file_exists(WWW_ROOT . 'uploads' . DS . 'advertisement' . DS .$value['Advertisement']['image'])) {
                        array_push($shopAddList, SITE_FULL_URL . 'uploads/advertisement/'.$value['Advertisement']['image']);
                    }
                }
            }
        } 
       
        $this->set(compact('walkins', 'reservations', 'shopAddList', 'adminAddList'));
    }

}
