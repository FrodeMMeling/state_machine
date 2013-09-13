<?php

require_once 'StateMachine.php';

$document = new StateMachine('Document', 'draft', array(
	'propose'	=> array(
		'draft'	=> 'proposed'
	),
	'accept'	=> array(
		'proposed'	=> 'accepted'
	),
	'reject'	=> array(
		'proposed'	=> 'draft'
	)
));

$document->propose();
$document->reject();
echo $document->currentState, PHP_EOL;
$document->propose();
echo $document->currentState, PHP_EOL;
$document->accept();
echo $document->currentState, PHP_EOL;