<?php

namespace Application\MenuBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Application\MenuBundle\Entity\Menu;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller {
	
	public function getMenuAction() {

		$menuarray = $this -> getDoctrine() -> getRepository('ApplicationMenuBundle:Menu') -> findAllOrderedByOrder();

		$menudata = array();
		$routes = array();
		$router = $this -> container -> get('router');

		foreach ($router->getRouteCollection()->all() as $name => $route) {
			$routes[$name] = $route -> compile();
		}

		foreach ($menuarray as $key => $result) {

			$menudata[$key]['isRoute'] = false;

			if (stripos($result -> getLink(), 'http://') === 0 || stripos($result -> getLink(), 'https://') === 0) {

				$menudata[$key]['link'] = $link;

			} else {

				$link = $result -> getLink();

				if ($link[0] == '@') {
					$link = substr($result -> getLink(), 1);

					if (array_key_exists($link, $routes)) {
						$menudata[$key]['isRoute'] = true;
					} else {
						throw new \Exception('Invalid route: Route not found: ' . $link);
						$menudata[$key]['isRoute'] = false;
					}

					$menudata[$key]['link'] = $link;
				} else {
					$menudata[$key]['link'] = $this -> container -> getParameter('web_path') . $result -> getLink();
				}

			}

			$menudata[$key]['name'] = $result -> getName();
		}

		return $this -> render('ApplicationMenuBundle:Default:index.html.twig', array('items' => $menudata));
	}

}
