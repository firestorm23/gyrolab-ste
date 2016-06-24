<?php

namespace SiteBundle\Admin;

use SiteBundle\Entity\File;
use SiteBundle\Entity\Category;
use SiteBundle\Form\FileType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class ProductAdmin extends AbstractAdmin
{
    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('sort')
            ->add('topSort')
            ->add('slug')
            ->add('translit_name')
            ->add('viewCount')
            ->add('dateAdded')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('name')
            ->add('description')
            ->add('sort')
            ->add('topSort')
            ->add('slug')
            ->add('viewCount')
            ->add('categories', 'entity', array('multiple' => true))
            ->add('dateAdded')
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
            ->add('name')
            ->add('description', 'textarea',array('attr' => array(
                'class' => 'tinymce',
                'data-theme' => 'simple',
                'cols' => "150",
                'rows' => "30"
            )))
            ->add('image', new FileType(), array('required' => false))
            ->add('galleryImages', 'collection', array('required' => false, 'type' => new FileType(),     'allow_add' => true,
                'allow_delete' => true))
            ->add('sort', 'integer', array('required' => false))
            ->add('topSort', 'integer',  array('required' => false))
            ->add('slug',  'text', array('required' => false))
            ->add('viewCount',  'integer', array('required' => false))
            ->add('categories', 'sonata_type_model', array('multiple' => true))
            ->add('dateAdded', 'sonata_type_date_picker', array('read_only' => true))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name')
            ->add('description')
            ->add('sort')
            ->add('topSort')
            ->add('slug')
            ->add('translit_name')
            ->add('viewCount')
            ->add('dateAdded', 'sonata_type_date_picker')
        ;
    }
}
