<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 1/4/17
 * Time: 11:30 PM
 */

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ArtistAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('name');
        $form->add('lastname');
        $form->add('birthday');
        $form->add('gender','choice',array(
            'choices'=>array('M'=>'Male','F'=>'Female')
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

}