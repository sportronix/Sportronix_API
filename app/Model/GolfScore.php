<?php 

App::uses('AppModel', 'Model');

class GolfScore extends AppModel {

	public $useTable = 'golf_scores';
	
	public $hasOne = array(
	
					'Player' => array(
					
						'classname' => 'Player',
						'foreignKey' => false,
						'conditions' => array('GolfScore.player_id=Player.id')
					
					)
	
					);
	
}