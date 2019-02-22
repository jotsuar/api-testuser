<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

	/**
	 * Get information user 
	 * @param  [int] $id  User id
	 * @return [method]   [Method response api]
	 */
	public function get($id) {
		if($this->request->is('get')) {
			$conditions = array('User.id' => $id);
			$user = $this->User->find('first', compact('conditions'));
			if(!empty($user)) {
				$user   = $this->clearFields($user);
				$result = $this->getResult($user);

				$this->log(__CLASS__.':'.__FUNCTION__.':'.__LINE__." result",'debug');
				$this->log($result,'debug');
				
				return $this->out($result, $user["User"], 'User');
			}else {
				$error = $this->getValidationErrors(array("NOT_EXISTS" => array("0008")));
				return $this->outFalseResponse($error);
			}
		}
		return $this->outFalseResponse();
	}

	/**
	 * Get all users
	 * @return [method]   [Method response api]
	 */
	public function all(){
		if($this->request->is('get')) {
			$users = $this->User->find('all');
			if(!empty($users)) {
				$users     = $this->clearFields($users);
				$result    = $this->getResult($users);
				return $this->out($result, $users, 'Users');
			}else {
				$error = $this->getValidationErrors(array("NOT_RECORDS" => array("0010")));
				return $this->outFalseResponse($error);
			}
		}
		return $this->outFalseResponse();
	}

	/**
	 * Add user to database
	 * @return [method]   [Method response api]
	 */
	public function add() {
		if($this->request->is('post') && !empty($this->request->data)) {
			$this->__validateRequest();
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$user       = $this->User->findById($this->User->id);
				$user   	= $this->clearFields($user);
				$result 	= $this->getResult($user);
				return $this->out($result, $user, 'Response');					
			} else {
				$error = $this->getValidationErrors($this->User->validationErrors);
				return $this->outFalseResponse($error);
			}
		}
		return $this->outFalseResponse();
	}

	/**
	 * Edit user to database
	 * @return [method]   [Method response api]
	 */
	public function edit() {
		if (($this->request->is('put') || $this->request->is('post') ) && !empty($this->request->data)) {
			$users_exists = $this->__validateUser();
			if (is_array($users_exists)) {
				return $this->outFalseResponse($users_exists);
			}
			$this->__validateRequest();
			$userSaved = $this->User->save($this->request->data);
			if ($userSaved) {
				$user   = $this->User->findById($this->User->id);
				$user   = $this->clearFields($user);
				$result = $this->getResult($user);
				return $this->out($result, $user,'Response');
			} else {
				$error = $this->getValidationErrors($this->User->validationErrors);
				return $this->outFalseResponse($error);
			}
		}
		return $this->outFalseResponse();
	}

	/**
	 * Delete user from database
	 * @return [method]   [Method response api]
	 */
	public function delete() {

		if (($this->request->is('delete') || $this->request->is('post') ) && !empty($this->request->data)) {
			$users_exists = $this->__validateUser();
			if (is_array($users_exists)) {
				return $this->outFalseResponse($users_exists);
			}
			$this->User->id = $this->request->data["id"];
			$userSaved      = $this->User->delete();
			if ($userSaved) {
				$userSaved = $this->getResult($userSaved);
				return $this->out($userSaved, true,'Deleted');
			} else {
				$error = $this->getValidationErrors($this->User->validationErrors);
				return $this->outFalseResponse($error);
			}
		}
		return $this->outFalseResponse();
	}

	/**
	 * Validate that inside the data of the request the image is found if not, the validation of the model is omitted
	 * @return void
	 */
	private function __validateRequest(){
		if (!empty($this->request->form["Image"])) {
			$this->request->data["Image"] = $this->request->form["Image"];
		}else{
			if (empty($this->request->data["Image"])) {
				unset($this->User->validate["Image"]);
			}
		}
	}

	/**
	 * In case the user id exists, it returns true if it does not exist or has not been sent, it returns the error
	 * @return [bolean or array]   [Method response api]
	 */
	private function __validateUser(){
		if (!empty($this->request->data["id"])) {
			$user = $this->User->findById($this->request->data["id"]);
			if (empty($user)) {
				return $error = $this->getValidationErrors(array("NOT_EXISTS" => array("0008")));
			}
		}else{
			return $error = $this->getValidationErrors(array("REQUIRED_ID" => array("0011")));

		}
		return true;
	}



}
