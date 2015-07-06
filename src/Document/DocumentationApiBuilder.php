<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface DocumentationApiBuilder
{
	/**
	 * @param Page $page
	 * @return Vidola\View\ViewApi
	 */
	public function buildApi(Page $page);
}