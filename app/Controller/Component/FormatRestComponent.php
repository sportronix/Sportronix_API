<?php


App::Uses('Component', 'Controller');


class FormatRestComponent extends Component{

	public $name = 'FormatRest';

	public function format_json_findall( $arr = array() ){
	
		if( count($arr) <= 0 )
			return 0;
	
		$key = array_keys( $arr[0] );
		$key = $key[0];
	
		for($count=0; $count<count($arr); $count++){
			
			echo json_encode( $arr[$count][ $key ] );
			
			if( $count < (count($arr)-1) )
				echo '<br/>';
			
		
		}

		return 1;
	
	}


}


?>