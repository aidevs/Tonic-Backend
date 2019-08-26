<?php

App::uses('AppController', 'Controller');

/**
 * Api Controller
 *
 * @property User $User
 * @property PaginatorComponent $Paginator
 */
class ApiController extends AppController {

    public $name = 'Api';

    /**
     *
     * Model
     *  
     */
    public $uses = array('User', 'Appointment', 'Slot', 'Schedule', 'Api', 'SystemMail', 'WalkinAppointment', 'WalkinAppointmentBarber','Walkin');

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Common');
    public $thumbHeight = 100;
    public $thumbWidth = 100;

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->authenticate = array(
            'Form' => array(
                'fields' => array('username' => 'email'),
                'scope' => array('User.role_id' => 2)
            )
        );
        $this->Auth->allow(array('login', 'forgot_password'));
        $this->layout = false;
        $this->render(false);
        $this->response->disableCache();

        $postMethod = array('login', 'forgot_password', 'claim_reservation', 'update_reservation_status','update_wakin_status','check_in');
        if (!$this->request->is('post') && in_array($this->params['action'], $postMethod)) {
            $this->response->statusCode(405);
            $this->response->send();
            exit;
        }
        if ($this->request->header('token') != '') {
            $this->User->recursive = -1;
            $curentUser = $this->User->findByToken($this->request->header('token'));
            if (!empty($curentUser)) {
                if ($curentUser['User']['status'] != 1) {
                    $response = array('data' => array('msg' => 'Account deactivated by admin.'));
                    $this->response->statusCode(406);
                    $this->response->body(json_encode($response));
                    $this->response->send();
                    exit;
                }
                $this->Auth->login($curentUser['User']);
            } else {
                $response = array('data' => array('msg' => 'Invalid login.'));
                $this->response->statusCode(406);
                $this->response->body(json_encode($response));
                $this->response->send();
                exit;
            }
        } else {
            if (!in_array($this->params['action'], $this->Auth->allowedActions)) {
                $response = array('data' => array('msg' => 'Token not found in request headers.'));
                $this->response->statusCode(206);
                $this->response->body(json_encode($response));
                $this->response->send();
                exit;
            }
        }
    }

    public function login() {
        $data = $this->request->input('json_decode', true);
        $this->Api->setValidation('login');
        $this->Api->set($data);
        $response = array();
        if ($this->Api->validates()) {
            $this->request->data['User'] = $data;
            $this->Auth->logout();
            if ($this->Auth->login()) {
                if ($this->Auth->user('status') != 1) {
                    $response = array('data' => array('msg' => 'Account deactivated by admin.'));
                    $this->response->statusCode(406);
                    $this->response->body(json_encode($response));
                    $this->response->send();
                    exit;
                }
                $apiLogin['User']['id'] = $this->Auth->user('id');
                $apiLogin['User']['token'] = md5(uniqid());
                if ($result = $this->User->save($apiLogin)) {
                    $response = array('data' => array('user_id' => $result['User']['id'], 'token' => $result['User']['token']));
                    $this->response->statusCode(200);
                }
            } else {
                $response = array('data' => array('msg' => 'Email and password invalid.'));
                $this->response->statusCode(206);
            }
        } else {
            $error = $this->Api->setError($this->Api->validationErrors);
            $response = array('data' => array('msg' => $error));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }

    public function logout() {
        $userLogout['User']['id'] = $this->Auth->user('id');
        $userLogout['User']['token'] = '';
        if ($this->User->save($userLogout)) {
            $this->Auth->logout();
            $response = array('data' => array('msg' => 'Logout successfully.'));
            $this->response->statusCode(200);
        } else {
            $response = array('data' => array('msg' => 'Error while logout.'));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->send();
        exit;
    }

    public function forgot_password() {
        $data = $this->request->input('json_decode', true);
        $this->Api->setValidation('forgot');
        $this->Api->set($data);
        if ($this->Api->validates()) {
            $user = $this->User->findByEmail($data['email']);
            if (!empty($user)) {
                $this->User->id = $user['User']['id'];
                $activationKey = md5(uniqid());
                $this->User->saveField('activation_key', $activationKey);
                $url1 = SITE_FULL_URL . 'admin/users/reset_password/' . $activationKey;
                $url = "<a href='$url1'>Click Here</a> and $url1";
                $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'ForgotPassword')));
                $mail['SystemMail']['message'] = str_replace('[fullname]', $user['User']['name'], $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[link]', $url, $mail['SystemMail']['message']);
                $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                $this->Email->to = $user['User']['email'];
                $this->Email->from = $mail['SystemMail']['senderName'] . '<' . $mail['SystemMail']['senderEmail'] . '>';
                $this->Email->subject = $mail['SystemMail']['subject'];
                $this->Email->sendAs = 'html';
                $this->Email->template = 'default';
                $this->set('message', $mail['SystemMail']['message']);
                $this->set('title', $mail['SystemMail']['subject']);
                if ($this->Email->send()) {
                    $response = array('data' => array('msg' => 'An email has been sent with instructions for resetting your password.'));
                    $this->response->statusCode(200);
                }
            } else {
                $response = array('data' => array('msg' => 'No user was found with the submitted email.'));
                $this->response->statusCode(206);
            }
        } else {
            $error = $this->Api->setError($this->Api->validationErrors);
            $response = array('data' => array('msg' => $error));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }

    public function reservations() {
        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'fields' => array('name', 'id')), 'Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'id', 'parent_id', 'image')), 'Slot')), false);
        $reservations = $this->Appointment->find('all', array('conditions' => array('Barber.parent_id' => $this->Auth->user('id'), 'Appointment.status' => 0), 'order' => array('Slot.time' => 'asc')));

        $reservations = array_map(function($elem) {
            $elem['Barber']['image'] = $this->Common->getUserImage($elem['Barber']['image'], $this->thumbWidth, $this->thumbHeight, 1, 'front');
            return $elem;
        }, $reservations);
        if (!empty($reservations)) {
            $response = array('data' => array('reservations' => $reservations));
            $this->response->statusCode(200);
        } else {
            $response = array('data' => array('msg' => 'No reservations.'));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }

    public function walkins() {
        $this->WalkinAppointment->bindModel(array('hasMany' => array('WalkinAppointmentBarber')));
        $this->WalkinAppointmentBarber->bindModel(array('belongsTo' => array('Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'id', 'parent_id', 'image')))));
        $this->WalkinAppointment->recursive = 2;
        $walkins = $this->WalkinAppointment->find('all', array('conditions' => array('WalkinAppointment.shop_id' => $this->Auth->user('id')), 'order' => array('WalkinAppointment.created' => 'asc')));
        $walkins = array_map(function($elem) {
            $elem['Customer'] = $elem['WalkinAppointment'];
            $elem['Barber'] = array_map(function($subElem) {
                $subElem['image'] = $this->Common->getUserImage($subElem['Barber']['image'], $this->thumbWidth, $this->thumbHeight, 1, 'front');
                $subElem['name'] = $subElem['Barber']['name'];
                $subElem['id'] = $subElem['Barber']['id'];
                $subElem['walkin_id'] = $subElem['walkin_appointment_id'];
                unset($subElem['Barber']);
                unset($subElem['walkin_appointment_id']);
                unset($subElem['barber_id']);
                unset($subElem['created']);
                return $subElem;
            }, $elem['WalkinAppointmentBarber']);
            unset($elem['WalkinAppointmentBarber']);
            unset($elem['WalkinAppointment']);
            return $elem;
        }, $walkins);
        if (!empty($walkins)) {
            $response = array('data' => array('walkins' => $walkins));
            $this->response->statusCode(200);
        } else {
            $response = array('data' => array('msg' => 'No walkins.'));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }

    public function barbers() {
        $this->User->recursive = -1;
        $barbers = $this->User->find('all', array('conditions' => array('User.role_id' => 3, 'User.parent_id' => $this->Auth->user('id')), 'fields' => array('User.id', 'User.name', 'User.image')));
        $barbers = array_map(function($elem) {
            $elem['User']['image'] = $this->Common->getUserImage($elem['User']['image'], $this->thumbWidth, $this->thumbHeight, 1, 'front');
            return $elem;
        }, $barbers);
        if (!empty($barbers)) {
            $response = array('data' => array('barbers' => $barbers));
            $this->response->statusCode(200);
        } else {
            $response = array('data' => array('msg' => 'No barber.'));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }

    public function customers() {
        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id'), 'Barber' => array('foreignKey' => 'barber_id', 'className' => 'User'))), false);
        $reservations = $this->Appointment->find('all', array('conditions' => array('Barber.parent_id' => $this->Auth->user('id'), 'Appointment.status' => 0, 'Appointment.claim_status' => 0), 'fields' => array('Appointment.id', 'User.id', 'User.name', 'User.image')));

        $reservations = array_map(function($elem) {
            $elem['User']['image'] = $this->Common->getUserImage($elem['User']['image'], $this->thumbWidth, $this->thumbHeight, 1, 'front');
            $elem['User']['appointment_id'] = $elem['Appointment']['id'];
            unset($elem['Appointment']);
            return $elem;
        }, $reservations);

        if (!empty($reservations)) {
            $response = array('data' => array('customers' => $reservations));
            $this->response->statusCode(200);
        } else {
            $response = array('data' => array('msg' => 'No barber.'));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }

    public function claim_reservation() {
        $data = $this->request->input('json_decode', true);
        $this->Api->setValidation('claim_reservation');
        $this->Api->set($data);
        if ($this->Api->validates()) {
            $this->Appointment->updateAll(array('Appointment.claim_status' => 1), array('Appointment.id' => $data['appointment_id'], 'Appointment.customer_id' => $data['user_id']));

            $response = array('data' => array('msg' => 'Claim reservation successfully.'));
            $this->response->statusCode(200);
        } else {
            $response = array('data' => array('msg' => 'Missing important data for request.'));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }

    public function update_reservation_status() {
        $data = $this->request->input('json_decode', true);
        $this->Api->setValidation('update_reservation_status');
        $this->Api->set($data);
        if ($this->Api->validates()) {
            $barber = $this->User->findByPinAndParentId($data['pin'],$this->Auth->user('id'));
            $barber_exists = $this->Appointment->findByIdAndBarberId($data['appointment_id'],$barber['User']['id']);
            if (empty($barber)) {
                $response = array('data' => array('msg' => 'Invalid pin.'));
                $this->response->statusCode(206);
            } else {
                if(!empty($barber_exists) && $this->Appointment->updateAll(array('Appointment.status' => $data['status']), array('Appointment.id' => $data['appointment_id'], 'Appointment.barber_id' => $barber['User']['id']))){

                $response = array('data' => array('msg' => 'Reservation update successfully.'));
                $this->response->statusCode(200);
                }else{
                    $response = array('data' => array('msg' => 'Invalid pin.'));
                    $this->response->statusCode(206);
                }
            }
        } else {
            $response = array('data' => array('msg' => 'Missing important data for request.'));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }
   public function update_wakin_status() {        
        $data = $this->request->input('json_decode', true);
        $this->Api->setValidation('update_wakin_status');
        $this->Api->set($data);
        if ($this->Api->validates()) {
            $barber = $this->User->findByPinAndParentId($data['pin'],$this->Auth->user('id'));
            if (empty($barber)) {
                $response = array('data' => array('msg' => 'Invalid pin.'));
                $this->response->statusCode(206);
            } else {
                if($data['status']==1){
                $wakinsApp=$this->WalkinAppointment->findByIdAndShopId($data['wakin_id'],$this->Auth->user('id'));
                $wakin['Walkin']['barber_id']=$barber['User']['id'];
                $wakin['Walkin']['name']=$wakinsApp['WalkinAppointment']['name'];
                $wakin['Walkin']['time']=date('h:i A');
                $wakin['Walkin']['date']=date('Y-m-d');
                $this->Walkin->save($wakin);
                }
                $this->WalkinAppointmentBarber->deleteAll(array('WalkinAppointmentBarber.walkin_appointment_id'=>$data['wakin_id']));
                $this->WalkinAppointment->delete($data['wakin_id']);
                $response = array('data' => array('msg' => 'Reservation update successfully.'));
                $this->response->statusCode(200);
            }
        } else {
            $response = array('data' => array('msg' => 'Missing important data for request.'));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
   }
   public function check_in() {
        $data = $this->request->input('json_decode', true);
        $this->Api->setValidation('check_in');
        $this->Api->set($data);
        if ($this->Api->validates()) {
            if ((isset($data['barbers']) && !empty($data['barbers'])) && (isset($data['next_available']) && $data['next_available'] == 0)) {
                if (count($data['barbers']) > 5) {
                    $response = array('data' => array('msg' => 'You can select five barbers only.'));
                    $this->response->statusCode(206);
                } else {
                    $walkin_appointment['WalkinAppointment']['name'] = $data['name'];
                    $walkin_appointment['WalkinAppointment']['shop_id'] = $this->Auth->user('id');
                    if ($result = $this->WalkinAppointment->save($walkin_appointment)) {
                        $walkin_appointment_barber['WalkinAppointmentBarber']['walkin_appointment_id'] = $result['WalkinAppointment']['id'];
                        foreach ($data['barbers'] as $value) {
                            $this->WalkinAppointmentBarber->create();
                            $walkin_appointment_barber['WalkinAppointmentBarber']['barber_id'] = $value;
                            $this->WalkinAppointmentBarber->save($walkin_appointment_barber);
                        }
                        $response = array('data' => array('msg' => 'Check-In successfully.'));
                        $this->response->statusCode(200);
                    }
                }
            } else if (isset($data['next_available']) && $data['next_available'] == 1) {
                $walkin_appointment['WalkinAppointment']['name'] = $data['name'];
                $walkin_appointment['WalkinAppointment']['shop_id'] = $this->Auth->user('id');
                $walkin_appointment['WalkinAppointment']['next_available'] = 1;
                $this->WalkinAppointment->save($walkin_appointment);
                $response = array('data' => array('msg' => 'Check-In successfully.'));
                $this->response->statusCode(200);
            } else {
                $response = array('data' => array('msg' => 'Please select at least one barber.'));
                $this->response->statusCode(206);
            }
        } else {
            $error = $this->Api->setError($this->Api->validationErrors);
            $response = array('data' => array('msg' => $error));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }

}
