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
			$action = strtolower($matches[1]);
			
			if (! isset($this->transitions[$action])) {
				return false;
			}
			
			$states = $this->transitions[$action];
			$transitions = $states['from'];
			
			if (! is_array ($transitions)) {
				$transitions = array ($transitions);
			}
			
			return in_array ($this->currentState, $transitions);
		} else {
			$function = strtolower($function);
			
			// perform transition $function
			if (! $this->{"can" . $function}()) {
				return false;
			}
			
			$states = $this->transitions[$function];
			$transitions = $states['to'];
			
			if (! is_array ($transitions)) {
				$this->currentState = $transitions;
				return $this->currentState;
			}
			
			// we can move to many different states. we have to figure out
			// exactly which one we can move to.
			
		}
	}
}