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
            ->add('extendedName')
            ->add('body')
            ->add('dateAdded', 'date')
            ->add('dateShowStart', 'date')
            ->add('dateShowEnd', 'date')
            ->add('sort', 'integer', array('editable' => true))
            ->add('slug', 'string', array('editable' => true))
            ->add('active', 'boolean', array('editable' => true))
            ->add('mainTag', 'sonata_type_model');
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Основные поля')
            ->add('name')
            ->add('extendedName', 'textarea', array('required' => false))
            ->add('body', 'textarea',array('attr' => array(
                'class' => 'tinymce',
                'data-theme' => 'simple',
                'cols' => "150",
                'rows' => "30"
            )))
            ->add('previewText', 'textarea',array('attr' => array(
                'class' => 'tinymce',
                'data-theme' => 'simple',
                'cols' => "150",
                'rows' => "20"
            )))
            ->end()->end()
            ->tab('Файлы')
            ->with('Изображение', array('class' => 'col-md-6'))
            ->add('image', new FileType(), array('required' => false))
            ->end()
            ->with('Документация', array('class' => 'col-md-6'))
            ->add('documentationFiles', 'collection', array('required' => false, 'type' => new FileType(),     'allow_add' => true,
                'allow_delete' => true))
            ->end()
            ->end()
            ->tab('Служебные')
            ->add('active', 'checkbox', array('required' => false))
            ->add('sort', 'integer', array('required' => false))
            ->add('slug',  'text', array('required' => false))
            ->add('dateAdded', 'sonata_type_date_picker', array('read_only' => true))
            ->add('dateShowStart', 'sonata_type_date_picker')
            ->add('dateShowEnd', 'sonata_type_date_picker', array('required' => false))
            ->end();
//            ->add('name')
//            ->add('body', 'textarea',             array('attr' => array(
//                'class' => 'tinymce',
//                'data-theme' => 'simple',
//                'cols' => "150",
//                'rows' => "30"
//            )))
//            ->add('dateAdded', 'sonata_type_date_picker')
//            ->add('image', new FileType(), array('required' => false))
//            ->add('topBannerSort', 'integer', array('required' => false))
//            ->add('recommendedSort', 'integer', array('required' => false))
//            ->add('viewCount', 'integer', array('required' => false))
//            ->add('topSort', 'integer' ,array('required' => false))
//            ->add('sort', 'integer')
//            ->add('tags', 'sonata_type_model', array('multiple' => true))
//            ->add('mainTag', 'sonata_type_model');
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
            ->add('extendedName')
            ->add('sort')
            ->add('slug')
            ->add('dateAdded')
            ->add('dateShowStart')
            ->add('dateShowEnd')
        ;
    }
}
