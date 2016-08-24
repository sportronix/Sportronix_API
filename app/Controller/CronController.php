<?php

App::uses('AppController', 'Controller');

class CronController extends AppController{


	public $name = 'Cron';
	
	public $uses = array('GolfScore', 'SkengoWins', 'Member', 'Bet', 'SkengoBet');

	public $components = array('FormatRest');
	
	public function calculate_skengo(){
	
		$this->autoRender = false;
	
		$mGolfScores = $this->GolfScore->find('all',array('conditions'=> array('GolfScore.game_id'=>2,'GolfScore.round'=>2)));
		$gSkengoWins = $this->SkengoWins->find('all',array('conditions'=> array('SkengoWins.game_id'=>2)));
		$TOTALHOLES=18;
		$size = sizeof($mGolfScores);
		$lowestscore=array();
		$carryplayers=array();
		$winners=array();
		
		//print_r($mGolfScores);
		
		
		for($holeinc=1;$holeinc<=$TOTALHOLES;$holeinc++){
			$lowestscore[$holeinc-1]=9999;
			
			//find the lowest score for that hole
			for ($i=0;$i<$size;$i++){
				if($mGolfScores[$i]['GolfScore']['hole'.$holeinc.''] < $lowestscore[$holeinc-1]){
					$lowestscore[$holeinc-1]=$mGolfScores[$i]['GolfScore']['hole'.$holeinc.''];		
				}
			}
			
			$carryplayers[$holeinc-1]=array();
			//find the players that scored the lowest
			for ($i=0;$i<$size;$i++){
				if ($mGolfScores[$i]['GolfScore']['hole'.$holeinc.''] == $lowestscore[$holeinc-1]){
					array_push($carryplayers[$holeinc-1],$i);
				}
			}
			
			
			
		}
		
		//echo "<pre>";
		//var_dump($carryplayers);
		//echo "</pre>";
		
		//find winners
		
		
		
		
		
			foreach($carryplayers as $key1 => $element){
				
				$holestart=$key1+1;
				for($holestart;$holestart<=18;$holestart++){
					
					
					
					$tempsizeof=sizeof($element);
					
					if($tempsizeof>1){
					
						//if($holestart==18){
						//	$holestart=1;
						//}
					
						$templowest=9999;
						
						foreach($element as $key2 => $subelement){
							if($mGolfScores[$subelement]['GolfScore']['hole'.$holestart.''] < $templowest){
								$templowest=$mGolfScores[$subelement]['GolfScore']['hole'.$holestart.''];
							}
						}
						
						//echo "start = ".$key1." hole = ".$holestart." low = ".$templowest."<br>";  
						
						foreach($element as $key2 => $subelement){
						
							if($mGolfScores[$subelement]['GolfScore']['hole'.$holestart.'']>$templowest){

								$tempsizeof2=sizeof($carryplayers[$key1]);
								if($tempsizeof2>1) {unset($carryplayers[$key1][$key2]); };
								
							}else{

							}
					
						}
					}

					
					
					
					
				}
			}
			
			foreach($carryplayers as $key1 => $element){
				
				
				for($holestart=1;$holestart<=18;$holestart++){
				
					$tempsizeof=sizeof($element);
					
					if($tempsizeof>1){
					
						$templowest=9999;
						
						foreach($element as $key2 => $subelement){
							if($mGolfScores[$subelement]['GolfScore']['hole'.$holestart.''] < $templowest){
								$templowest=$mGolfScores[$subelement]['GolfScore']['hole'.$holestart.''];
							}
						}
						
						foreach($element as $key2 => $subelement){
						
							if($mGolfScores[$subelement]['GolfScore']['hole'.$holestart.'']>$templowest){

								$tempsizeof2=sizeof($carryplayers[$key1]);
								if($tempsizeof2>1) {unset($carryplayers[$key1][$key2]); };
								
							}
						}
				
				
					}
				}
			}	



			$SkengoWins_Data_Array = array();
	
			foreach($carryplayers as $key1 => $element){
			
				foreach($element as $key2 => $subelement){
					$temp=$key1+1;
					//echo "aHole ".$temp." winner index = ".$carryplayers[$key1][$key2]." database index = ".$mGolfScores[$subelement]['GolfScore']['id']." player id= ".$mGolfScores[$subelement]['GolfScore']['player_id']." = ". $mGolfScores[$subelement]['Player']['name'] ."<br>";
					
					if( count($gSkengoWins) < 18 ) {
						$SkengoWins_Data = array("SkengoWins" => array());
						$SkengoWins_Data["SkengoWins"]["game_id"]     = 2;
						$SkengoWins_Data["SkengoWins"]["score_id"]    = $mGolfScores[$subelement]['GolfScore']['id'];
						$SkengoWins_Data["SkengoWins"]["player_id"]   = $mGolfScores[$subelement]['GolfScore']['player_id'];
						$SkengoWins_Data["SkengoWins"]["hole_number"] = $temp;
						array_push($SkengoWins_Data_Array, $SkengoWins_Data);
/*
						if( $this->SkengoWins->save( $SkengoWins_Data ) )
							echo json_encode( array('result' => 1) );
						else
							echo json_encode( array('result' => 0) );
*/
					}
				}
				
			}
			//echo "<pre>";
			//var_dump($carryplayers);
			//echo "</pre>";
			if( count($gSkengoWins) < 18 )
				$this->SkengoWins->saveMany( $SkengoWins_Data_Array, array('deep' => true) );
		
		echo json_encode(array('result' => 1));
			
	}

	public function testSkengoWins() {
		$this->autoRender = false;
		$gSkengoWins = $this->SkengoWins->find('all',array('conditions'=> array('SkengoWins.game_id'=>2)));
		echo count($gSkengoWins);
	}

	public function calculate_points(){

		/**
		* After calculating the winner we need to calculate the winnings for each player.
		* 
		*/

		// Get Members who betted.
			$this->autoRender = false;

			$members = $this->Member->find('all');
			$skengowinners = $this->SkengoWins->find('all', array('conditions' => array('SkengoWins.game_id' => 2) ));
			$players_that_won = array();
			$winning_hole = array();
			$skengobets = array();

			for($i=0; $i<count($skengowinners); $i++) {
				array_push($players_that_won,$skengowinners[$i]['SkengoWins']['player_id']);
				array_push($winning_hole, $skengowinners[$i]['SkengoWins']['hole_number']);
				//echo $players_that_won[$i] . "<br />";
			}

			for( $i=0; $i<count($members); $i++ ) {

				unset($members[$i]['Member']['password']);
				$bets = $this->Bet->find('all', array('conditions' => array('Bet.member_id' => $members[$i]['Member']['id'], 'Bet.game_id' => 2, 'Bet.closed' => 0) ));
				$nCorrect = 0;

				for($j=0; $j<count($bets); $j++) {

					$skengobets = $this->SkengoBet->find('all', array('conditions' => array('SkengoBet.bet_Id' => $bets[$j]['Bet']['id']) ));
					
					for($k=0; $k<count($skengobets); $k++) {

						$skengobet_player_id = $skengobets[$k]['SkengoBet']['player_id'];
						$npoints = 0;
						
						for($m=0; $m<count($players_that_won); $m++) {
							if( $skengobet_player_id == $players_that_won[$m] ) {
								
								$h = $winning_hole[$m];
								switch($m+1) {
									case 1:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$nCorrect++;
											$npoints += 55;
										}
									break;

									case 2:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 34;
											$nCorrect++;
										}
									break;

									case 3:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 21;
											$nCorrect++;
										}
									break;

									case 4:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 13;
											$nCorrect++;
										}
									break;

									case 5:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 8;
											$nCorrect++;
										}
									break;

									case 6:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 5;
											$nCorrect++;
										}
									break;

									case 7:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 3;
											$nCorrect++;
										}
									break;

									case 8:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 9:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 10:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 11:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 12:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 13:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 14:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 15:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 16:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 17:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

									case 18:
										if( $skengobets[$k]['SkengoBet']["hole$h"] ) {
											$npoints += 2;
											$nCorrect++;
										}
									break;

								}

								//if($npoints > 0)
								//	$members[$i]['Member']['points'] += $npoints;
								$skengobets[$k]['SkengoBets']['closed'] = true;
								
							}
							//echo $members[$i]['Member']['username'] . ": " . $npoints . "<br />";

						}

					}

					$this->SkengoBet->saveMany( $skengobets );
					$bets[$j]['Bet']['closed'] = 1;

					if($nCorrect > 0) {
						$members[$i]['Member']['points'] += (18*$nCorrect);
						//echo (18*$nCorrect) . '<br />';
					}
					//post member :  add how much gained in message queue


				}

		// Get each bet made by member

				//$bets = $this->Bet->find('all', array('conditions' => array( 'Bet.member_id' => $members[$i]['Member']['id'] ) ));
				//$skengobets = $this->SkengoBet->find('all', array('conditions' => array( 'Bet.member_id' => $members[$i]['Member']['id'] ) )); // The code that stores the player correctly is broken

			}

			$this->Bet->saveMany( $bets );
			$this->Member->saveMany( $members );

			$member = $this->Member->findById( $_POST['memberid'] );
			$u_points = $member['Member']['points']; 

			echo json_encode(array('result' => 1, 'points' => $u_points));

		// Match Bet Info Against SkengoWins. The Max anyone should be able to get is 8/18.


		// Create switch case to see what number of points member gained.
		// Add gained points to member. Close bet.
		// Add message queue (<-- this is new)


		// Close Game

		//Finish

	}

	public function reset() {

		// Demo purposes only!

		$this->autoRender = false;
		$this->SkengoWins->query('TRUNCATE TABLE skengo_wins');
		$this->SkengoBet->query('TRUNCATE TABLE skengo_bets');
		$this->Bet->query('TRUNCATE TABLE bets');

		$m = $this->Member->find('all');
		for($i=0; $i<count($m); $i++)
			$m[$i]['Member']['points'] = 20;
		$this->Member->saveMany($m);

		echo json_encode(array('result' => 1));
	}

	public function getWinners() {
		$this->autoRender = false;
		$Winners = $this->SkengoWins->find('all');
		$this->FormatRest->format_json_findall( $Winners );
	}

}
