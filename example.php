<?php
/**
 *  Vehicle->isParked()
 *  Vehicle->canIgnite()
 *  Vehicle->ignite()
 *  Vehicle->isIdling()
 *  Vehicle->canShiftUp()
 *  Vehicle->shiftUp()
 *  Vehicle->isFirstGear()
 *  
 *  @see https://raw.github.com/pluginaweek/state_machine/master/examples/Vehicle_state.png
 */
require_once 'StateMachine.php';

$vehicle = new StateMachine ('Vehicle', 'parked', array(
	'ignite'	=> array(
		'parked'	=> 'idling',
		'stalled'	=> 'stalled'
	),
	'park'	=> array(
		'idling'	=> 'parked',
		'first_gear'	=> 'parked'
	),
	'shift_up'	=> array(
		'idling' => 'first_gear',
		'first_gear' => 'second_gear',
		'second_gear'	=> 'third_gear'
	),
	'shift_down'	=> array(
		'first_gear' => 'idling',
		'second_gear'	=> 'first_gear',
		'third_gear'	=> 'second_gear'
	),
	'crash'	=> array(
		'first_gear'	=> 'stalled',
		'second_gear'	=> 'stalled',
		'third_gear'	=> 'stalled'
	),
	'repair'	=> array(
		'stalled'	=> 'parked'
	),
	'idle'	=> array(
		'first_gear'	=> 'idling'
	)
));

$vehicle->addMethod('isMoving', function($f, $currentState) {
	return in_array($currentState, array('first_gear', 'second_gear', 'third_gear'));
});

$vehicle->addMethod('speed', function($f, $currentState) {
	$factor = 0;
	
	if ($currentState === 'first_gear') {
		$factor = 1;
	} elseif ($currentState === 'second_gear') {
		$factor = 2;
	} elseif ($currentState === 'third_gear') {
		$factor = 3;
	}
	
	return 10 * $factor;
});

$vehicle->onTransition(function($toState, $fromState, $transition) {
	echo "After performed: <", $transition, "> from state <", $fromState, "> to <", $toState, ">", PHP_EOL;
});

$vehicle->onTransition('before', function($toState, $fromState, $transition) {
	echo "Before performing: <", $transition, "> from state <", $fromState, "> to <", $toState, ">", PHP_EOL;
});

$vehicle->onIgnite('before', function() {
	echo 'Before Igniting the car.', PHP_EOL;
});

$vehicle->onIgnite('after', function() {
	echo 'After Igniting the car.', PHP_EOL;
});

assert($vehicle->isParked() === true);
assert($vehicle->canShiftUp() === false);
assert($vehicle->canIgnite() === true);
assert($vehicle->ignite() === "idling");
assert($vehicle->canPark() === true);
assert($vehicle->canShiftDown() === false);
assert($vehicle->canShiftUp() === true);
assert($vehicle->shiftUp() === "first_gear");
assert($vehicle->speed() === 10);
assert($vehicle->shiftDown() === "idling");
assert($vehicle->isMoving() === false);
assert($vehicle->shiftDown() === false);
assert($vehicle->shiftUp() === "first_gear");
assert($vehicle->crash() === "stalled");
assert($vehicle->ignite() === "stalled");
assert($vehicle->repair() === "parked");