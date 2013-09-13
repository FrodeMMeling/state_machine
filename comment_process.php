<?php

require_once 'StateMachine.php';

$commentProcess = new StateMachine('CommentProcess', 'created', array(
	'accept'	=> array(
		
	)
));

$commentProcess->accept('Owner');
$commentProcess->accept('Yard');