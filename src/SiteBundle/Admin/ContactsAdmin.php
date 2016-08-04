<?php

namespace SiteBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ContactsAdmin extends AbstractAdmin
{
    public function configure()
    {
        $this->setLabel('Контакты');
    }
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('code')
            ->add('value')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name', 'string', array('label' => 'Название'))
            ->add('code', 'string', array('label' => 'Код'))
            ->add('value', 'string', array('label' => 'Значение', 'editable' => true))
            ->add('htmlValue', 'html', array('label' => 'Дополнительное HTML значение'))
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
            ->with('Данные', array('class' => 'col-md-6'))
            ->add('name', 'text', array('label' => 'Название'))
            ->add('value', 'text', array('required' => false, 'label' => 'Значение'))
            ->add('htmlValue', 'textarea', array(
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'simple',
                    'cols' => "150",
                    'rows' => "30"
                ),
                'required' => false,
                'label' => 'Дополнительное HTML значение'
            ))
            ->end()
            ->with('Служебные', array('class' => 'col-md-6'))
            ->add('code', 'text', array('label' => 'Код'))
            ->end()
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
            ->add('code')
            ->add('value')
        ;
    }
}
