<?php
/**
 * Created by PhpStorm.
 * User: nursultan
 * Date: 11/25/16
 * Time: 2:13 AM
 */

namespace AppBundle\Admin;


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SongAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $form)
    {
        $form->add('title','text');
        $form->add('artist','sonata_type_model',array(
            'class'=>'AppBundle\Entity\Artist'
        ));
        $form->add('published');
        $form->add('duration');
        $form->add('oldUrl');
        $form->add('lyrics');
    }

    protected function configureListFields(ListMapper $list)
    {
        $list->addIdentifier('title');
        $list->add('artist');
        $list->add('published');
        $list->add('duration');
        $list->add('createdAt');
        $list->add('updatedAt');
        $list->add('likes');
        $list->add('countDownload');
    }

    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter->add('title');
        $filter->add('artist','doctrine_orm_model_autocomplete',array(),null,array(
            'property'=>'name'
        ));
    }


}