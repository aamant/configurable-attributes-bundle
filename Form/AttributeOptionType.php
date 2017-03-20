<?php
/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

namespace Aamant\ConfigurableAttributesBundle\Form;


use Aamant\ConfigurableAttributesBundle\Entity\Option;
use Aamant\ConfigurableAttributesBundle\Repository\OptionRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeOptionType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class'         => 'AamantConfigurableAttributesBundle:Option',
            'choice_label'  => 'value',
            'query_builder' => function (Options $options) {

                $definition = $options['definition'];

                return function (OptionRepository $er) use ($definition) {
                    return $er->createQueryBuilder('o')
                        ->join('o.attributeDefinition', 'd')
                        ->where('d.name = :definition')
                        ->setParameter('definition', $definition);
                };
            },
            'choice_value'  => function(Option $option = null){
                if (!$option) return;
                return $option->getId();
            },
        ]);

        $resolver
            ->setRequired(['definition'])
            ->setDefaults(array(
                'invalid_message' => 'The entity does not exist.',
            ))
        ;
    }

    public function getParent()
    {
        return EntityType::class;
    }

    public function getName()
    {
        return 'attribute_option';
    }
}