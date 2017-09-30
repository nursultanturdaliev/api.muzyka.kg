<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class PlaylistAdmin extends AbstractAdmin
{

    public function configure()
    {
        parent::configure();

        $this->datagridValues['_sort_by'] = 'id';
        $this->datagridValues['_sort_order'] = 'DESC';
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('profilePhoto', 'string', array(
                'template' => 'AppBundle:Admin:playlist_profile_list.html.twig'
                ))
            ->add('name', null, array('label' => 'Аталышы'))
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
            ->with('Негизги маалыматтар', array('class' => 'col-md-12'))
                ->add('name', null, array(
                    'label' =>'Аталышы'
                ))
                ->add('songs', 'sonata_type_model' , array(
                    'label' => 'Ырлар',
                    'required' => false,
                    'by_reference' => false,
                    'multiple' => true,
                    'class' => 'AppBundle\Entity\Song',
                ))
            ->end()
            ->with('Cover Photo', array('class' => 'col-md-6'))
                ->add('coverPhoto', 'file', array(
                    'required' => false,
                    'label' => false,
                    'attr' => array('class' => 'playlist-cover-photo')
                ))
            ->end()
            ->with('Profile Photo', array('class' => 'col-md-6'))
                ->add('profilePhoto', 'file', array(
                    'required' => false,
                    'label' => false,
                    'attr' => array('class' => 'playlist-profile-photo')
                ))
            ->end()
            ->with('SEO', array('class' => 'col-md-12'))
                ->add('keywords', null, array())
                ->add('description', null, array())
            ->end()

        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('Негизги маалыматтар', array('class' => 'col-md-12'))
                ->add('name', null, array(
                    'label' =>'Аталышы'
                ))
                ->add('songs', 'string' , array(
                    'label' => 'Ырлар',
                    'template' => 'AppBundle:Admin:playlist_song_show.html.twig'
                ))
            ->end()
            ->with('Дата', array('class' => 'col-md-12'))
                ->add('createdAt')
                ->add('updatedAt')
            ->end()
            ->with('SEO', array('class' => 'col-md-12'))
                ->add('keywords', null, array())
                ->add('description', null, array())
            ->end()
        ;
    }
}
