<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class FavouriteAdmin extends AbstractAdmin
{

/*    public function configure()
    {
        parent::configure();

        $this->datagridValues['_sort_by'] = 'name';
        $this->datagridValues['_sort_order'] = 'ASC';
    }*/


    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('user', null, array('label' => 'Колдонуучу'))
            ->add('song', null, array('label' => 'Ыр'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('user', null, array('label' => 'Колдонуучу'))
            ->add('song', null, array('label' => 'Ыр'))
            ->add('_action', null, array(
                'label' => 'Башкаруу',
                'actions' => array(
                    'edit' => array(),
                    'show' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('user', null, array('label' => 'Колдонуучу'))
            ->add('song', null, array('label' => 'Ыр'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('user', null, array('label' => 'Колдонуучу'))
            ->add('song', null, array('label' => 'Ыр'))
        ;
    }
}
