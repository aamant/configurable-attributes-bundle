<?php
/**
 *
 * @author Arnaud Amant <contact@arnaudamant.fr>
 */

namespace Aamant\ConfigurableAttributesBundle\Event;


use Aamant\ConfigurableAttributesBundle\Entity\Option;
use Symfony\Component\EventDispatcher\Event;

class AttributesOptionChangedEvent extends Event
{
    /**
     * @var Option
     */
    private $option;

    public function __construct(Option $option)
    {
        $this->option = $option;
    }

    /**
     * @return Option
     */
    public function getOption()
    {
        return $this->option;
    }
}