<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/4/17
 * Time: 11:30 PM
 */

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArtistAdmin extends AbstractAdmin
{
	protected function configureFormFields(FormMapper $form)
	{
		$form->add('name');
		$form->add('lastname');
		$form->add('birthday', 'date', array(
			'years'      => range(1800, 2016),
			'empty_data' => new \DateTime('1990-01-01')
		));
		$form->add('gender', 'choice', array(
			'choices' => array('M' => 'Male', 'F' => 'Female')
		));
	}

	protected function configureListFields(ListMapper $list)
	{
		$list->addIdentifier('name');
		$list->add('email');
		$list->add('lastname');
		$list->add('birthday');
		$list->add('gender');
	}

	protected function configureDatagridFilters(DatagridMapper $filter)
	{
		$filter->add('name');
	}


}