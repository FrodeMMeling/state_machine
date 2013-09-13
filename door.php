<?php

require_once 'StateMachine.php';

$door = new StateMachine('Door', 'opened', array(
	'close'	=> array(
		'opened'	=> 'closed'
	),
	'open'	=> array(
		'closed'	=> 'opened'
	)
));

$door->open();
echo $door->currentState, PHP_EOL;
$door->open();
$door->close();
echo $door->currentState, PHP_EOL;