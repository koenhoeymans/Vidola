<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
interface PageList
{
	/**
	 * Adds next page to the list, specifying parentpage if any.
	 * 
	 * @param Page $page
	 * @param Page $parentPage
	 */
	public function add(Page $page, Page $parentPage = null);

	/**
	 * Gets a list of all pages in order of appearance.
	 * 
	 * @return array
	 */
	public function getPages();
}