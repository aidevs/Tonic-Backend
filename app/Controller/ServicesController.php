<?php

App::uses('AppController', 'Controller');

/**
 * Services Controller
 *
 * @property Service $Service
 * @property PaginatorComponent $Paginator
 */
class ServicesController extends AppController {

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Paginator');
    public $uses = array('Service','BarberService');

    /**
     * Displays a view
     *
     * @param mixed What page to display
     * @return void
     */
    function beforeFilter() {
        parent::beforeFilter();
        if ($this->Auth->user('role_id') == 1) {
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }        
    }
    /**
     * index method
     *
     * @return void
     */
    public function admin_index() {
         $this->set('title_for_layout', 'Services');
         $this->set('total_service', $this->Service->find('count', array('condition' => array('Service.user_id' =>$this->Auth->user('id')))));
    }
    public function admin_list(){
        $this->layout = false;
        $name_con = $des_con = $time_con = $cost_con = $order=array();

        if (isset($this->request->data['name']) && $this->request->data['name'] != '') {
            $name_con = array('Service.name LIKE' => '%' . $this->request->data['name'] . '%');
        }
        if (isset($this->request->data['description']) && $this->request->data['description'] != '') {
            $des_con = array('Service.description LIKE' => '%' . $this->request->data['description'] . '%');
        }
        if (isset($this->request->data['time']) && $this->request->data['time'] != '') {
            $time_con = array('Service.time' => $this->request->data['time']);
        }
        if (isset($this->request->data['cost']) && $this->request->data['cost'] != '') {
            $cost_con = array('ROUND(Service.cost)' => $this->request->data['cost']);
        }
      
        switch ($this->request->data['order'][0]['column']) {
            case 0:
                $order = array('Service.name' => $this->request->data['order'][0]['dir']);
                break;
            case 1:
                $order = array('Service.description' => $this->request->data['order'][0]['dir']);
                break;
            case 2:
                $order = array('Service.time' => $this->request->data['order'][0]['dir']);
                break;
            case 3:
                $order = array('Service.cost' => $this->request->data['order'][0]['dir']);
                break;            
            case 4:
                $order = array('Service.status' => $this->request->data['order'][0]['dir']);
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

        $con = array_merge($name_con,$des_con,$time_con,$cost_con, array('Service.user_id' =>$this->Auth->user('id')));

        $this->paginate = array('conditions' => $con, 'limit' => $this->request->data['length'], 'order' => $order, 'page' => $page);
        $this->Service->recursive = 2;
        $this->set('services', $this->paginate('Service'));
    }

   

    /**
     * add method
     *
     * @return void
     */
    public function admin_add() {
        $this->set('title_for_layout', __('Add Service', true));
        if ($this->request->is('post')) {
            $this->Service->create();
            $this->request->data['Service']['user_id']=$this->Auth->user('id');
            if ($this->Service->save($this->request->data)) {
                $this->Session->setFlash(__('The service has been saved.'), 'default', array('class' => 'success'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The service could not be saved. Please, try again.'), 'default', array('class' => 'error'));
               
            }
        }
    }

    /**
     * edit method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_edit($id = null) {
        $this->set('title_for_layout', __('Edit Service', true));
        if (!$this->Service->findByIdAndUserId($id,$this->Auth->user('id'))) {            
             $this->Session->setFlash(__('Invalid service.'), 'default', array('class' => 'error'));
             return $this->redirect(array('action' => 'index'));
        }
        if ($this->request->is(array('post', 'put'))) {
            if(!isset($this->request->data['Service']['status'])){
               $this->request->data['Service']['status']=0; 
            }           
            if ($this->Service->save($this->request->data,array('deep'=>true))) {
                $this->Session->setFlash(__('The service has been saved.'), 'default', array('class' => 'success'));
                return $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The service could not be saved. Please, try again.'), 'default', array('class' => 'error'));
            }
        } else {
            $options = array('conditions' => array('Service.' . $this->Service->primaryKey => $id));
            $this->request->data = $this->Service->find('first', $options);
           
            
        }
       
    }

    /**
     * delete method
     *
     * @throws NotFoundException
     * @param string $id
     * @return void
     */
    public function admin_delete($id = null) {
        $this->Service->id = $id;
        if (!$this->Service->exists()) {
            throw new NotFoundException(__('Invalid service'));
        }
        $this->request->allowMethod('post', 'delete');
        if ($this->Service->delete()) {            
            $this->Session->setFlash(__('The service has been deleted.'), 'default', array('class' => 'success'));
        } else {
             $this->Session->setFlash(__('The service could not be deleted. Please, try again.'), 'default', array('class' => 'error'));
        }
        return $this->redirect(array('action' => 'index'));
    }

}
