<?php

namespace SiteBundle\Form;

use SiteBundle\Services\Helper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlockSortType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */

    /** @var Helper */
    private $helper;

    public function __construct(Helper $helper)
    {
        $this->helper = $helper;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', 'choice', array('label' => 'Идентификатор',
                'choices' => array(
                    'top_slider_main' => 'В верхнем слайдере на главной',
                    'middle_blocks_main' => 'Блоки "Преимущества", на главной',
//                    'tab_main' => 'Табы на главной',
                    'social_block' => 'Блок соц.кнопок'
                )
            ))
            ->add('sort', 'text', array('label' => 'Значение сортировки'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SiteBundle\Entity\BlockSort'
        ));
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'sitebundle_blocksort';
    }

}
