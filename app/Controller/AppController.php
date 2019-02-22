<?php

App::uses('Controller', 'Controller');

class AppController extends Controller {
  
	public $components = array('RequestHandler');

	/**
	 * Method where the headers and the output type of the api are configured
	 * @return [void]
	 */
	public function beforeFilter(){
		parent::beforeFilter();
		$this->response->header('Access-Control-Allow-Origin','*');
		$this->response->header('Access-Control-Allow-Headers','*');	
        $this->response->header('Access-Control-Allow-Methods','*');
        $this->response->header('Access-Control-Max-Age','172800');
		$this->RequestHandler->ext = 'json';
	}

	/**
	 * Response of all methods from API
	 * @param  [array] $result     	 [request result (true or false)]
	 * @param  [array] $values     	 [array of data response]
	 * @param  [String] @$reponseKey [Key of object to response]
	 * @return [array]               [request response]
	 */
	public function out($result, $values, $reponseKey=null) {
		$reponseKey = ($reponseKey != null) ? $reponseKey : $this->modelClass;
		$result[$reponseKey] = $values;

		$this->log(__CLASS__.':'.__FUNCTION__.':'.__LINE__." result",'debug');
		$this->log($result,'debug');
		
		$this->set([
		  "Response"   => $result,
		  '_serialize' => "Response"
		]);
	}

	/**
	 * Validate the existence of data to answer
	 * @param  [array] $values array of value generated
	 * @return [array]         arraay of request result (bolean)
	 */
	public function getResult($values = null) {
		if(empty($values)) {
			return array('result'=> false);
		} 
		return array('result'=> true);
	}

	/**
	 * Request error due to lack of data or invalid content type
	 * @return array      result error
	 */
	public function outFalseResponse($msg = null) {

		if (is_null($msg)) {
			$msg = array("msg" => "Remember that the information must be sent under the HTTP methods: GET, POST, PUT, DELETE and the Content-Type: application / x-www-form-urlencoded");
		}

		$result = array('result' => false);
		$result = array_merge($result, $msg);


		$this->log(__CLASS__.':'.__FUNCTION__.':'.__LINE__." result",'debug');
		$this->log($result,'debug');

		$this->set([
		  "Response"   => $result,
		  '_serialize' => "Response"
		]);
	}

	/*
    * Empty the fields, when the values ​​are null
    * en los lenguajes de programacion mobile que validan los tipos de datos
    * @params Array $resultSet data that comes from the query that returns the model
    * @uses $this->__clearHiddenFields
    * @return Array $resultSet
	*/
	public function clearFields($resultSet = array()) {
		$resultSet = $this->__asignUrlImg($resultSet);
		foreach ($resultSet as $modelName => &$modelValues) {
			if (is_array($modelValues)) {
				$modelValues = $this->__asignUrlImg($modelValues);
				foreach ($modelValues as $field => $values) {
					if(is_array($values)) {
						foreach ($values as $valueKey => $value) {
							if($value === null) {
								$resultSet[$modelName][$field][$valueKey] = "";
							}
						}
					}elseif($values === null) {
						$resultSet[$modelName][$field]= "";
					}	
				}
			}
		}
		return $resultSet;
	}


	/*
    * Get a message at the same time from the list of messages that returns
    * a model, when it can not save, because a validation fails
    * @params Array $listErrors list of errors returned by the model
    * @return String $error
	*/
	public function getValidationErrors($listErrors = array()) {
		$error = null;
		if(!empty($listErrors)) {
		  foreach ($listErrors as $field => $errors) {
		    foreach ($errors as $errorMessage) {
		      $error = $this->__getErrorCodeMessage($errorMessage);
		      break;
		    }
		  }
		}
		return $error;
	}

	/**
	 * Generate error message structure
	 * @param  [array] $code array with error messajes
	 * @return [array]       [Code of result error]
	 */
	private function __getErrorCodeMessage($code = null) {
		$errors = array(
			'0001' => array('code'=>'0001', 'message'=>__('The name is required')),
			'0002' => array('code'=>'0002', 'message'=>__('The email is required')),
			'0003' => array('code'=>'0003', 'message'=>__('The email already exists')),
			'0004' => array('code'=>'0004', 'message'=>__('The file exceeds the upload file size limit.')),
			'0005' => array('code'=>'0005', 'message'=>__('The image is not jpg, gif or png.')),
			'0006' => array('code'=>'0006', 'message'=>__('The image size is too large. Only 10MB size images are allowed.')),
			'0007' => array('code'=>'0007', 'message'=>__('The image does not have the jpg, gif, png or jpeg extension.')),
			'0008' => array('code'=>'0008', 'message'=>__('User not exists')),
			'0009' => array('code'=>'0009', 'message'=>__('Error saving, please try again.')),
			'0010' => array('code'=>'0010', 'message'=>__('There are no records')),
			'0011' => array('code'=>'0011', 'message'=>__('The user Id is required')),
		);
		if(!empty($errors[$code])) {
			return $errors[$code]; 
			$this->log(__CLASS__.':'.__FUNCTION__.':'.__LINE__." errors",'debug');
			$this->log($errors,'debug');
		}
		return array('message'=>$code);
	}

	/**
	 * Asign URL to Image in response data
	 * @param  [array] $data User data
	 * @return [array]       [Image user asigned]
	 */
	private function __asignUrlImg($data){
		if (!empty($data["User"]["Image"])) {
			$data["User"]["Image"] = Router::url("/",true)."files/users/".$data["User"]["Image"];
		}
		return $data;
	}



}


