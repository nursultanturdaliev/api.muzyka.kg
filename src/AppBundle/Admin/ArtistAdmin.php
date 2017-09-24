<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class ArtistAdmin extends AbstractAdmin
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
            ->add('name', null, array('label' => 'Аты-жөнү'))
            ->add('instagram', null, array('label' => 'Instagram'))
            ->add('profileLocal', 'doctrine_orm_string', array('label' => 'Сүрөтү'))

            ->add('gender', 'doctrine_orm_string', array(
                'label' => 'Жынысы'
                ),
                'choice',
                array('choices' => array('эркек' => 'm', 'аял' => 'f')
                )
            );

        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('profileLocal', 'string', array(
                'label' => 'Сүрөтү',
                'template' => 'AppBundle:Admin:image_list.html.twig'
            ))
            ->add('name', null, array('label' => 'Аты-жөнү'))
            ->add('gender', 'choice', array(
                'label' => 'Жынысы',
                'editable' => true,
                'choices'  => array(
                    'f' => "аял",
                    'm' => "эркек",
                    null => 'жок',

                ),
                ))
            ->add('instagram', 'string', array(
                'label' => 'Instagram',
                'template' => 'AppBundle:Admin:instagram_list.html.twig'
            ))
            ->add('_action', null, array(
                'label' => 'Башкаруу',
                'actions' => array(
                    'edit' => array(),
                    'show' => array(),
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
                ->add('name', null, array('label' => 'Аты-жөнү'))
                ->add('instagram', null, array('label' => 'Instagram'))
                ->add('profile', null, array('label' => 'Профил'))
            ->end()
            ->with('Сүрөтү', array('class' => 'col-md-6'))
                ->add('profileLocal', 'file', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array('class' => 'file-input')
                ))
            ->end()

            ->with('Кошумча маалыматтар', array('class' => 'col-md-12'))

                ->add('gender', ChoiceType::class, array(
                    'label' => 'Жынысы',
                    'required' => false,
                    'choices'  => array(
                        'аял' => "f",
                        'эркек' => "m",
                        'жок' => null,
                    ),
                ))
                ->add('debut', null, array('label' => 'Дебют'))
                ->add('email', null, array('label' => 'Email'))
                ->add('biography', null, array('label' => 'Биография'))
                ->add('deletedAt', null, array('label' => 'Өчүрүлгөн датасы'))
            ->end()
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id', null, array('label' => 'ID'))
            ->add('profileLocal', 'string', array(
                'label' => 'Сүрөтү',
                'template' => 'AppBundle:Admin:image_show.html.twig'
            ))
            ->add('name', null, array('label' => 'Аты-жөнү'))
            ->add('instagram', null, array('label' => 'Instagram'))
            ->add('profile', null, array('label' => 'Профил'))
            ->add('gender', null, array('label' => 'Жынысы'))
            ->add('debut', null, array('label' => 'Дебют'))
            ->add('email', null, array('label' => 'Email'))
            ->add('biography', null, array('label' => 'Биография'))
            ->add('deletedAt', null, array('label' => 'Өчүрүлгөн датасы'))
        ;
    }
}
