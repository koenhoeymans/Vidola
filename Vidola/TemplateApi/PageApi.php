<?php

/**
 * @package Vidola
 */
namespace Vidola\TemplateApi;

use Vidola\View\TemplateBasedView\TemplateApi;
use Vidola\Document\Page;

/**
 * @package Vidola
 */
class PageApi extends TemplateApi
{
	private $page;

	public function __construct(Page $page)
	{
		$this->page = $page;
	}

	public function getName()
	{
		return 'page';
	}

	public function content()
	{
		return $this->page->getContent();
	}

	public function filename()
	{
		return $this->page->getFilename();
	}

	public function title()
	{
		return $this->page->getTitle();
	}

	public function nextPageName()
	{
		return $this->page->getNextPage();
	}

	public function previousPageName()
	{
		return $this->page->getPreviousPage();
	}
}