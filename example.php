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
	'shiftup'	=> array(
		'idling' => 'first_gear',
		'first_gear' => 'second_gear',
		'second_gear'	=> 'third_gear'
	),
	'shiftdown'	=> array(
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

$vehicle->on('ignite', function($to, $from) {
	echo 'Igniting. From: ', $from, ', to: ', $to, PHP_EOL;
});

$vehicle->onShiftUp(function($to, $from) {
	echo 'Shifting UP. From: ', $from, ', to: ', $to, PHP_EOL;
});

echo "Are we parked?\n";
var_dump ($vehicle->isParked()); // true

echo "Can we shift up?\n";
var_dump ($vehicle->canShiftUp()); // false

echo "Can we ignite?\n";
var_dump ($vehicle->canIgnite()); // true

echo "Igniting..\n";
var_dump ($vehicle->ignite()); // idling

echo "Can we park?\n";
var_dump ($vehicle->canPark()); // true

echo "Can we shift down?\n";
var_dump ($vehicle->canShiftDown()); // false

echo "Can we shift up?\n";
var_dump ($vehicle->canShiftUp()); // true

echo "Shifting up...\n";
var_dump ($vehicle->shiftUp()); // first_gear

echo "Shifting down..\n";
var_dump ($vehicle->shiftDown()); // idling

echo "Shifting down..\n";
var_dump ($vehicle->shiftDown()); // false

echo "Crashing the car..\n";
var_dump ($vehicle->shiftUp());
var_dump ($vehicle->crash()); // false

echo "Trying to ignite..\n";
var_dump ($vehicle->ignite());

echo "No hope, we have to repair..\n";
var_dump ($vehicle->repair());