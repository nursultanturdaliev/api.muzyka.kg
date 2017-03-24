<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/4/17
 * Time: 11:41 PM
 */

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PlaylistAdmin extends AbstractAdmin
{
	protected function configureListFields(ListMapper $list)
	{
		$list->add('name');
	}

	protected function configureRoutes(RouteCollection $collection)
	{
		$collection->remove('create');
	}

}