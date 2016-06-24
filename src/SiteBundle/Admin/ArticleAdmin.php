<?php

namespace SiteBundle\Admin;

use SiteBundle\Entity\File;
use SiteBundle\Entity\Tag;
use SiteBundle\Form\FileType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ArticleAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('body');
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
            ->add('id')
            ->add('name')
            ->add('body')
            ->add('dateAdded', 'date')
            ->add('topBannerSort', 'integer', array('editable' => true))
            ->add('recommendedSort', 'integer', array('editable' => true))
            ->add('topSort', 'integer', array('editable' => true))
            ->add('viewCount', 'integer', array('editable' => true))
            ->add('sort', 'integer', array('editable' => true))
            ->add('slug', 'string', array('editable' => true))
            ->add('allowComments', 'boolean', array('editable' => true))
            ->add('tags', 'entity', array('multiple' => true))
            ->add('replies', 'entity', array('multiple' => true))
            ->add('mainTag', 'sonata_type_model');
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name')
            ->add('body', 'textarea',             array('attr' => array(
                'class' => 'tinymce',
                'data-theme' => 'simple',
                'cols' => "150",
                'rows' => "30"
            )))
            ->add('dateAdded', 'sonata_type_date_picker')
            ->add('image', new FileType(), array('required' => false))
            ->add('topBannerSort', 'integer', array('required' => false))
            ->add('recommendedSort', 'integer', array('required' => false))
            ->add('viewCount', 'integer', array('required' => false))
            ->add('topSort', 'integer' ,array('required' => false))
            ->add('sort', 'integer')
            ->add('tags', 'sonata_type_model', array('multiple' => true))
            ->add('mainTag', 'sonata_type_model');
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('name')
            ->add('body')
        ;
    }
}
