<?php

namespace Application\TwitterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tweet
 */
class Tweet
{
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set tweet
     *
     * @param string $tweet
     * @return Tweet
     */
    public function setTweet($tweet)
    {
        $this->tweet = $tweet;
    
        return $this;
    }

    /**
     * Get tweet
     *
     * @return string 
     */
    public function getTweet()
    {
        return $this->tweet;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Tweet
     */
    public function setUser($user)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }
	
	function linkifyTweet($status_text) {
	  // linkify URLs
	  $status_text = preg_replace(
	    '/(https?:\/\/\S+)/',
	    '<a href="\1" class="preg-links">\1</a>',
	    $status_text
	  );
	 
	  // linkify twitter users
	  $status_text = preg_replace(
	    '/(^|\s)@(\w+)/',
	    '\1@<a href="http://twitter.com/\2" class="preg-links">\2</a>',
	    $status_text
	  );
	 
	  // linkify tags
	  $status_text = preg_replace(
	    '/(^|\s)#(\w+)/',
	    '\1#<a href="http://search.twitter.com/search?q=%23\2" class="preg-links">\2</a>',
	    $status_text
	  );
	 
	  return $status_text;
	}
	
}
