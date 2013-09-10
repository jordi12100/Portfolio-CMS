<?php

namespace Application\TwitterBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Application\TwitterBundle\Entity\Tweet;

class TweetCommand extends ContainerAwareCommand {

	protected $name;

	protected $output;

	protected $consumer_key;
	protected $consumer_secret;
	protected $token_key;
	protected $token_secret;

	private $tweetarray = array();

	protected function configure() {
		$this -> setName('twitter:update') -> setDescription('Grab tweets from twitter', 'Grab tweets from user');
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		$this -> output = $output;

		$this -> consumer_key = $this -> getContainer() -> getParameter('consumer_key');
		$this -> consumer_secret = $this -> getContainer() -> getParameter('consumer_secret');
		$this -> token_key = $this -> getContainer() -> getParameter('token_key');
		$this -> token_secret = $this -> getContainer() -> getParameter('token_secret');

		if ($tweets = $this -> getTweets()) {
			$this -> resetTweets();
			$this -> saveTweets($tweets);
		}
	}

	protected function getTweets() {

		if ($this -> sendRequest() && count($this -> tweetarray) > 0) {

			foreach ($this -> tweetarray AS $key => $tweet) {
				$tweetdata[$key]['tweet'] = $tweet['text'];
				$tweetdata[$key]['user'] = $tweet['user']['screen_name'];
				$tweetdata[$key]['created'] = date('Y-m-d H:i:s', strtotime($tweet['created_at']));
			}

			return $tweetdata;
		} else {
			return false;
		}

	}

	protected function saveTweets($tweets) {
		foreach ($tweets as $tweet) {
				$tweetEntity = new Tweet;

				$tweetEntity -> setTweet($tweetEntity -> linkifyTweet($tweet['tweet']));
				$tweetEntity -> setUser($tweet['user']);
				$tweetEntity -> setDateAdded(new \DateTime($tweet['created']));

				$em = $this -> getContainer() -> get('doctrine') -> getEntityManager('default');

				$em -> persist($tweetEntity);
				$em -> flush();
		}

		$this -> output -> writeln('<comment>New tweets saved!</comment>');
	}

	protected function resetTweets() {

		$em = $this -> getContainer() -> get('doctrine') -> getEntityManager('default');
			
		$tweets = $em -> getRepository('ApplicationTwitterBundle:Tweet') -> findAll();

		foreach ($tweets AS $tweet) {
			$tweetrecord = $em -> getRepository('ApplicationTwitterBundle:Tweet') -> find($tweet -> getId());

			$em -> remove($tweetrecord);
			$em -> flush();
		}
		
		$this -> output -> writeln('<comment>Old tweets wiped!</comment>');
	}

	protected function sendRequest() {

		$oauth = new \tmhOAuth( array('consumer_key' => $this -> consumer_key, 'consumer_secret' => $this -> consumer_secret, 'token' => $this -> token_key, 'secret' => $this -> token_secret, ));

		$code = $oauth -> user_request(array('url' => $oauth -> url('1.1/statuses/user_timeline')));

		$this -> output -> writeln('<comment>Connecting to service.....</comment>');

		if ($code !== 200) {
			$this -> output -> writeln('<error>Request failed, bad authentication or invalid URL!</error>');
		} else {
			$this -> output -> writeln('<comment>Request sent...</comment>');

			$data = json_decode($oauth -> response['response'], true);
			$this -> tweetarray = $data;

			return true;
		}
	}

}
