<?php

namespace Aamant\ConfigurableAttributesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AttributeValue
 *
 * @ORM\Table(name="attribute_option")
 * @ORM\Entity(repositoryClass="Aamant\ConfigurableAttributesBundle\Repository\OptionRepository")
 */
class Option
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="`key`", type="string", length=255)
     */
    private $key;

    /**
     * @var string
     *
     * @ORM\Column(name="`value`", type="string", length=255)
     */
    private $value;

    /**
     * @var string
     *
     * @ORM\Column(name="`_group`", type="string", length=255, nullable=true)
     */
    private $group;

    /**
     * @var Collection
     *
     * @ORM\ManyToOne(targetEntity="\Aamant\ConfigurableAttributesBundle\Entity\AttributeDefinition", inversedBy="options", cascade={"remove", "persist"})
     * @ORM\JoinColumn(name="attribute_definition_id", referencedColumnName="id", nullable=false)
     */
    private $attributeDefinition;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set key
     *
     * @param string $key
     *
     * @return Option
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Get key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Option
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set attributeDefinition
     *
     * @param \Aamant\ConfigurableAttributesBundle\Entity\AttributeDefinition $attributeDefinition
     *
     * @return Option
     */
    public function setAttributeDefinition(\Aamant\ConfigurableAttributesBundle\Entity\AttributeDefinition $attributeDefinition = null)
    {
        $this->attributeDefinition = $attributeDefinition;

        return $this;
    }

    /**
     * Get attributeDefinition
     *
     * @return \Aamant\ConfigurableAttributesBundle\Entity\AttributeDefinition
     */
    public function getAttributeDefinition()
    {
        return $this->attributeDefinition;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * Set group
     *
     * @param string $group
     *
     * @return Option
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }
}
