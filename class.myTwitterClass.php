<?php
require_once ($_SERVER['DOCUMENT_ROOT']. '/util/TwitterApiExchange/TwitterAPIExchange.php');

class myTwitterClass{
	
	private $settings;
	private $twitterObj;
	private $method;
	private $postfields;
	private $getfield;
	
	private static $user 						= 'CUENTA';
	private static $url_update 					= 'https://api.twitter.com/1.1/statuses/update.json';
	private static $url_timeline				= 'https://api.twitter.com/1.1/statuses/user_timeline.json';
	
	private static $oauth_access_token 			= 'DATOS';
	private static $oauth_access_token_secret 	= 'DATOS';
	private static $consumer_key 				= 'DATOS';
	private static $consumer_secret 			= 'DATOS';
	
	
	public function __construct(){
		
		date_default_timezone_set('America/Santiago');
		
		if(!isset($this->settings)){
			$this->settings = array(
				'oauth_access_token' 		=> self::$oauth_access_token,
				'oauth_access_token_secret' => self::$oauth_access_token_secret,
				'consumer_key' 				=> self::$consumer_key,
				'consumer_secret' 			=> self::$consumer_secret
			);
		}
		
		if(!isset($this->twitterObj)){
			$this->twitterObj = new TwitterAPIExchange($this->settings);
		}
		
	}
	
	private function setMethod($methodToUse){
		$this->method = $methodToUse;
	}
	
	private function getMethod(){
		return $this->method;
	}
	
	private function setPostfields($postfieldsToUse){
		$this->postfields = $postfieldsToUse;
	}
	
	private function getPostfields(){
		return $this->postfields;
	}
	
	private function setGetfield(){
		return $this->twitterObj->setGetfield('?screen_name='.self::$user.'&count=10');
	}
	
	
	/*
	*	fn sendTwitterMessage: send a twitter message
	*	param	$msg			String		the message to be posted on twitter
	*	return	$twitterObj		object		the twitter object with the post response
	*
	*	Basic usage: 
	*	$obj = new myTwitterClass();
	*	$result = $obj->sendTwitterMessage($msg);
	*
	*/
	public function sendTwitterMessage($msg){
		$this->setMethod('POST');
		$this->setPostfields(array( 'status' => $msg, ));
		
		return $this->twitterObj
			->buildOauth(self::$url_update, $this->getMethod())
			->setPostfields($this->getPostfields())
			->performRequest();
	}
	
	/*
	*	fn getTweetsFromAccount: obtains last 10 tweets from configured account
	*	return	$twitterObj		object		the twitter object with 10 last messages
	* 
	*	Basic usage: 
	* 	$obj = new myTwitterClass();
	*	$result = $obj->getTweetsFromAccount();
	* 	foreach ($result as $item){echo $item['text'];}
	*/
	public function getTweetsFromAccount(){
		$this->setMethod('GET');
		
		$result = $this->setGetfield()
			->buildOauth(self::$url_timeline, $this->getMethod())
			->performRequest();
			
		return json_decode($result, TRUE);
	
	}
}
	
?>