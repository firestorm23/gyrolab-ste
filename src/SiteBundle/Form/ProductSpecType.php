<?php

namespace SiteBundle\Form;

use SiteBundle\Services\Helper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSpecType extends AbstractType
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
            ->add('name', 'text', array('label' => 'Название'))
            ->add('code', 'text', array('label' => 'Идентификатор', 'required' => false))
            ->add('value', 'text', array('label' => 'Значение'))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SiteBundle\Entity\ProductSpec'
        ));
    }
    /**
     * @return string
     */
    public function getName()
    {
        return 'sitebundle_productspec';
    }

}
