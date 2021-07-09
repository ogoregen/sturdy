<?php

/**
 * Messages Class
 * 
 * Manage cookie based messages (notifications).
 * 
 * Messages class stores messages as properties and cookies and retrieves
 * them. All properties and methods of it are static and the class is not
 * designed to be instantiated.
 */

namespace Sturdy;

class Messages{

	static $messages = [];
	
	/**
	 * Prevent instantiation.
	 */
	private function __construct(){

		throw new Exception();
	}

	/**
	 * Create cookie-based messages that can be retrieved after redirecting.
	 * 
	 * Also stores messages as static property.
	 * 
	 * @param string $level Message level or any meta data
	 * @param string $body Message itself
	 */
	static function addMessage($level, $body){

		//retrieve existing messages
		$messages = self::$messages;
		if(isset($_COOKIE["messages"])){
			
			$messages = array_merge($messages, json_decode($_COOKIE["messages"], true)); 
		} 
		//add to the end of messages array
		$messages[] = [
			"level" => $level,
			"body" => $body,
		];
		self::$messages = $messages;
		setcookie("messages", json_encode($messages));
	}

	/**
	 * Fetch messages and delete afterwards.
	 * 
	 * @return array Array of messages 
	 */
	static function getMessages(){

		if(isset($_COOKIE["messages"])){

			$messages = json_decode($_COOKIE["messages"], true);
		}
		setcookie("messages", "", -1); //delete read messages
		$messages = array_merge(self::$messages, $messages ?? []);
		self::$messages = []; //delete read messages
		return $messages;
	}
}
