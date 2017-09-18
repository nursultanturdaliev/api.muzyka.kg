<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


class SongAdmin extends AbstractAdmin
{

    public function configure()
    {
        parent::configure();

        $this->datagridValues['_sort_by'] = 'title';
        $this->datagridValues['_sort_order'] = 'ASC';
    }


    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('uuid', null, array('label' => 'UUID'))
            ->add('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Аталышы'))
            ->add('duration', null, array('label' => 'Убактысы'))
            ->add('published', null, array('label' => 'Жарыяланган'))
            ->add('downloadable', null, array('label' => 'Көчүрүү'))
            ->add('countDownload', null, array('label' => 'Саны'))
            ->add('countPlay', null, array('label' => 'Ырдоо саны'))
            ->add('likes', null, array('label' => 'Жакты'))
            ->add('lyrics', null, array('label' => 'Текстти'))
            ->add('isNew', null, array('label' => 'Жаны'))
            ->add('youtube', null, array('label' => 'Ютуб'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Аталышы'))
            ->add('duration', null, array('label' => 'Убактысы'))
            ->add('artists')
            //->add('countPlay', null, array('label' => 'Ырдоо саны'))
            ->add('published', null, array(
                'label' => 'Жарыяланган',
                'editable' => true
            ))
            ->add('isNew', null, array(
                'label' => 'Жаны',
                'editable' => true
            ))
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
            ->add('title', null, array('label' => 'Аталышы'))
            ->add('youtube', null, array('label' => 'Ютуб'))
            ->add('lyrics', null, array('label' => 'Текстти'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('uuid', null, array('label' => 'UUID'))
            ->add('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Аталышы'))
            ->add('duration', null, array('label' => 'Убактысы'))
            ->add('published', null, array('label' => 'Жарыяланган'))
            ->add('downloadable', null, array('label' => 'Көчүрүү'))
            ->add('countDownload', null, array('label' => 'Саны'))
            ->add('countPlay', null, array('label' => 'Ырдоо саны'))
            ->add('likes', null, array('label' => 'Жакты'))
            ->add('isNew', null, array('label' => 'Жаңы'))
            ->add('createdAt', null, array('label' => 'Түзүлгөн датасы'))
            ->add('updatedAt', null, array('label' => 'Өзгөргөн датасы'))
            ->add('deletedAt', null, array('label' => 'Өчүрүлгөн датасы'))
            ->add('youtube', null, array('label' => 'Ютуб'))
            ->add('writtenBy', null, array('label' => 'Автору'))
            ->add('composedBy', null, array('label' => 'Композитору'))
            ->add('lyrics', null, array('label' => 'Текстти'))
        ;
    }
}
