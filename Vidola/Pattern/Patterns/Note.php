<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
class Note extends SpecialSection
{
	/**
	 * @param string $identifier
	 * @param string $elementName
	 */
	public function __construct()
	{
		parent::__construct('!note', 'div', 'note');
	}
}