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
		'from'	=> 'parked',
		'to'	=> 'idling'
	),
	'park'	=> array(
		'from'	=> array(
			'idling', 'first_gear'
		),
		'to'	=> 'parked'
	),
	'shiftup'	=> array(
		'from'	=> array(
			'idling', 'first_gear', 'second_gear', 'third_gear'
		),
		'to'	=> array(
			'first_gear', 'second_gear', 'third_gear', 'fourth_gear'
		)
	),
	'shiftdown'	=> array(
		'from'	=> array(
			'second_gear', 'third_gear', 'fourth_gear'
		),
		'to'	=> array(
			'first_gear', 'second_gear', 'third_gear'
		)
	),
	'idle'	=> array(
		'from'	=> array(
			'first_gear', 'second_gear', 'third_gear', 'fourth_gear'
		),
		'to'	=> 'idling'
	),
	'reverse'	=> array(
		'from'	=> 'idling',
		'to'	=> 'reverse'
	)
));

var_dump ($vehicle->isParked());
var_dump ($vehicle->canShiftUp());
var_dump ($vehicle->canIgnite());
var_dump ($vehicle->ignite());
var_dump ($vehicle->canPark());
var_dump ($vehicle->canShiftDown());
var_dump ($vehicle->canShiftUp());
var_dump ($vehicle->shiftUp());