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
class MemberController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Member';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Member', 'Bet');
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
	
	
	public function register(){
	
		/*
		*
		* Enters New Member Into Database.
		* 
		* [IN]
		*
		* firstname  (TEXT) --> [Required]
		* lastname   (TEXT) --> [Required]
		* username   (TEXT) --> [Required]		
		* email		 (TEXT) --> [Required]
		* password	 (TEXT) --> [Required]
		*
		* [OUT]
		* Data Fields:
		* - result             (BOOL)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
		
			$this->autoRender = false;
		
			$newMember = array('Member' => array());
			
			$newMember['Member']['firstname'] = $_POST['firstname'];
			$newMember['Member']['lastname']  = $_POST['lastname'];
			$newMember['Member']['email']  	  = $_POST['email'];
			$newMember['Member']['username']  = $_POST['username'];
			$newMember['Member']['password']  = $_POST['password'];
			
			if( $this->Member->save( $newMember ) )
				echo json_encode( array('result' => 1) );
			else
				echo json_encode( array('result' => 0) );
		
		}
	
	
	}
	
	public function check_username_available(){

		/*
		*
		* Check If User Already Exists.
		* 
		* [IN]
		*
		* username  (TEXT) --> [Required]
		*
		* [OUT]
		* Data Fields:
		* - result             (BOOL)
		*
		* sendType: (POST)
		* 
		*/		
	
		if( $this->request->is('post') ){
		
			$this->autoRender = false;
		
			$c_username = $_POST['username'];
		
			$Member = $this->Member->findByUsername( $c_username );
		
			if( $Member )
				echo json_encode( array('result' => 1) );
			else
				echo json_encode( array('result' => 0) );
		
		}
	
	
	}
	
	public function login(){
	
		/*
		*
		* User Login
		* 
		* [IN]
		*
		* username  (TEXT) --> [Required]
		* password  (TEXT) --> [Required]
		*
		* [OUT]
		* Data Fields:
		* - result             (BOOL)
		* - memberid           (INT)
		*
		* sendType: (POST)
		* 
		*/

		
		if( $this->request->is('post') ){
		
			$Auth = $this->Components->load('Auth');
			
			$username = $_POST['username'];
			$password = $_POST['password'];
			
			$pw_encrypt = AuthComponent::password( $password );
			
			$Member = $this->Member->find('first', array('conditions' => array('Member.username' => $username, 'Member.password' => $pw_encrypt)));
		
			if( $Member )
				echo json_encode( array('result' => 1, 'memberid' => $Member['Member']['id']) );
			else
				echo json_encode( array('result' => 0, 'aa' => $pw_encrypt) );
		
		}
	
	}
	
	public function account_points(){
	
		/*
		*
		* User Points
		* 
		* [IN]
		*
		* memberid  (TEXT) --> [Required]
		* 
		*
		* [OUT]
		* Data Fields:
		* - result       (INT)
		* - points       (INT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
		
			$Member = $this->Member->findById( $_POST['memberid'] );
			
			if( $Member )
				echo json_encode( array('result' => 1, 'points' => $Member['Member']['points']) );
			else
				echo json_encode( array('result' => 0) );
		
		}else
			echo json_encode( array('result' => 0) );
	
	}
	
	public function account_bets_placed(){
	
		/*
		*
		* Number of User Bets Placed
		* 
		* [IN]
		*
		* memberid  (TEXT) --> [Required]
		* 
		*
		* [OUT]
		* Data Fields:
		* - result      (INT)
		* - nbets       (INT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
		
			$Member = $this->Member->findById( $_POST['memberid'] );
			
			if( $Member ){
			
				$Bet = $this->Bet->find('all', array('conditions' => array('Bet.member_id' => $Member['Member']['id'])));
			
				if( $Bet ){
				
					echo json_encode( array('result' => 1, 'nbets' => count( $Bet ) ) );
					
				}else{
				
					echo json_encode( array('result' => 0) );
					
				}
			
			}else{
			
				echo json_encode( array('result' => 0) );
			
			}
		
		}
		
	}

	public function addpoints() {

		if( $this->request->is('post')) {

			$member_id = $_POST['memberid'];
			$n_points  = intval($_POST['p_points']);

			$member = $this->Member->findById($member_id);
			$member['Member']['points'] += $n_points;

			if ($this->Member->save( $member ))
				echo json_encode(array('result' => 1, 'npoints' => $member['Member']['points']));
			else
				echo json_encode(array('result' => 0));
		}	

	}	
	
	public function get_notifications(){
	
		/*
		*
		* Number of User Bets Placed
		* 
		* [IN]
		*
		* memberid  (TEXT) --> [Required]
		* 
		*
		* [OUT]
		* Data Fields:
		* - result		(INT)
		* -[ARRAY]
		* -- points     (INT)
		* -- game_name  (TEXT)
		* -- game_type	(TEXT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
	
			$Bet = $this->Bet->findAllByMemberId( $_POST['memberid'] );

			if( count($Bet) > 0 ){

				$result_data = array();

				for( $count=0; $count<count($Bet); $count++ )
					array_push($result_data, array('points' => $Bet[$count]['Bet']['points'], 'game_name' => $Bet[$count]['Game']['name'], 'game_type' => $Bet[$count]['EnumStx']['name'], 'closed' => $Bet[$count]['Bet']['closed']) );

				echo json_encode( array('result' => 1, 'Bet' => $result_data ) );

			}else
				echo json_encode( array('result' => 0) );
		
		}else
			echo json_encode( array('result' => 0) );
		
	}
	
}
