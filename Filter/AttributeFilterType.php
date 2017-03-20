<?php
/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

namespace Aamant\ConfigurableAttributesBundle\Filter;


use Aamant\ConfigurableAttributesBundle\Entity\Option;
use Aamant\ConfigurableAttributesBundle\Repository\OptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityRepository;
use Lexik\Bundle\FormFilterBundle\Filter\Query\QueryInterface;
use Symfony\Component\Form\AbstractType;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class'         => 'AamantConfigurableAttributesBundle:Option',
            'apply_filter' => array($this, 'filter'),
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

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return Filters\EntityFilterType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'filter_attribute';
    }

    public function filter(QueryInterface $filterQuery, $field, $values)
    {
        if (empty($values['value'])
            || ($values['value'] instanceof ArrayCollection && $values['value']->count() == 0) ) {
            return null;
        }

        $paramName = sprintf('b_%s', str_replace('.', '_', $field));

        $expression = $filterQuery->getExpr()->eq($field, ':'.$paramName);
        $parameters = array($paramName => $values['value']);

        return $filterQuery->createCondition($expression, $parameters);
    }
}