<?php
/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

namespace Aamant\ConfigurableAttributesBundle\Event;


use Aamant\ConfigurableAttributesBundle\Entity\Attribute;
use Symfony\Component\EventDispatcher\Event;

class AttributeDefinitionChangeEvent extends Event
{
    /**
     * @var Attribute
     */
    private $attribute;
    private $delete;

    /**
     * AttributeDefinitionChangeEvent constructor.
     * @param Attribute $attribute
     */
    public function __construct(Attribute $attribute, $delete)
    {
        $this->attribute = $attribute;
        $this->delete = $delete;
    }

    /**
     * @return Attribute
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * @return boolean
     */
    public function isDelete()
    {
        return $this->delete;
    }

}