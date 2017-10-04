<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class UserAdmin extends AbstractAdmin
{

    public function configure()
    {
        parent::configure();

        $this->datagridValues['_sort_by'] = 'name';
        $this->datagridValues['_sort_order'] = 'ASC';
    }


    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('username', null, array('label' => 'Фамилиясы'))
            ->add('email', null, array('label' => 'Фамилиясы'))
            ->add('firstName', null, array('label' => 'Аты'))
            ->add('lastName', null, array('label' => 'Фамилиясы'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('photo', 'string', array(
                'label' => 'Сүрөтү',
                'template' => 'AppBundle:Admin:user_image_list.html.twig',
            ))
            ->add('username', null, array('label' => 'Username'))
            ->add('firstName', null, array('label' => 'Аты'))
            ->add('lastName', null, array('label' => 'Фамилиясы'))
            ->add('favourites', 'string', array(
                'template' => 'AppBundle:Admin:playlist_songs_list.html.twig',
                'label'   => 'Ырлар'
            ))

            ->add('roles', 'string', array(
                'label' => 'Role',
                'template' => 'AppBundle:Admin:user_role_list.html.twig'
                ))

/*            ->add('_action', null, array(
                'label' => 'Башкаруу',
                'actions' => array(
                    'edit' => array(),
                    'show' => array(),
                    'delete' => array(),
                )
            ))*/
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
    }
}
