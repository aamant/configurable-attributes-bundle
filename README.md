# README

## Install

Install with composer

```composer require aamant/configurable-attributes-bundle```

Modify AppKernel.php

    $bundles = [
        ...
        new Aamant\ConfigurableAttributesBundle\AamantConfigurableAttributesBundle(),
        ...
    ];
    
Add routes

    configurable_attributes.attributes:
        resource: "@AamantConfigurableAttributesBundle/Resources/config/routing.xml"
        
## Use

Add relation to entity

    /**
     * @ORM\ManyToOne(targetEntity="\Aamant\ConfigurableAttributesBundle\Entity\Option")
     * @ORM\JoinColumn(name="relation_id", referencedColumnName="id", onDelete="SET NULL", nullable=true)
     */
    private $relation;
    
Form field

    $builder
        ...
        ->add('relation', \Aamant\ConfigurableAttributesBundle\Form\AttributeOptionType::class, [
            'definition'    => 'test',
            'placeholder'   => '',
            'required'      => false,
        ])
        ...
    ;