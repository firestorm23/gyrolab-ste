<?php

namespace SiteBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class CategoryAdmin extends AbstractAdmin
{

    public function configure()
    {
        $this->setLabel('Категории');
    }
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('isMain')
            ->add('description')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name', 'string', array('label' => 'Название', 'editable' => true))
            ->add('slug', 'string', array('label' => 'URL код', 'editable' => true))
            ->add('isMain', 'boolean', array('label' => 'Главная категория', 'editable' => true))
//            ->add('description', 'html', array('label' => 'Описание'))
            ->add('sort', 'integer', array('editable' => true, 'label' => 'Индекс сортировки'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
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
            ->add('name', 'text', array('label' => 'Название'))
            ->add('isMain', 'checkbox', array('label' => 'Главная категория'))
            ->add('slug', 'text', array('label' => 'URL код'))
            ->add('description', 'textarea', array('label' => 'Описание'))
            ->add('sort', 'integer', array('label' => 'Индекс сортировки'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('isMain')
            ->add('description')
        ;
    }
}
