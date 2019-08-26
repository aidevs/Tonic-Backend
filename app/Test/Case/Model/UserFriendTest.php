<?php
App::uses('UserFriend', 'Model');

/**
 * UserFriend Test Case
 */
class UserFriendTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'app.user_friend',
		'app.user',
		'app.role',
		'app.user_detail',
		'app.university',
		'app.user_class',
		'app.friend'
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->UserFriend = ClassRegistry::init('UserFriend');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->UserFriend);

		parent::tearDown();
	}

}
