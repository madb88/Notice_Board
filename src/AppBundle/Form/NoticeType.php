<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class NoticeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('expirationDate')
            ->add('picture', 'file', ['required' => false, 'label' => 'Zdjęcie:', 'mapped' => false]) 
            ->add('category','entity', array(
                    'class' => 'AppBundle:Category',
                    'choice_label' => 'name'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Notice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'appbundle_notice';
    }
}
