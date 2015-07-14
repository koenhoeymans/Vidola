<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use AnyMark\Pattern\Pattern;
use ElementTree\Element;

/**
 * @package Vidola
 */
class Header extends \AnyMark\Pattern\Patterns\Header
{
    protected $knownIds = array();

    public function handleMatch(
        array $match,
        Element $parent = null,
        Pattern $parentPattern = null
    ) {
        $header = parent::handleMatch($match, $parent, $parentPattern);
        $id = strtolower(preg_replace('@ @', '-', $header->getChildren()[0]->getValue()));
        $id = $this->createUniqueId($id);
        $header->setAttribute('id', $id);

        return $header;
    }

    protected function createUniqueId($id, $count = null)
    {
        if (!in_array($id, $this->knownIds)) {
            $this->knownIds[] = $id;
        } else {
            $count = ($count === null) ? 2 : $count++;
            $id = $this->createUniqueId($id.'-'.$count);
        }

        return $id;
    }
}
