<?php
App::uses('UsersController', 'Controller');

/**
 * UsersController Test Case
 */
class UsersControllerTest extends ControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	// public $fixtures = array(
	// 	'app.user'
	// );

	/**
	 * testIndex method
	 *
	 * @return void
	 */
	public function testAll() {

		$result = $this->testAction('/users/all',array("method"=>"get","return" => "vars"));
		debug($result["Response"]);
		$this->markTestSkipped("Test users all complete");
	}

	/**
	 * testView method
	 *
	 * @return void
	 */
	public function testGet() {
		$user_id = 2;
		$result = $this->testAction("/users/get/{$user_id}",array("method"=>"get","return" => "vars"));
		debug($result["Response"]);
		$this->markTestSkipped("Test users get complete");
	}

	/**
	 * testAdd method
	 *
	 * @return void
	 */
	public function testAdd() {
		$data = array(
			"name"  => "New User",
			"email" => "user1@yopmail.com"
		);
		$result = $this->testAction('/users/add',array("method"=>"post","data" => $data,"return" => "vars"));
		debug($result["Response"]);
		$this->markTestSkipped("Test users add complete");
	}


}
