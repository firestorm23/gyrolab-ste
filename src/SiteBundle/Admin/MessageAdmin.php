<?php

namespace SiteBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class MessageAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('dateCreated')
            ->add('text')
            ->add('orderNumber')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('dateCreated')
            ->add('text')
            ->add('name', 'string', array('editable' => true))
            ->add('email', 'string', array('editable' => true))
            ->add('orderNumber')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
            ->add('replies', 'entity', array('multiple' => true))
            ->add('repliedTo', 'sonata_type_model');
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('text', 'textarea', array('attr' => array('rows' => "10")))
            ->add('name')
            ->add('dateCreated', 'sonata_type_datetime_picker')
            ->add('email')
            ->add('orderNumber')
            ->add('article', 'sonata_type_model');
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('dateCreated')
            ->add('text')
            ->add('orderNumber')
        ;
    }
}
