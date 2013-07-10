<?php

namespace Application\TwitterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TweetCommand extends ContainerAwareCommand {

	protected $name;

	protected $output;

	protected function configure() {
		$this -> setName('twitter:update') -> setDescription('Grab tweets from twitter') -> addArgument('name', InputArgument::REQUIRED, 'Grab tweets from user');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$this -> name = $input -> getArgument('name');
		$this -> output = $output;

		$this -> comment();
	}

	protected function getTweets() {

		$this -> output -> writeln('<comment>Connecting to service.....</comment>');
		
		$output = $this->sendRequest();
		
		$this -> output -> writeln('<comment>Request sended.....</comment>');
		
		
	}

	protected function sendRequest() {
		
		/**
		 * user -> screen_name
		 * text
		 */
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=jordi12100&count=5"); 	
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 

		curl_setopt($ch, CURLOPT_HTTPHEADER, 
		array('Authorization: OAuth oauth_consumer_key="Z03Z6XoHvxpwO5F8dPxfMg", oauth_nonce="117abf46a0e08f98f0761a174aad35a3", oauth_signature="Fp%2Fp4xk2vGzTy42PSAzkHGk02Js%3D", oauth_signature_method="HMAC-SHA1", oauth_timestamp="1373415252", oauth_token="228118190-vo2SvE7xtiDDXS96623FsTgVXhVolR1G9E8Fj54O", oauth_version="1.0"'));
		
		$cr = curl_exec($ch);
		
		curl_close($ch);
	
		$array = json_decode($cr, true);
		
		$tweetdata = array();
		
		foreach($array AS $key => $tweet) {
			$tweetdata[$key]['tweet'] = $tweet['text'];
			$tweetdata[$key]['user']  = $tweet['user']['screen_name'];
		}
		
		return $tweetdata;
		
	}
	

}
