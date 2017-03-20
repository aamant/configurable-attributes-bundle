<?php

namespace Aamant\ConfigurableAttributesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeDefinitationOptionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value')
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $option = $event->getData();
            $form = $event->getForm();

            $form
                ->add('key', null, [
                    'required' => false,
                    'disabled' => ($option && $option->getKey())? true: false,
                    'attr' => [
                        'placeholder' => 'auto generate if empty',
                    ]
                ])
                ->add('group')
            ;
        });
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aamant\ConfigurableAttributesBundle\Entity\Option'
        ));
    }
}
