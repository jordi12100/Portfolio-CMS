<?php

/**
 * Symfony2 email class
 * 
 * @author Jordi Kroon
 * @version 1.0
 * 
 * @copyright 2013
 */
 
namespace Application\ContactBundle\Entity;

Class Email {
	
    /**
     * @var Object
     */
	protected $mailer;

    /**
     * @var string
     */
	protected $errorMessage = '';
	
    /**
     * @var array
     */
	protected $from = array();
	
    /**
     * @var array
     */
	protected $to = array();
	
    /**
     * @var string
     */
	protected $message;
	
    /**
     * @var bool
     */
	protected $html = false;
	
    /**
     * @var strimg
     */
	protected $subject = '~ No subject ~ ';

	/**
	 * Initializes the swiftmailer object
	 *
	 * @param object $mailer
	 */
	public function __construct($mailer) {
		$this -> mailer = $mailer;
	}

	/**
	 * Sets the message
	 *
	 * @param string $message
	 */
	public function setMessage($message) {
		$this -> message = $message;
	}

	/**
	 * Sets the subject
	 *
	 * @param string $subject
	 */	
	public function setSubject($subject) {
		$this -> subject = $subject;
	}

	/**
	 * Send email as HTML or plaintext
	 *
	 * @param bool $html
	 */	
	public function isHTML($html) {

		if (!is_bool($html)) {
			throw new \Exception('isHTML function only accepts booleans. Got: ' . $html);
		} else {
			$this -> html = $html;
		}
	}
	
	/**
	 * Sets the receiver
	 *
	 * @param string $to
	 * @param string $tomail
	 */
	public function setTo($to, $toemail) {
		$this -> to = array($toemail => $to);
	}

	/**
	 * Sets the sender
	 *
	 * @return string $from
	 * @param string $frommail
	 */
	public function setFrom($from, $fromemail) {

		$this -> from = array($fromemail => $from);

	}

	/**
	 * Sends the email
	 * 
	 * @return bool sended or not
	 */
	public function sendEmail() {

		if (!isset($this -> from, $this -> to, $this -> message)) {
			$this -> errorMessage = 'U hebt niet alle velden (correct) ingevuld!';
		} else {

			$message = \Swift_Message::newInstance();

			$message -> setSubject($this -> subject);
			$message -> setFrom($this -> from);
			$message -> setTo($this -> to);
			$message -> setBody($this -> message);

			if ($this -> html == true) {
				$message -> setContentType("text/html");
			} else {
				$message -> setContentType("text/plain");
			}

			try {
				$this -> mailer -> send($message);

				return true;
			} catch(Exception $exception) {
				$this -> errorMessage = $exception;
			}

		}

	}

	/**
	 * Returns the error message
	 * 
	 * @return string error message
	 */
	public function getErrorMessage() {
		return $this -> errorMessage;
	}

}
