<?php

namespace SiteBundle\Admin;

use Genemu\Bundle\FormBundle\Form\JQuery\DataTransformer\ArrayToStringTransformer;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\AdminBundle\Show\ShowMapper;
use SiteBundle\Form\BlockSortType;
use SiteBundle\Form\FileType;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;

class BlockAdmin extends AbstractAdmin
{

    public function configure()
    {
        $this->setLabel('Текстовые блоки');
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id')
            ->add('name')
            ->add('body')
            ->add('blockSort')
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
            ->add('body', 'string', array('label' => 'Текст блока'))
            ->add('extendedBody', 'string', array('label' => 'Дополнительный текстовый блок'))
            ->add('link', 'string', array('label' => 'Ссылка', 'editable' => true))
            ->add('blockSort', 'entity', array('multiple' => true, 'label' => 'Индексы сортировок'))
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
            ->with('Контент', array('class' => 'col-md-6'))
            ->add('name', 'text', array('label' => 'Название'))
            ->add('body', 'textarea', array(
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'simple',
                    'cols' => "150",
                    'rows' => "30"
                ),
                'label' => 'Текст блока'
            ))
            ->add('extendedBody', 'textarea', array('required' => false, 'label' => 'Дополнительный текстовый блок'))
            ->add('link', 'text', array('label' => 'Ссылка', 'required' => false))
            ->end()
            ->with('Параметры сортировки', array('class' => 'col-md-6'))
            ->add('blockSort',  'collection', array(
                'type' => new BlockSortType(
                    $this->getConfigurationPool()->getContainer()->get('helper')
                ),
                'allow_add' => true,
                'allow_delete' => true,
                'required' => true,
                'label' => false,
                'help' => 'Список параметров для вставки блоков в нужные места на сайте.
                За конкретное место отвечает поле \'Идентификатор\', идентификаторы зарезервированы в движке,
                \'Значение сортировки\' отвечает за порядок вывода блока <br><br><i>Пример: Вы хотите, поставить блок
                 с заполненным контентом в слайдер на главной странице. Для этого нужно выбрать соответствующий <b>Идентификатор</b> и проставить <b>Значение Сортировки</b>.
                На основе этих параметров блоки выведуться в желаемом месте и отсортируются с задаными значениями</i>',
            ))
            ->end()

            ->with('Медиа', array('class' => 'col-md-6'))
            ->add('image', new FileType(), array('required' => false, 'label' => 'Изображение'))->end()
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
            ->add('body')
            ->add('blockSort')
        ;
    }
}
