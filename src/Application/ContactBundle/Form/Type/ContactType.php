<?php

namespace Application\ContactBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactType extends AbstractType {
	public function buildForm(FormBuilderInterface $builder, array $options) {

		$builder -> add('name', 'text', array('label' => 'Uw naam: ', 'attr' => array('class' => 'textfield')));

		$builder -> add('email', 'email', array('label' => 'Uw email: ', 'attr' => array('class' => 'textfield')));

		$builder -> add('subject', 'text', array('label' => 'Onderwerp: ', 'attr' => array('class' => 'textfield')));

		$builder -> add('message', 'textarea', array('label' => 'Bericht: ', 'attr' => array('class' => 'textfield textarea', 'width' => '500', 'height' => '100')));
	}

	public function getName() {
		return 'contact';
	}

}
