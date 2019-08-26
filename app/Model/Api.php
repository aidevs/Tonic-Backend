<?php

class Api extends AppModel {

    public $name = 'Api';
    public $useTable = false;
    public $actsAs = array('Multivalidatable');
    var $validationSets = array(
        'login' => array(
            'email' => array(
                'notBlank' => array(
                    'rule' => 'notBlank',
                    'required' => true,
                    'message' => 'Please enter email address.',
                ),
                'email' => array(
                    'rule' => 'email',
                    'message' => 'Please provide a valid email address.',
                )
            ),
            'password' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter password.',
            )
        ),
        'forgot' => array(
            'email' => array(
                'notBlank' => array(
                    'rule' => 'notBlank',
                    'required' => true,
                    'message' => 'Please enter email address.',
                ),
                'email' => array(
                    'rule' => 'email',
                    'message' => 'Please provide a valid email address.',
                )
            ),
        ),
        'claim_reservation' => array(
            'user_id' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter user id.',
            ),
            'appointment_id' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter barber appointment id.',
            )
        ),
        'update_reservation_status' => array(
            'appointment_id' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter appointment.',
            ),
            'pin' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter barber pin.',
            ),
            'status' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter status.',
            ),
        ),
        'update_wakin_status' => array(
            'wakin_id' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter appointment.',
            ),
            'pin' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter barber pin.',
            ),
            'status' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter status.',
            ),
        ),
        'check_in'=>array(
            'name' => array(
                'rule' => 'notBlank',
                'required' => true,
                'message' => 'Please enter name.',
            ),
        )
    );

    function setError($error) {
        $errors = array();
        foreach ($error as $key => $value) {
            if (isset($value[0]))
                $errors[] = $value[0];
        }
        return implode(', ', $errors);
    }

}
