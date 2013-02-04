<?php

namespace Company\Vidola;

use Vidola\Plugin\Plugin;
use Vidola\Plugin\EventMapper;
use Vidola\Events\SavedToXml;

class TextModPlugin extends Plugin
{
	public function register(EventMapper $mapper)
	{
		$mapper->register(
			'Vidola\\Events\\SavedToXml',
			function(SavedToXml $event) {
				$this->handleEvent($event);
			}
		);
	}

	public function handleEvent(SavedToXml $event)
	{
		$str = preg_replace('@ @', '-', $event->getXmlString());
		$event->setXmlString($str);
	}
}