<?php
/**
 *  State Machine in PHP.
 *  
 *  A state machine is a series on events and transitions. You cannot move from one event to another, unless the 
 *  transitions allows this.
 */

class StateMachine {

	public $name;
	
	public $initialState;
	
	public $currentState;
	
	public $transitions = array();
	
	public $listeners = array();
	
	public function __construct($name, $initial_state, $transitions) {
		$this->name = $name;
		$this->initialState = $this->currentState = $initial_state;
		$this->transitions = $transitions;
	}
	
	/**
	 *  StateMachine->is<State>		i.e. StateMachine->isParked()
	 *  StateMachine->can<Action>	i.e. StateMachine->canShiftGear()
	 *  StateMachine-><action>		i.e. StateMachine->shiftGear();
	 */
	public function __call($function, array $args = array()) {
		if (method_exists($this, $function)) {
			return call_user_func_array(array ($this, $function), $args);
		}
		
		$matches = array();
		
		if (preg_match('#^is([a-zA-Z0-9_-]+)#', $function, $matches)) {
			$state = $matches[1];
			return $this->currentState === strtolower($state);
		} elseif (preg_match('#^can([a-zA-Z0-9_-]+)#', $function, $matches)) {
			// if one can go from $this->currentState to $function
			$action = strtolower($matches[1]);
			return !!$this->getStates($action);
		} elseif (preg_match('#^on([a-zA-Z0-9_-]+)#', $function, $matches)) {
			array_unshift($args, $matches[1]);
			return call_user_func_array(array($this, 'on'), $args);
		} else {
			$function = strtolower($function);
			
			// the states we are allowed to switch to
			$statesTo = $this->getStates($function);
			
			// perform transition $function
			if (! $statesTo) {
				return false;
			}
			
			$previousState = $this->currentState;
			$this->currentState = $statesTo;
			
			if (isset($this->listeners[$function])) {
				foreach ($this->listeners[$function] as $cb) {
					$cb($this->currentState, $previousState);
				}
			}
			
			return $this->currentState;
		}
	}
	
	public function on($transition, $cb) {
		$this->listeners[strtolower($transition)][] = $cb;
	}
	
	/**
	 *  Returns the states the machine can be in, if $transition
	 *  is performed.
	 */
	public function getStates($transition) {
		if (! isset($this->transitions[$transition])) {
			// transition doesn't exist
			return false;
		}
		
		// get the states the machine can move from and to
		$states = $this->transitions[$transition];
		
		if (! isset($states[$this->currentState])) {
			// we canno move from the current state
			return false;
		}
		
		return $states[$this->currentState];
	}
}