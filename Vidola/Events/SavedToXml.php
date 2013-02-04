<?php

/**
 * @package Vidola
 */
namespace Vidola\Events;

use Vidola\Plugin\Event;

/**
 * @package Vidola
 */
class SavedToXml implements Event
{
	private $xml;

	public function __construct($xml)
	{
		$this->xml = $xml;
	}

	public function getXmlString()
	{
		return $this->xml;
	}

	public function setXmlString($xml)
	{
		$this->xml = $xml;
	}
}