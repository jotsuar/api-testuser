<?php
App::uses('AppModel', 'Model');

class User extends AppModel {

	public $actsAs = array(
	   'Upload.Upload' => array(
	         'Image' => array(
	           'pathMethod'   => 'flat',
	           'nameCallback' => 'renameFile',
	           'path'         => '{ROOT}{DS}webroot{DS}files{DS}users{DS}',
	           'deleteOnUpdate' => false,
	           'deleteFolderOnDelete' => false,
	           'maxSize' => 3000000,
	         )
	     )
	);


	public $validate = array(
		'name' => array(
			array('rule'   => array('notBlank'),
				'message'  => '0001',
				'required' => true,
			),
		),
		'email' => array(
			array('rule'   => array('email'),
				'message'  => '0002',
				'on'       => "create",
				'required' => true,
			),
			array('rule'   => array('isUnique'),
				'message'  => '0003',
				'on'       => "create",
				'required' => true,
			),
		),
		'Image' => array(          
            'isUnderPhpSizeLimit' => array(
                'rule'    => 'isUnderPhpSizeLimit',
                'message' => '0004',
            ), 
            'isValidMimeType' => array(
                'rule'    => array('isValidMimeType', array('image/jpeg', 'image/png', 'image/gif'), false),
                'message' => '0005',
            ),
            'isBelowMaxSize' => array(
                'rule'    => array('isBelowMaxSize', 10485760),
                'message' => '0006'
            ), 
            'isValidExtension' => array( 
                'rule'    => array('isValidExtension', array('jpg', 'png', 'jpeg','gif'), false),
                'message' => '0007', 
            ), 
        ), 
	);

	public function renameFile($field, $currentName, $data, $options = array()) { 

        $rand_v2     = uniqid();
        $nameContent = explode(".", $currentName);
        $ext         = end($nameContent);
        $newName     = $this->alias."_{$rand_v2}.{$ext}";
        return $newName;

    }


}
