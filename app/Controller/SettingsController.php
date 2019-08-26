<?php

/**
 * Settings Controller
 *
 * PHP version 5
 *
 * @category Controller
 * @package  Croogo
 * @version  1.0
 * @author   Fahad Ibnay Heylaal <contact@fahad19.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class SettingsController extends AppController {

    /**
     * Controller name
     *
     * @var string
     * @access public
     */
    public $name = 'Settings';

    /**
     * Models used by the Controller
     *
     * @var array
     * @access public
     */
    public $uses = array('Setting', 'User');

    /**
     * Helpers used by the Controller
     *
     * @var array
     * @access public
     */
    public $helpers = array('Html', 'Form');

    public function beforeFilter() {
        parent::beforeFilter();
        if ($this->Auth->user('role_id') != 1) {
            $this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
        }
    }

    public function admin_prefix($prefix = null) {
        $this->set('title_for_layout', sprintf(__('Settings')));

        if (!empty($this->request->data) && $this->Setting->saveAll($this->request->data['Setting'])) {
            $this->Session->setFlash(__("Settings updated successfully."), 'default', array('class' => 'success'));
        }
        $settings = $this->Setting->find('all', array(
            'order' => 'Setting.weight ASC',
            'conditions' => array(
                'Setting.key LIKE' => $prefix . '.%',
                'Setting.editable' => 1,
            ),
        ));
        $userdata = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id')
            ),
        ));
        //pr($userdata);
        $this->request->data = $userdata;
        $this->set(compact('settings'));

        if (count($settings) == 0) {
            $this->Session->setFlash(__("Invalid Setting key"), 'default', array('class' => 'error'));
        }

        $this->set("prefix", $prefix);
    }

    

}

?>