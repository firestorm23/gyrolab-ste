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
    public function configure()
    {
        $this->setLabel('Новости');
    }

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
            ->add('name','string' , array('editable' => true, 'label' => 'Название'))
            ->add('extendedName','string' , array('editable' => true, 'label' => 'Дополнительное название'))
//            ->add('body','html' , array('editable' => true, 'label' => 'Описание'))
            ->add('dateAdded', 'date', array('label' => 'Дата добавления'))
            ->add('dateShowStart', 'date', array('label' => 'Дата начала показа'))
            ->add('dateShowEnd', 'date', array('label' => 'Дата коца показа'))
            ->add('sort', 'integer', array('editable' => true, 'label' => 'Дата коца показа'))
            ->add('slug', 'string', array('editable' => true, 'label' => 'Дата коца показа'))
            ->add('active', 'boolean', array('editable' => true,  'label' => 'Дата коца показа'))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->tab('Основные поля')
            ->add('name', 'text' , array('label' => 'Название'))
            ->add('extendedName', 'textarea', array('required' => false, 'label' => 'Доп. название'))
            ->add('body', 'textarea',array('attr' => array(
                'class' => 'tinymce',
                'data-theme' => 'simple',
                'cols' => "150",
                'rows' => "30"
            ),
            'label' => 'Описание'))
            ->add('previewText', 'textarea',array('attr' => array(
                'class' => 'tinymce',
                'data-theme' => 'simple',
                'cols' => "150",
                'rows' => "20"
            ),  'label' => 'Описание для анонса'))
            ->end()->end()
            ->tab('Файлы')
            ->with('Изображение', array('class' => 'col-md-6'))
            ->add('image', new FileType(), array('required' => false, 'label' => 'Основное изображение'))
            ->end()
            ->with('Документация', array('class' => 'col-md-6'))
            ->add('documentationFiles', 'collection', array('required' => false, 'label' => 'Документцы', 'type' => new FileType(),     'allow_add' => true,
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
