<?php

namespace Application\TwitterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tweet
 */
class Tweet {
	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $tweet;

	/**
	 * @var string
	 */
	private $user;

	/**
	 * @var string
	 */
	private $date_added;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
		return $this -> id;
	}

	/**
	 * Set tweet
	 *
	 * @param string $tweet
	 * @return Tweet
	 */
	public function setTweet($tweet) {
		$this -> tweet = $tweet;

		return $this;
	}

	/**
	 * Get tweet
	 *
	 * @return string
	 */
	public function getTweet() {
		return $this -> tweet;
	}

	/**
	 * Set user
	 *
	 * @param string $user
	 * @return Tweet
	 */
	public function setUser($user) {
		$this -> user = $user;

		return $this;
	}

	/**
	 * Get user
	 *
	 * @return string
	 */
	public function getUser() {
		return $this -> user;
	}

	public function linkifyTweet($tweet) {

	$tweet = preg_replace('#@([\\d\\w]+)#', '<a href="http://twitter.com/$1">$0</a>', $tweet);
	$tweet = preg_replace('/#([\\d\\w]+)/', '<a href="http://twitter.com/#search?q=%23$1">$0</a>', $tweet);

		return $tweet;
	}

	/**
	 * Set date_added
	 *
	 * @param \DateTime $dateAdded
	 * @return Tweet
	 */
	public function setDateAdded($dateAdded) {
		$this -> date_added = $dateAdded;

		return $this;
	}

	/**
	 * Get date_added
	 *
	 * @return \DateTime
	 */
	public function getDateAdded() {
		return $this -> date_added;
	}

}
