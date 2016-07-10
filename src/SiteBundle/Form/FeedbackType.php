<?php

/*
 * This file is part of the Mremi\ContactBundle Symfony bundle.
 *
 * (c) Rémi Marseille <marseille.remi@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SiteBundle\Form;

use Mremi\ContactBundle\Model\Contact;
use Mremi\ContactBundle\Provider\SubjectProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Contact type class.
 *
 * @author Rémi Marseille <marseille.remi@gmail.com>
 */
class FeedbackType extends AbstractType
{
    /**
     * @var SubjectProviderInterface
     */
    private $subjectProvider;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $captchaType;

    /**
     * Constructor.
     *
     * @param SubjectProviderInterface $subjectProvider A subject provider instance
     * @param string                   $class           The Contact class namespace
     * @param string                   $captchaType     The captcha type
     */
    public function __construct(SubjectProviderInterface $subjectProvider, $class, $captchaType)
    {
        $this->subjectProvider = $subjectProvider;
        $this->class           = $class;
        $this->captchaType     = $captchaType;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('name', 'text',  array(
                    'label' => 'Имя *',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label_attr' => array(
                        'class' => 'col-md-4 col-sm-3 control-label',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top'
                    )
                )
            )
            ->add('subject', 'text', array(
                    'label' => 'Тема *',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label_attr' => array(
                        'class' => 'col-md-4 col-sm-3 control-label',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top'
                    )
                )
            )
            ->add('email',     'email', array(
                    'label' => 'Email *',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label_attr' => array(
                        'class' => 'col-md-4 col-sm-3 control-label',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top'
                    )
                )
            )
            ->add('message', 'textarea', array(
                    'label' => 'Сообщение *',
                    'attr' => array(
                        'class' => 'form-control'
                    ),
                    'label_attr' => array(
                        'class' => 'col-md-4 col-sm-3 control-label',
                        'data-toggle' => 'tooltip',
                        'data-placement' => 'top'
                    )
                )
            );

        if ($this->captchaType) {
            $builder->add('captcha', $this->captchaType, array(
                'label' => 'Код безопасности *',
                'attr' => array(
                    'class' => 'form-control'
                ),
                'label_attr' => array(
                    'class' => 'col-md-4 col-sm-3 control-label',
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'top'
                )
            ));
        }

        $builder->add('save', 'submit', array('label' => 'Отправить', 'attr' => array('class' => 'btn-defult login-btn pull-right')));
    }

    /**
     * {@inheritdoc}
     *
     * @todo: Remove it when bumping requirements to Symfony 2.7+
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $this->configureOptions($resolver);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'         => $this->class,
            'intention'          => 'contact',
            'translation_domain' => 'MremiContactBundle',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'feedback_form';
    }
}
