<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppModel', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class Member extends AppModel {

	public $useTable = 'members';
	public $validate = array();


	public function beforeSave($options = array()){
	
		if( !isset($this->data['Member']['id']) ) {
			App::Uses('AuthComponent', 'Controller/Component');
			$this->data['Member']['password'] = AuthComponent::password( $this->data['Member']['password'] );
			$this->data['Member']['timestamp_created'] = date('Y-m-d H:i:s', time());
		}
		
		return true;
	}


}
