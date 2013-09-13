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
		$matches = array();
		
		if (preg_match('#^is([a-zA-Z0-9_-]+)#', $function, $matches)) {
			$state = $matches[1];
			return $this->currentState === strtolower($state);
		} elseif (preg_match('#^can([a-zA-Z0-9_-]+)#', $function, $matches)) {
			// if one can go from $this->currentState to $function
			$action = strtolower($matches[1]);
			
			if (! isset($this->transitions[$action])) {
				return false;
			}
			
			$states = $this->transitions[$action];
			
			if (! isset($states[$this->currentState])) {
				// we cannot move from $this->currentState to $action
				return false;
			}
			
			$transitions = $states[$this->currentState];
			
			// return true;
			return $transitions;
		} else {
			$function = strtolower($function);
			
			// the states we are allowed to switch to
			$statesTo = $this->{"can" . $function}();
			
			// perform transition $function
			if (! $statesTo) {
				return false;
			}
			
			$this->currentState = $statesTo;
			return $this->currentState;
		}
	}
}