<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Note extends Block
{
	/**
	 * @param string $identifier
	 * @param string $elementName
	 */
	public function __construct()
	{
		parent::__construct('note:', 'div', 'note');
	}
}