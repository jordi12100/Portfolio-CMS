<?php

namespace Application\TwitterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function getTweetsAction()
    {
		$tweetarray = $this -> getDoctrine() -> getRepository('ApplicationTwitterBundle:Tweet') -> findAllOrderedByDateAdded();
		
		$tweetData = array();
		
		foreach($tweetarray AS $key => $tweet) {
			$tweetData[$key]['tweet'] = $tweet -> getTweet();
			$tweetData[$key]['user'] = $tweet -> getUser();
		}
		
        return $this->render('ApplicationTwitterBundle:Default:index.html.twig', array('items' => $tweetData));
    }
	
}
