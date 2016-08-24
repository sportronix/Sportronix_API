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
class SeanController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Sean';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Simple', 'Player', 'EnumSport', 'Member');
	
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
	
	public function test(){ // Action
		
		/*
		*
		* A test to print out the json data
		*
		* Data Fields:
		* - id        (INT)
		* - message   (TEXT)
		* - timestamp (TIMESTAMP)
		*
		* sendType: (GET)
		*
		*/
		
		//$data = $this->Simple->find('first');	// Gets the information from the table simple (Grabs the first row)
		$data = array('Simple' => array('5', '6', '8'));

		/*
		$data['Simple']['id']
		$data['Simple']['text']
		$data['Simple']['timestamp']
		$data['Simple']['some other field']
		
		$data = array("Simple" => array("id" => 5) );
		
		$data = array(0 => array("Simple" => array("id" => 5)),
					  1 => array("Simple" => array("id" => 6) 
					 );
		*/
		
		echo json_encode( $data );	// Print out data
	
	}

	public function simple(){ // Action
	
		/*
		*
		* Test - Nothing of use right now
		*
		* ??? Probably won't use this function at all but I'll (sean) get rid of it later
		*
		* sendType: (GET)
		*
		*/
		
	}
	
	public function loginTest(){
	
		/*
		*
		* Will be using digestpasswords but not at the moment
		* This will remain hardcoded until registration is completed
		*
		* sendType: (POST)
		*
		*/
	
		if( $this->request->is('post') ){
		
			if($_POST['username'] == "test" && $_POST['password'] == "test")
				echo json_encode( array("entry" => "1") );
			else
				echo json_encode( array("entry" => "0") );
		}
	
	}

	public function loginTest2(){
	
		/*
		*
		* Will be using digestpasswords but not at the moment
		* This will remain hardcoded until registration is completed
		*
		* sendType: (POST)
		*
		*/
	
		if( $this->request->is('get') ){
		
			if($_GET['username'] == "test" && $_GET['password'] == "test"){

				$Member = $this->Member->findByUsername("test");

				echo json_encode( array("entry" => "1", 'member' => $Member) );
			}
			else
				echo json_encode( array("entry" => "0") );
		}
	
	}
	
	public function getPlayers(){
	
		/*
		*
		* Gets Player Information From The Database.
		* print out the data (json format) in a newline (ie. use <br/>) for each row.
		* RestClient handles <br/> to create it's internal array.
		* 
		* Data Fields:
		* - id        (INT)
		* - name      (TEXT)
		* - date_of_birth  (TIMESTAMP)
		* - career_start   (TIMESTAMP)
		* - sport          (INT)
		* - number         (INT)
		* - ranking        (INT)
		*
		* sendType: (GET)
		*
		*/
	
	
		$Player = $this->Player->find('all');
		echo json_encode( array('result' => 1, $Player) );
		//$this->FormatRest->format_json_findall( $Player );
	
	}

	public function getPlayerById($id){
	
		/*
		*
		* Gets Player Information From The Database with Specific Parameters.
		* print out the data (json format) in a newline (ie. use <br/>) for each row.
		* RestClient handles <br/> to create it's internal array.
		* 
		* [IN]
		*
		* player_id    (INT) --> [Required]
		* limit       (INT) --> [Optional]
		* limit_start (INT) --> [Optional]
		*
		* [OUT]
		* Data Fields:
		* - id             (INT)
		* - name           (TEXT)
		* - date_of_birth  (TIMESTAMP)
		* - career_start   (TIMESTAMP)
		* - sport          (INT)
		* - number         (INT)
		* - ranking        (INT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if(isset($id))
		{
			//$Player = $this->Player->findById($id);
			//json_encode( $Player );
			$Player = $this->Player->find('all', array('conditions' => array('Player.id' => $id )));
			$this->FormatRest->format_json_findall( $Player );
		}
		else
		{

			if( $this->request->is('post') ){
			
				$Player = array();
			
				if(isset($_POST['player_id'])){
			
					if( isset($_POST['limit']) ){

						if( !isset($_POST['limit_start']) )
							$Player = $this->Player->find('all', array('conditions' => array('Player.id' => $_POST['player_id']), 'limit' => $_POST['limit']));
						else
							$Player = $this->Player->find('all', array('conditions' => array('Player.id='.$_POST['player_id'].' LIMIT '.$_POST['limit_start'].','.$_POST['limit'] ) ));

					}else{

						$Player = $this->Player->find('all', array('conditions' => array('Player.id='.$_POST['player_id']) ));

					}

					$this->FormatRest->format_json_findall( $Player );
				
				}
				else
					echo json_encode( array() );
					
				//$log = $this->Player->getDataSource()->getLog(false, false);
				//debug( $log );
			}
			else
				echo json_encode( array() );

		}
	
	}
	
	public function getPlayersBySport(){
	
	
		/*
		*
		* Gets Player Information From The Database with Specific Parameters.
		* print out the data (json format) in a newline (ie. use <br/>) for each row.
		* RestClient handles <br/> to create it's internal array.
		* 
		* [IN]
		*
		* sport_id    (INT) --> [Required]
		* limit       (INT) --> [Optional]
		* limit_start (INT) --> [Optional]
		*
		* [OUT]
		* Data Fields:
		* - id             (INT)
		* - name           (TEXT)
		* - date_of_birth  (TIMESTAMP)
		* - career_start   (TIMESTAMP)
		* - sport          (INT)
		* - number         (INT)
		* - ranking        (INT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
		
			$Player = array();
		
			if(isset($_POST['sport_id'])){
		
				if( isset($_POST['limit']) ){

					if( !isset($_POST['limit_start']) )
						$Player = $this->Player->find('all', array('conditions' => array('Player.sport' => $_POST['sport_id']), 'limit' => $_POST['limit']));
					else
						$Player = $this->Player->find('all', array('conditions' => array('Player.sport='.$_POST['sport_id'].' LIMIT '.$_POST['limit_start'].','.$_POST['limit'] ) ));

				}else{

					$Player = $this->Player->find('all', array('conditions' => array('Player.sport' => $_POST['sport_id']) ));

				}

				$this->FormatRest->format_json_findall( $Player );
			
			}
			else
				echo json_encode( array() );
				
			//$log = $this->Player->getDataSource()->getLog(false, false);
			//debug( $log );
		}
		else
			echo json_encode( array() );
	}
	
	public function getPlayersBySportStr(){
	
	
		/*
		*
		* Gets Player Information From The Database with Specific Parameters.
		* print out the data (json format) in a newline (ie. use <br/>) for each row.
		* RestClient handles <br/> to create it's internal array.
		* 
		* [IN]
		*
		* sport       (TEXT) --> [Required]
		* limit       (INT)  --> [Optional]
		* limit_start (INT)  --> [Optional]
		*
		* [OUT]
		* Data Fields:
		* - id             (INT)
		* - name           (TEXT)
		* - date_of_birth  (TIMESTAMP)
		* - career_start   (TIMESTAMP)
		* - sport          (INT)
		* - number         (INT)
		* - ranking        (INT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
		
			$Player = array();
		
			if(isset($_POST['sport'])){
			
				$EnumSport = $this->EnumSport->findByName( ucfirst($_POST['sport']) );
				$sport_id = $EnumSport['EnumSport']['id'];
		
				if( isset($_POST['limit']) ){

					if( !isset($_POST['limit_start']) )
						$Player = $this->Player->find('all', array('conditions' => array('Player.sport' => $sport_id), 'limit' => $_POST['limit']));
					else
						$Player = $this->Player->find('all', array('conditions' => array('Player.sport='.$sport_id.' LIMIT '.$_POST['limit_start'].','.$_POST['limit'] ) ));

				}else{

					$Player = $this->Player->find('all', array('conditions' => array('Player.sport' => $sport_id) ));

				}

				$this->FormatRest->format_json_findall( $Player );
			
			}
			else
				echo json_encode( array() );
				
			//$log = $this->Player->getDataSource()->getLog(false, false);
			//debug( $log );
		}
		else
			echo json_encode( array() );
	}

}
