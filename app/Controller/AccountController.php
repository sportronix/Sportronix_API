<?php
/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class AccountController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Account';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Member', 'Bet', 'Feedback');
	public $components = array('FormatRest');

/**
 * Displays an action with it's view
 *
 * @param mixed What page to display
 * @return void
 */

	public function beforeFilter(){
		
		$this->autoRender = false;
	
	}
	
	
	public function update_userpassword(){
	
		/*
		*
		* Update password
		* 
		* [IN]
		*
		* memberid  (TEXT) --> [Required]
		* oldpassword  (TEXT) --> [Required]
		* newpassword  (TEXT) --> [Required]
		* 
		*
		* [OUT]
		* Data Fields:
		* - result		(INT)
		* - message		(TEXT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
	
			$Member = $this->Member->findById( $_POST['memberid'] );
			
			$oldpasswd = $_POST['oldpassword'];
			$newpasswd = $_POST['newpassword'];
			
			$h_oldpasswd = AuthComponent::password( $oldpaswd );
			
			if( $h_oldpasswd == $Member['Member']['password'] ){
			
				$h_newpasswd = AuthComponent::password( $newpasswd );
				$Member['Member']['password'] = $h_newpasswd;
				
				if( $this->Member->save( $Member ) ){
				
					echo json_encode( array('result' => 1, 'message' => 'password update successful!') );
				
				}else{
				
					echo json_encode( array('result' => 0, 'message' => 'problem saving new password!') );
				
				}
			
			}else{
			
				echo json_encode( array('result' => 0, 'message' => 'old password not correct!') );
			
			}
			
		
		}else{
		
			echo json_encode( array('result' => 0) );
		
		}
		
		
	
	}
	
	public function update_useremail(){
	
		/*
		*
		* Update email
		* 
		* [IN]
		*
		* memberid  (TEXT) --> [Required]
		* newemail  (TEXT) --> [Required]
		* 
		*
		* [OUT]
		* Data Fields:
		* - result		(INT)
		* - message		(TEXT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
	
			$Member = $this->Member->findById( $_POST['memberid'] );
			
			$newemail = $_POST['newemail'];
			$Member['Member']['email'] = $newemail;
			
			if( $this->Member->save( $Member ) ){
			
				echo json_encode( array('result' => 1, 'message' => 'email successfully updated!') );
			
			}else{
			
				echo json_encode( array('result' => 0, 'message' => 'unable to update email!') );
			
			}
			
		
		}else{
		
			echo json_encode( array('result' => 0) );
		
		}
		
		
	
	}
	
	public function send_feedback(){
	
		/*
		*
		* Store feedback
		* 
		* [IN]
		*
		* feedback  (TEXT) --> [Required]
		* 
		*
		* [OUT]
		* Data Fields:
		* - result		(INT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
	
			
			$feedback = $_POST['feedback'];
			
			$Feed = array('Feedback' => array());
			$Feed['Feedback']['text'] = $feedback;
			
			if( $this->Feedback->save( $Feed ) ){
			
				echo json_encode( array('result' => 1) );
			
			}else{
			
				echo json_encode( array('result' => 0) );
			
			}
			
		
		}else{
		
			echo json_encode( array('result' => 0) );
		
		}
		
		
	
	}
	
	public function change_creditcard(){
	
		/*
		*
		* Number of User Bets Placed
		* 
		* [IN]
		*
		* memberid (TEXT) --> [Required]
		* new_card (TEXT) --> [Required]
		* 
		*
		* [OUT]
		* Data Fields:
		* - result		(INT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
	
			$Member = $this->Member->findById( $_POST['memberid'] );
			
			// Need to encrypt credit cards correctly look into finding a nice solution to this..
			
		
		}else{
		
			echo json_encode( array('result' => 0) );
		
		}
	
	}
	
}
