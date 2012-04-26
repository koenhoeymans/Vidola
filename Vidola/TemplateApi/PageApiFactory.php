<?php

/**
 * @package Vidola
 */
namespace Vidola\TemplateApi;

use Vidola\Document\Page;

/**
 * @package
 */
class PageApiFactory
{
	public function createWith(Page $page)
	{
		return new \Vidola\TemplateApi\PageApi($page);
	}
}