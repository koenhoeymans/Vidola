<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface Linkable
{
	/**
	 * Get URL relative to this documentation.
	 * 
	 * @return string
	 */
	public function getUrl();
}