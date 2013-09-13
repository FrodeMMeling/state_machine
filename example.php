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
		'parked'	=> 'idling'
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
	'idle'	=> array(
		'first_gear'	=> 'idling'
	)
));

var_dump ($vehicle->isParked()); // true
var_dump ($vehicle->canShiftUp()); // false
var_dump ($vehicle->canIgnite()); // idling
var_dump ($vehicle->ignite()); // idling
var_dump ($vehicle->canPark()); // parked
var_dump ($vehicle->canShiftDown()); // false
var_dump ($vehicle->canShiftUp()); // first_gear
var_dump ($vehicle->shiftUp()); // first_gear
var_dump ($vehicle->shiftDown()); // idling
var_dump ($vehicle->shiftDown()); // false