<?php

	$this->layout = 'ajax';

?>

<style type="text/css">

	body{
	
		background: url('/sportronixrest/img/Background/background.png');
		font-family: Arial;
		
	}

	#content{
	
		position: relative;
		display: block;
		
		width: 1200px;
		
		margin: 0px auto;
	
	}
	
	.message{
	
		position: relative;
		display: block;
	
		width: 100%;
	
	}
	
	.message p{
	
		font-size: 18px;
		text-align: center;
		font-weight: bold;
		color: #9E8E7E;
		text-shadow: -1px 1px 1px black;
	
	}

	.RestFunctionBox{
	
		position: relative;
		display: block;
		
		width: 100%;
		min-height: 500px;
		
		margin-top: 100px;
	
	}
	
	.RestFunctionBox span.header{
	
		position: relative;
		display: block;
		
		font-size: 24px;
		
		margin: 25px 0px;
	
	}
	
	.RestFunctionBox table{
	
		width: 100%;
	
	}
	
	.RestFunctionBox a{
	
		color: orange;
		text-decoration: none;
	
	}
	
	.RestFunctionBox a:hover{
		color: red;
	}

</style>

<div id="content">

<div class="message">
	<p>Hey you guys. This will be the rest sample test lobby. Basically we will make specific php action with this site and make links to them here so we can all see and test them. Ill be the first to put mine up so you can see the code and follow the format.</p>
</div>

<div class="RestFunctionBox">

	<span class="header">SAMPLES</span>
	
	<table>
	
		<tr>
			<td>Links</td>
			<td>Description</td>
		</tr>
		
		<!-- ADD LINKS HERE!! -->
		
		<tr>
			<td><?php echo $this->Html->link('Test (Sean)', array('controller' => 'sean', 'action' => 'test')); ?></td>
			<td>A simple data get from the database and print out the json object</td>
		</tr>
	
	</table>

</div>

</div>