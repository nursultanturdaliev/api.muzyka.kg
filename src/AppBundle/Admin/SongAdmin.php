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
            ->add('artists', null, array('label' => 'Аткаруучулар'))
            ->add('uuid', null, array('label' => 'UUID'))
            ->add('id', null, array('label' => 'ID'))
            ->add('title', null, array('label' => 'Аталышы'))
            ->add('slug', null, array('label' => 'Slug'))
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
            /*->add('uuid', 'string', array(
                'label' => 'Play',
                'template' => 'AppBundle:Admin:play_list.html.twig'
            ))*/

            //->add('slug', null, array('label' => 'Аталышы'))
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
            ->add('countPlay', null, array('label' => 'Play'))
            ->add('likes', null, array('label' => 'Жакты'))

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
            ->with('Негизги маалыматтар', array('class' => 'col-md-6'))
                ->add('title', null, array('label' => 'Аталышы'))
                ->add('slug', null, array(
                    'label' => 'Slug',

                    'help' => '<p style="color: red">yrdyn-aty-kichine-tamga-tire-menen.<br>Эгер андай аталыш бар болсо ырчынын аты кошо жазылат</p>'
                ))
                ->add('artists', null, array('label' => 'Аткаруучулар'))
                ->add('writtenBy', null, array('label' => 'Сөзү'))
                ->add('composedBy', null, array('label' => 'Обону'))
                ->add('youtube', null, array('label' => 'Ютуб'))
                //->add('favourites', null, array('label' => 'Фаворит'))
                ->add('genres', null, array('label' => 'Жанр'))
            ->end()
            ->with('Текстти', array('class' => 'col-md-6'))
                ->add('lyrics', 'textarea', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array('rows' => '20', 'style' => 'height:548px')
                    ))
            ->end()


            ->with('Audio', array('class' => 'col-md-6'))
            ->add('audioFile', 'file', array(
                'label' => false,
                'required' => false,
                'attr' => array('class' => 'file-input-audio')
            ))
            ->end()


            ->with('Сүрөтү', array('class' => 'col-md-6'))
                ->add('coverPhoto', 'file', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array('class' => 'file-input-cover-photo')
                ))
            ->end()

            ->with('Кошумча маалыматтар', array('class' => 'col-md-6'))
                ->add('uuid', null, array('label' => 'UUID'))
                ->add('duration', null, array('label' => 'Убактысы'))
                ->add('countPlay', null, array('label' => 'Ырдоо саны'))
                ->add('likes', null, array('label' => 'Жакты'))

                ->add('keywords', null, array())
                ->add('description', null, array())

            ->end()
            ->with('Даталар', array('class' => 'col-md-6'))
                ->add('countDownload', null, array('label' => 'Көчүрүүнүн саны'))
                ->add('downloadable', null, array('label' => 'Көчүрүү'))
                ->add('isNew', null, array('label' => 'Жаңы'))
                ->add('published', null, array('label' => 'Жарыяланган'))
                ->add('createdAt', null, array('label' => 'Түзүлгөн датасы'))
                ->add('releasedAt', null, array('label' => 'Released At'))
                ->add('updatedAt', null, array('label' => 'Өзгөргөн датасы'))
                ->add('deletedAt', null, array('label' => 'Өчүрүлгөн датасы'))
                //->add('histories', null, array('label' => 'История'))
            ->end()


        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('Негизги маалыматтар', array('class' => 'col-md-6'))
                ->add('uuid', null, array('label' => 'UUID'))
                ->add('id', null, array('label' => 'ID'))
                ->add('title', null, array('label' => 'Аталышы'))
                ->add('slug', null, array('label' => 'Slug'))
                ->add('artists', null, array('label' => 'Аткаруучулар'))
                ->add('writtenBy', null, array('label' => 'Сөзү'))
                ->add('composedBy', null, array('label' => 'Обону'))
                ->add('youtube', null, array('label' => 'Ютуб'))
                //->add('favourites', null, array('label' => 'Фаворит'))
                ->add('genres', null, array('label' => 'Жанр'))
                ->add('duration', null, array('label' => 'Убактысы'))
                ->add('countPlay', null, array('label' => 'Ырдоо саны'))
                ->add('likes', null, array('label' => 'Жакты'))
                ->add('countDownload', null, array('label' => 'Саны'))
                ->add('downloadable', null, array('label' => 'Көчүрүү'))
                ->add('isNew', null, array('label' => 'Жаңы'))
                ->add('published', null, array('label' => 'Жарыяланган'))
                ->add('createdAt', null, array('label' => 'Түзүлгөн датасы'))
                ->add('releasedAt', null, array('label' => 'Released At'))
                ->add('updatedAt', null, array('label' => 'Өзгөргөн датасы'))
                ->add('deletedAt', null, array('label' => 'Өчүрүлгөн датасы'))
                //->add('histories', null, array('label' => 'История'))
            ->end()
            ->with('Текстти', array('class' => 'col-md-6'))
            ->add('lyrics', 'textarea', array(
                'label' => false,
                'attr' => array('rows' => '20', 'style' => 'height:505px')
            ))
            ->end()
        ;
    }
}
