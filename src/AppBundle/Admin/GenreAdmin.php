<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/4/17
 * Time: 11:39 PM
 */

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class GenreAdmin extends AbstractAdmin
{
	protected function configureFormFields(FormMapper $form)
	{
		$form->add('name');
	}

	protected function configureListFields(ListMapper $list)
	{
		$list->add('name');
	}
}