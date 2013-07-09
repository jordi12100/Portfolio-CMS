<?php

namespace Application\ContactBundle\Controller;

use Application\ContactBundle\Entity\Email;
use Application\ContactBundle\Form\Type\ContactType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

	protected $request;

	public function indexAction(Request $request) {

		$this -> request = $request;

		$error = 0;
		$success = 0;

		$form = $this -> get('form.factory') -> create(new ContactType());

		if ($this -> request -> isMethod('POST')) {

			$form -> bind($this -> request);

			if ($form -> isValid()) {

				$body = $this -> parseBodyFormat($form -> get('name') -> getData(), $form -> get('email') -> getData(), $form -> get('message') -> getData(), $form -> get('subject') -> getData());
				$email = new Email($this -> get('mailer'));

				$email -> isHTML(true);
				$email -> setFrom($this -> container -> getParameter('contact_name'), $this -> container -> getParameter('contact_email'));
				$email -> setTo($form -> get('name') -> getData(), $form -> get('email') -> getData());
				$email -> setMessage($body);
				$email -> setSubject($form -> get('subject') -> getData());
				$email -> isHTML(false);

				if ($email -> sendEmail()) {
					$success = 1;
				} else {
					$error = 2;
				}
			} else {
				$error = 1;
			}

		}

		return $this -> render('ApplicationContactBundle:Default:index.html.twig', array('form' => $form -> createView(), 'success' => $success, 'error' => $error));

	}

	protected function parseBodyFormat($name, $email, $body, $subject) {

		$format = 'Van: ' . $name . ' < ' . $email . "> \n";
		$format .= 'Onderwerp: ' . $subject . "\n";
		$format .= 'IP: ' . $this -> container -> get('request') -> getClientIp() . "\n\n";
		$format .= "Bericht inhoud:\n " . $body . "\n\n";

		$format .= "-- \n Deze mail is verzonden via contact form op: " . $this -> request -> getHost();

		return $format;

	}

}
