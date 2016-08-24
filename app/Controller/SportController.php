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
class SportController extends AppController {

/**
 * Controller name
 *
 * @var string
 */
	public $name = 'Sport';

/**
 * This controller does not use a model
 *
 * @var array
 */
	public $uses = array('Simple', 'Player', 'Bet', 'GolfScore', 'EnumLeague', 'Game', 'SkengoBet', 'Member');
	
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
	
	public function place_bet_skengo(){ // Action
		
		
		/*
		*
		* Place Bet On Skengo Game
		* 
		* [IN]
		*
		* gameid    (INT) --> [Required] --> Set static for now
		* memberid  (INT) --> [Required] 
		* points	(INT) --> [Required] --> Set static for now
		* league	(INT) --> [Required] --> Set static for now
		* player	(INT) --> [Required] --> Deprecation?
		* allplayers (INT) --> [Required]
		* allholes	(INT:Array) --> [Required]
		*
		* [OUT]
		* Data Fields:
		* - result             (INT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
		
			$this->autoRender = false;
		
			ini_set('max_execution_time', '10');
		
			$Bet = array('Bet' => array());
		
			if( (isset($_POST['gameid']) && !empty($_POST['gameid'])) && (isset($_POST['memberid'])  && !empty($_POST['memberid']) ) && (isset($_POST['points'])  && !empty($_POST['points']) ) && (isset($_POST['league'])  && !empty($_POST['league']) ) && (isset($_POST['player'])  && !empty($_POST['player']) ) && (isset($_POST['allholes'])  && !empty($_POST['allholes']) && !empty($_POST['allplayers'])) ){
		
				//$League = $this->EnumLeague->findByName( $_POST['league'] );
				//$Game   = $this->Game->findByName( $_POST['game'] );
				
				$Points = $_POST['points'];
				$MemberId = $_POST['memberid'];
		
				$Bet['Bet']['stx_game_id'] = 1;
				//$Bet['Bet']['member_id']   = $MemberId;
				//$Bet['Bet']['game_id']     = $Game['Game']['id'];

				$Bet['Bet']['member_id']   = $_POST['memberid'];
				$Bet['Bet']['game_id']     = $_POST['gameid'];

				$Bet['Bet']['league_id']   = 1;//= !empty($League['League']['id']) ? $League['League']['id'] : null; // should not be null. I'll investigate later
				$Bet['Bet']['points']      = $Points;
		
				$member = $this->Member->findById($MemberId);
	
				if( ($member['Member']['points'] - $Points) >= 0) {
					$member['Member']['points'] -= $Points;
					$this->Member->save( $member );
				}else {
					echo json_encode(array('result' => 0, 'msg' => 'Not enough points!'));
					return;
				}
			
				if( $this->Bet->save( $Bet ) ){
				
					$completed_transaction = true;

					$BetId = $this->Bet->getLastInsertId();

					$AllHoles   = explode( ';', $_POST['allholes'] );
					$AllPlayers = explode(';', $_POST['allplayers']);
					$Player   = $this->Player->findByName( $_POST['player'] );

					$SkengoBet = array( array( 'SkengoBet' => array()) );

					for($count=0; $count<count($AllHoles); $count++){
					
						

						$SkengoBet[$count]['SkengoBet']['player_id'] = $AllPlayers[$count];
						$SkengoBet[$count]['SkengoBet']['bet_Id'] = $BetId;

						$hole = 'hole'.$AllHoles[ $count ];
						$SkengoBet[$count]['SkengoBet'][$hole] = 1;

					
					}

					if( $this->SkengoBet->saveMany( $SkengoBet ) ){
						$completed_transaction = true;
					}
					else{
						$completed_transaction = false;
						break;
					}

					if( $completed_transaction) {
						echo json_encode( array('result' => 1) );
					}
					else {
						echo json_encode( array('result' => 0, 'message' => 'Unable To Complete Bet [SkengoBet]') );
					}
				
				
				}else{
				
					echo json_encode( array('result' => 0, 'message' => 'Unable To Complete Bet [Bet]') );
				
				}

				//$this->FormatRest->format_json_findall( $Player );

			}
			else
				echo json_encode( array('result' => 0, 'message' => 'Not All Data Sent!') );
				
		}
		else
			echo json_encode( array('result' => 0) );
	
	}
	
	
	
	
	public function place_bet_rabbit(){ // Action
		
		/// NOT CORRECT AND JUST A PLACE-HOLDER!!!!!
		
		/*
		*
		* Place Bet On Rabbit Game
		* 
		* [IN]
		*
		* gameid    (INT) --> [Required]
		* memberid  (INT) --> [Required]
		* points	(INT) --> [Required]
		* league	(INT) --> [Required]
		* player	(INT) --> [Required]
		* allholes	(INT:Array) --> [Required]
		*
		* [OUT]
		* Data Fields:
		* - result             (INT)
		*
		* sendType: (POST)
		* 
		*/	
	
		if( $this->request->is('post') ){
		
			$this->autoRender = false;
		
			ini_set('max_execution_time', '15');
		
			$Bet = array();
		
			if( (isset($_POST['game']) && !empty($_POST['game'])) && (isset($_POST['memberid'])  && !empty($_POST['memberid']) ) && (isset($_POST['points'])  && !empty($_POST['points']) ) && (isset($_POST['league'])  && !empty($_POST['league']) ) && (isset($_POST['player'])  && !empty($_POST['player']) ) && (isset($_POST['allholes'])  && !empty($_POST['allholes']) ) ){
		
				$League = $this->EnumLeague->findByName( $_POST['league'] );
				$Game   = $this->Game->findByName( $_POST['game'] );
				
				$Points = $_POST['points'];
				$MemberId = $_POST['memberid'];
		
				$Bet['Bet']['stx_game_id'] = 1;
				$Bet['Bet']['member_id']   = $MemberId;
				$Bet['Bet']['game_id']     = $Game['Game']['id'];
				$Bet['Bet']['league_id']   = !empty($League['League']['id']) ? $League['League']['id'] : null; // should not be null. I'll investigate later
				$Bet['Bet']['points']      = $Points;
			
			
				if( $this->Bet->save( $Bet ) ){
				
					$BetId = $this->Bet->getLastInsertId();
				
					$AllHoles = explode( ';', $_POST['allholes'] );
					$Player   = $this->Player->findByName( $_POST['player'] );
				
					$SkengoBet = array('SkengoBet' => array());
		
					$SkengoBet['SkengoBet']['player_id'] = $Player['Player']['id'];
					$SkengoBet['SkengoBet']['bet_id'] = $BetId;
					for($count=0; $count<count($AllHoles); $count++){
					
						$hole = 'hole_'.($count+1);
						$SkengoBet['SkengoBet'][$hole] = $AllHoles[ $count ];
					
					}
					
					if( $this->SkengoBet->save( $SkengoBet ) ){
					
						echo json_encode( array('result' => 1) );
					
					}
					else{
					
						echo json_encode( array('result' => 0, 'message' => 'Unable To Complete Bet [SkengoBet]') );
					
					}
				
				
				}else{
				
					echo json_encode( array('result' => 0, 'message' => 'Unable To Complete Bet [Bet]') );
				
				}

				//$this->FormatRest->format_json_findall( $Player );

			}
			else
				echo json_encode( array('result' => 0, 'message' => 'Not All Data Sent!') );
				
		}
		else
			echo json_encode( array('result' => 0) );
	
	}


	
	public function golf_results(){
	
		// NEEDS TESTING!!!!
	
		/*
		*
		* Get A Golf Game Score Sheet
		* 
		* [IN]
		*
		* game    (TEXT)
		* league  (TEXT)
		* day	  (TEXT)
		*
		* [OUT]
		* Data Fields:
		* - result             (INT)
		* - scoresheet         (TEXT) --> Serialized
		*
		* sendType: (POST)
		* 
		*/		
	
		if( $this->request->is('post') ){
		
			$FormatRest = $this->Components->load('FormatRest');
		
			$Game = $this->Game->findByName( $_POST['game'] );
			$GolfScore = $this->GolfScore->findAllByGameId( $Game['Game']['id'] );
		
			$FormatRest->format_json_findall( $GolfScore );
		
		}
	
	}
	

}
