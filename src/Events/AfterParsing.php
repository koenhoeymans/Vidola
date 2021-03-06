<?php

/**
 * @package Vidola
 */
namespace Vidola\Events;

use Vidola\Plugin\Event;
use ElementTree\ElementTree;

/**
 * @package Vidola
 */
class AfterParsing implements Event
{
    private $parsedText;

    public function __construct(ElementTree $parsedText)
    {
        $this->parsedText = $parsedText;
    }

    public function getParsedText()
    {
        return $this->parsedText;
    }
}
