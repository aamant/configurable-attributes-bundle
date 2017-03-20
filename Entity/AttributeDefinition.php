<?php

namespace Aamant\ConfigurableAttributesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Attribute
 *
 * @ORM\Table(name="attribute_definition")
 * @ORM\Entity(repositoryClass="Aamant\ConfigurableAttributesBundle\Repository\AttributeDefinitionRepository")
 */
class AttributeDefinition
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @ORM\OneToMany(
     *     targetEntity="\Aamant\ConfigurableAttributesBundle\Entity\Option",
     *     mappedBy="attributeDefinition",
     *     cascade={"persist", "remove"}, orphanRemoval=true
     * )
     */
    private $options;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Attribute
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set label
     *
     * @param string $label
     *
     * @return Attribute
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Add option
     *
     * @param \Aamant\ConfigurableAttributesBundle\Entity\Option $option
     *
     * @return Attribute
     */
    public function addOption(\Aamant\ConfigurableAttributesBundle\Entity\Option $option)
    {
        $this->options[] = $option;
        $option->setAttributeDefinition($this);
        return $this;
    }

    /**
     * Remove option
     *
     * @param \Aamant\ConfigurableAttributesBundle\Entity\Option $option
     */
    public function removeOption(\Aamant\ConfigurableAttributesBundle\Entity\Option $option)
    {
        $this->options->removeElement($option);
        $option->setAttributeDefinition(null);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $option
     * @return Option|null
     */
    public function getOptionById(Option $option = null)
    {
        foreach ($this->getOptions() as $element){
            if ($element->getId() == $option->getId()){
                return $option;
            }
        }
    }
}
