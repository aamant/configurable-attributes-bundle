<?php

namespace Aamant\ConfigurableAttributesBundle\Form;

use Aamant\ConfigurableAttributesBundle\Entity\AttributeDefinition;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeDefinitionForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /** @var AttributeDefinition $entity */
            $entity = $event->getData();
            $builder = $event->getForm();

            $builder
                ->add('name', null, [
                    'disabled' => ($entity->getId() != null)
                ])
                ->add('label')
                ->add('options', CollectionType::class, [
                    'entry_type'    => AttributeDefinitationOptionType::class,
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'by_reference'  => false,
                ])
            ;
        });
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Aamant\ConfigurableAttributesBundle\Entity\AttributeDefinition'
        ));
    }
}
