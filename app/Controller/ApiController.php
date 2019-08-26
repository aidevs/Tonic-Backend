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
    public $uses = array('User', 'Appointment', 'Slot', 'Schedule', 'Api', 'SystemMail', 'WalkinAppointment', 'WalkinAppointmentBarber', 'Walkin');

    /**
     * Components
     *
     * @var array
     */
    public $components = array('Common');
    public $thumbHeight = 100;
    public $thumbWidth = 100;

    public function beforeFilter() {

        /*
          $fp = fopen(Configure::read('Site.docroot').'log/'.date('d-m-y').'log.txt', "a");
          fwrite($fp,"\r\n".$this->params['controller'].'Action========='.$this->params['action'].' Data==='.json_encode($this->Rest->_request));
          fclose($fp); */


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

        $postMethod = array('login', 'forgot_password', 'claim_reservation', 'update_reservation_status', 'update_wakin_status', 'check_in');
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
                $response = array('data' => array('msg' => 'Invalid Email or password.\nPlease Try Again.'));
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

        //pr($reservations);
        //die;
        $reservations = array_map(function($elem) {
            $elem['Appointment']['created'] = strtotime($elem['Appointment']['created']);
            $elem['Slot']['created'] = strtotime($elem['Slot']['created']);

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

    /*
     * GET walking user  list 
     */

    public function walkins() {

        $this->WalkinAppointment->bindModel(array('hasMany' => array('WalkinAppointmentBarber')));
        $this->WalkinAppointmentBarber->bindModel(array('belongsTo' => array('Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'id', 'parent_id', 'image')))));

        $this->WalkinAppointment->recursive = 2;


        $walkins = $this->WalkinAppointment->find('all', array('conditions' => array('WalkinAppointment.shop_id' => $this->Auth->user('id')), 'order' => array('WalkinAppointment.created' => 'asc')));



        $walkins = array_map(function($elem) {


            $elem['Customer'] = $elem['WalkinAppointment'];
            unset($elem['Customer']['created']);
            unset($elem['Customer']['updated']);
            $elem['Customer']['created'] = strtotime($elem['WalkinAppointment']['created']);
            $elem['Customer']['updated'] = strtotime($elem['WalkinAppointment']['created']);

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
        //pr($walkins);
        //   die;
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

    public function reservation_walkins() {
        //$today = date('Y-m-d');
        $this->WalkinAppointment->bindModel(array('hasMany' => array('WalkinAppointmentBarber')));

        $this->WalkinAppointmentBarber->bindModel(array('belongsTo' => array('Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'id', 'parent_id', 'image')), "Slot" => array('foreignKey' => 'slot_id'))));

        $this->WalkinAppointment->recursive = 2;

        $walkins = $this->WalkinAppointment->find('all', array('conditions' => array('WalkinAppointment.shop_id' => $this->Auth->user('id'), 'DATE_FORMAT(WalkinAppointment.created,"%Y-%m-%d") ' => date('Y-m-d')), 'order' => array('WalkinAppointment.created' => 'asc')));

        /* pr($walkins);
          die; */


        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id', 'fields' => array('name', 'id')), 'Barber' => array('foreignKey' => 'barber_id', 'className' => 'User', 'fields' => array('name', 'id', 'parent_id', 'image')), 'Slot')), false);


        $reservations = $this->Appointment->find('all', array('conditions' => array('Barber.parent_id' => $this->Auth->user('id'), 'Appointment.date' => date('Y-m-d'), 'Appointment.status' => 0), 'order' => array('Slot.time_24' => 'asc')));


        /* echo '<pre>';
          print_r( $reservations);
          die; */
        //  $appointmentdate =  $reservations['Appointment']['date'].' '.$reservations['Slot']['time'];
        // $slottime =        $reservations['Slot']['time'];

        $reservations = array_map(function($elem) {
            $elem['Appointment']['created'] = strtotime($elem['Appointment']['created']);
            $elem['Slot']['created'] = strtotime($elem['Slot']['created']);
            $elem['Slot']['solot_unixtime'] = strtotime($elem['Appointment']['date'] . ' ' . $elem['Slot']['time']);

            $elem['Barber']['image'] = $this->Common->getUserImage($elem['Barber']['image'], $this->thumbWidth, $this->thumbHeight, 1, 'front');
            return $elem;
        }, $reservations);



        $walkins = array_map(function($elem) {


            $elem['Customer'] = $elem['WalkinAppointment'];
            unset($elem['Customer']['created']);
            unset($elem['Customer']['updated']);
            $elem['Customer']['created'] = strtotime($elem['WalkinAppointment']['created']);
            $elem['Customer']['updated'] = strtotime($elem['WalkinAppointment']['updated']);

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
        // pr($walkins);
        // die;
        // $test = array_merge($walkinsold, $reservations);
        // pr($walkins);
        // die;

        $countdata = array();
        $countdata['walking_count'] = count($walkins);
        $countdata['reservation_count'] = count($reservations);
        if (!empty($walkins) || !empty($reservations)) {
            $response = array('data' => array('CountData' => $countdata, 'walkins' => $walkins, "reservations" => $reservations));
            $this->response->statusCode(200);
        } else {
            $response = array('data' => array('msg' => 'No Data Found.'));
            $this->response->statusCode(206);
        }
        $this->response->body(json_encode($response));
        $this->response->type('json');
        $this->response->send();
        exit;
    }

    public function barbers() {
        $this->User->recursive = -1;//'User.status'=>1,
        $barbers = $this->User->find('all', array('conditions' => array('User.deleted'=>0,'User.role_id' => 3, 'User.parent_id' => $this->Auth->user('id')), 'fields' => array('User.id', 'User.name', 'User.image')));
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

        $this->Appointment->bindModel(array('belongsTo' => array('User' => array('foreignKey' => 'customer_id'), 'Barber' => array('foreignKey' => 'barber_id', 'className' => 'User'), "Slot" => array('foreignKey' => 'slot_id'))), false);

        // echo $this->Auth->user('id');

        $reservations = $this->Appointment->find('all', array('conditions' => array('Barber.parent_id' => $this->Auth->user('id'), 'Appointment.status' => 0, 'Appointment.claim_status' => 0, 'Appointment.date' => date('Y-m-d')), 'order' => array('Slot.time_24' => 'asc'), 'fields' => array('Appointment.id', 'User.id', 'User.name', 'User.image')));

        /* echo '<pre>';
          print_r($reservations);
          die; */
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
            $barber = $this->User->findByPinAndParentId($data['pin'], $this->Auth->user('id'));

            if (empty($barber)) {
                $response = array('data' => array('msg' => 'Invalid pin.'));
                $this->response->statusCode(206);
            } else {
                $barber_exists = $this->Appointment->findByIdAndBarberId($data['appointment_id'], $barber['User']['id']);
                if (!empty($barber_exists) && $this->Appointment->updateAll(array('Appointment.status' => $data['status']), array('Appointment.id' => $data['appointment_id'], 'Appointment.barber_id' => $barber['User']['id']))) {
                    if ($data['status'] == 1) {
                        $res = $this->Appointment->findById($data['appointment_id']);

                        $admin = $this->User->findById($barber['User']['parent_id']);
                        $customer = $this->User->findById($res['Appointment']['customer_id']);
                        $url = $admin['User']['review_url'];
                        $acturl = "<a href='$url'>Click Here</a> and $url";
                        $site_url = SITE_FULL_URL;
                        $site_url = "<a href='$site_url'>$site_url</a>";
                        $shop_url=SITE_FULL_URL . $admin['User']['shop_slug'];
                        $shop_name= ucfirst($admin['User']['shop_name']);
                        $mail = $this->SystemMail->find('first', array('conditions' => array('SystemMail.emailType' => 'Feedbacks')));
                        $mail['SystemMail']['message'] = str_replace('[sitename]', Configure::read('Site.title'), $mail['SystemMail']['message']);
                        $mail['SystemMail']['message'] = str_replace('[username]', ucfirst($customer['User']['name']), $mail['SystemMail']['message']);

                        $mail['SystemMail']['message'] = str_replace('[LINK]', $url, $mail['SystemMail']['message']);
                        $mail['SystemMail']['message'] = str_replace('[SHOP_LINK]', $shop_url, $mail['SystemMail']['message']);
                        $mail['SystemMail']['message'] = str_replace('[SHOP_NAME]', $shop_name, $mail['SystemMail']['message']);
                        $mail['SystemMail']['message'] = str_replace('[site_url]', SITE_FULL_URL, $mail['SystemMail']['message']);
                        $this->Email->to = $customer['User']['email'];

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
                    $response = array('data' => array('msg' => 'Reservation update successfully.'));
                    $this->response->statusCode(200);
                } else {
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

    public function delete_reservation_status() {
        $data = $this->request->input('json_decode', true);
        $this->Api->setValidation('delete_reservation_status');
        $this->Api->set($data);
        if ($this->Api->validates()) {
            $barber = $this->User->findByParentId($this->Auth->user('id'));

            if (empty($barber)) {
                $response = array('data' => array('msg' => 'Invalid barber.'));
                $this->response->statusCode(206);
            } else {
                $barber_exists = $this->Appointment->findByIdAndBarberId($data['appointment_id'], $barber['User']['id']);
                if (!empty($barber_exists) && $this->Appointment->updateAll(array('Appointment.status' => $data['status']), array('Appointment.id' => $data['appointment_id'], 'Appointment.barber_id' => $barber['User']['id']))) {

                    $response = array('data' => array('msg' => 'Reservation update successfully.'));
                    $this->response->statusCode(200);
                } else {
                    $response = array('data' => array('msg' => 'Invalid barber.'));
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
            $barber = $this->User->findByPinAndParentId($data['pin'], $this->Auth->user('id'));
            //pr($barber);
            if (empty($barber)) {
                $response = array('data' => array('msg' => 'Invalid pin.'));
                $this->response->statusCode(206);
            } else {
                if (!empty($data)) {

                    //echo $data['wakin_id'];
                    // echo $this->Auth->user('id');

                    $wakinsApp = $this->WalkinAppointment->findByIdAndShopId($data['wakin_id'], $this->Auth->user('id'));

                    if (empty($wakinsApp)) {
                        $response = array('data' => array('msg' => 'Invalid walking appointment id.'));
                        $this->response->statusCode(206);
                    } else {
                        $wakin['Walkin']['barber_id'] = $barber['User']['id'];
                        $wakin['Walkin']['name'] = $wakinsApp['WalkinAppointment']['name'];
                        $wakin['Walkin']['time'] = date('h:i A');
                        $wakin['Walkin']['date'] = date('Y-m-d');
                        $wakin['Walkin']['status'] = ($data['status']==1)?$data['status']:0;
                        $this->Walkin->save($wakin);
                        $this->WalkinAppointmentBarber->deleteAll(array('WalkinAppointmentBarber.walkin_appointment_id' => $data['wakin_id']));
                        $this->WalkinAppointment->delete($data['wakin_id']);
                        $response = array('data' => array('msg' => 'Reservation update successfully.'));
                        $this->response->statusCode(200);
                    }
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

    public function delete_wakin_status() {
        $data = $this->request->input('json_decode', true);
        $this->Api->setValidation('delete_wakin_status');
        $this->Api->set($data);
        if ($this->Api->validates()) {
            $barber = $this->User->findByParentId($this->Auth->user('id'));
            //pr($barber);
            if (empty($barber)) {
                $response = array('data' => array('msg' => 'Invalid barber.'));
                $this->response->statusCode(206);
            } else {
                if ($data['status'] == 1) {

                    //echo $data['wakin_id'];
                    // echo $this->Auth->user('id');

                    $wakinsApp = $this->WalkinAppointment->findByIdAndShopId($data['wakin_id'], $this->Auth->user('id'));

                    if (empty($wakinsApp)) {
                        $response = array('data' => array('msg' => 'Invalid walking appointment id.'));
                        $this->response->statusCode(206);
                    } else {
                        $wakin['Walkin']['barber_id'] = $barber['User']['id'];
                        $wakin['Walkin']['name'] = $wakinsApp['WalkinAppointment']['name'];
                        $wakin['Walkin']['time'] = date('h:i A');
                        $wakin['Walkin']['date'] = date('Y-m-d');
                        $this->Walkin->save($wakin);
                        $this->WalkinAppointmentBarber->deleteAll(array('WalkinAppointmentBarber.walkin_appointment_id' => $data['wakin_id']));
                        $this->WalkinAppointment->delete($data['wakin_id']);
                        $response = array('data' => array('msg' => 'Reservation update successfully.'));
                        $this->response->statusCode(200);
                    }
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

    public function check_in_edit() {
        $data = $this->request->input('json_decode', true);
        $this->Api->setValidation('check_in_edit');
        $this->Api->set($data);

        $fp = fopen(Configure::read('Site.docroot') . 'log/' . date('d-m-y') . 'log.txt', "a");
        fwrite($fp, "\r\n" . $this->params['controller'] . 'Action=========' . $this->params['action'] . ' Data===' . json_encode($data));
        fclose($fp);



        if ($this->Api->validates()) {
            if ((isset($data['barbers']) && !empty($data['barbers'])) && (isset($data['id']) && !empty($data['id'])) && (isset($data['next_available']) && $data['next_available'] == 0)) {
                if (count($data['barbers']) > 5) {
                    $response = array('data' => array('msg' => 'You can select five barbers only.'));
                    $this->response->statusCode(206);
                } else {

                    $walkAppointmentDate = date("y-m-d H:i:s", $data['checkin_date']);

                    $walkin_appointment['WalkinAppointment']['id'] = $data['id'];
                    $walkin_appointment['WalkinAppointment']['name'] = $data['name'];
                    $walkin_appointment['WalkinAppointment']['next_available'] = 0;
                    $walkin_appointment['WalkinAppointment']['shop_id'] = $this->Auth->user('id');
                    $walkin_appointment['WalkinAppointment']['updated'] = $walkAppointmentDate;

                    if ($result = $this->WalkinAppointment->save($walkin_appointment)) {
                        //$walkAppointmentDate = $this->WalkinAppointment->findById($data['id']);
                        if ($this->WalkinAppointmentBarber->deleteAll(array('WalkinAppointmentBarber.walkin_appointment_id' => $data['id']), false)) {
                            $walkin_appointment_barber['WalkinAppointmentBarber']['walkin_appointment_id'] = $data['id'];
                            $walkin_appointment_barber['WalkinAppointmentBarber']['created'] = $walkAppointmentDate;

                            foreach ($data['barbers'] as $value) {
                                $this->WalkinAppointmentBarber->create();
                                $walkin_appointment_barber['WalkinAppointmentBarber']['barber_id'] = $value;
                                $this->WalkinAppointmentBarber->save($walkin_appointment_barber);
                            }
                            $response = array('data' => array('msg' => 'Check-In edit successfully.'));
                            $this->response->statusCode(200);
                        }
                    }
                }
            } else if ((isset($data['next_available']) && $data['next_available'] == 1) && (isset($data['id']) && !empty($data['id']))) {
                $walkAppointmentDate = date("y-m-d H:i:s", $data['checkin_date']);

                $walkin_appointment['WalkinAppointment']['id'] = $data['id'];
                $walkin_appointment['WalkinAppointment']['name'] = $data['name'];
                $walkin_appointment['WalkinAppointment']['shop_id'] = $this->Auth->user('id');
                $walkin_appointment['WalkinAppointment']['next_available'] = 1;
                $walkin_appointment['WalkinAppointment']['updated'] = $walkAppointmentDate;

                $this->WalkinAppointment->save($walkin_appointment);
                $this->WalkinAppointmentBarber->deleteAll(array('WalkinAppointmentBarber.walkin_appointment_id' => $data['id']), false);
                $response = array('data' => array('msg' => 'Check-In edit successfully.'));
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
