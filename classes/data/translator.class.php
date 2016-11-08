<?php
/*
Class Translator
Coder: Mai Minh
Last updated: 01/06/2012
*/
class Translator
{
	var $messages;
	
	#Constructor
	function Translator($messages) {
		$this->messages = $messages;
	}
	function msg($key='') {
		if(isset($this->messages[$key])) return $this->messages[$key];
		return '{'.$key.'}';
	}	
}
?>