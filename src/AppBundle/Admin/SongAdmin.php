<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 11/25/16
 * Time: 2:13 AM
 */

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SongAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('title','text');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('title');
    }


}