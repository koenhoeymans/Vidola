<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\View\ViewApi;

/**
 * The supported API that can be used for templates.
 * 
 * @package Vidola
 */
class MarkdownBasedDocumentationViewApi implements ViewApi
{
	const VIEW_ACCESS_NAME = 'document';

	private $doc;

	private $currentPage;

	public function __construct(MarkdownBasedDocumentation $doc, $currentPage)
	{
		$this->doc = $doc;
		$this->currentPage = $currentPage;
	}

	/**
	 * @see Vidola\View.ViewApi::getName()
	 */
	public function getName()
	{
		return self::VIEW_ACCESS_NAME;
	}

	/**
	 * The content for the current page after going through the parser.
	 * 
	 * @return string
	 */
	public function currentPageContent()
	{
		return $this->doc->getParsedContent($this->currentPage);
	}

	/**
	 * The url pointing to a given page relative to the current page.
	 *
	 * @param string $page
	 * @return string
	 */
	public function getPageLink($page)
	{
		return $this->doc->getLink($page, $this->currentPage);
	}

	/**
	 * The url pointing to the previous page or null if there is no previous page.
	 * 
	 * @return string|null
	 */
	public function previousPageLink()
	{
		$previousPage = $this->doc->getPreviousPage($this->currentPage);
		if ($previousPage)
		{
			return $this->doc->getLink($previousPage, $this->currentPage);
		}

		return null;
	}

	/**
	 * The url pointing to the next page or null if there is no next page.
	 *
	 * @return string|null
	 */
	public function nextPageLink()
	{
		$nextPage = $this->doc->getNextPage($this->currentPage);
		if ($nextPage)
		{
			return $this->doc->getLink($nextPage, $this->currentPage);
		}

		return null;
	}

	/**
	 * The url of the page that is the starting point of the documentation.
	 *
	 * @return string
	 */
	public function startPageLink()
	{
		return $this->doc->getLink($this->doc->getStartPage(), $this->currentPage);
	}

	public function linkTo($resource)
	{
		return $this->doc->getLink($resource, $this->currentPage);
	}

	/**
	 * The title of the current page.
	 */
	public function currentPageTitle()
	{
		return $this->doc->getPageTitle($this->currentPage);
	}

	/**
	 * The title of the previous page or null if there is no previous page.
	 * 
	 * @return string|null
	 */
	public function previousPageTitle()
	{
		$page = $this->doc->getPreviousPage($this->currentPage);
		return $page ? $this->doc->getPageTitle($page) : null;
	}

	/**
	 * The title of the next page or null if there is no next page.
	 *
	 * @return string|null
	 */
	public function nextPageTitle()
	{
		$page = $this->doc->getNextPage($this->currentPage);
		return $page ? $this->doc->getPageTitle($page) : null;
	}

	/**
	 * The title of a given page.
	 *
	 * @param string $page
	 * @return string
	 */
	public function getPageTitle($page)
	{
		return $this->doc->getPageTitle($page);
	}

	/**
	 * Answers the question 'Does this page has a table of contents?' If there
	 * are no headers there won't be a table of contents.
	 * 
	 * @return bool
	 */
	public function pageHasToc()
	{
		return $this->toc() ? true : false;
	}

	/**
	 * The table of contents for the current page, e.i. a HTML list of headers as a list
	 * and sublists if there are subheaders. If there is no table of contents it will
	 * return null.
	 * 
	 * @return string|null The HTML list with headers or null if there is not toc.
	 */
	public function toc()
	{
		$toc = $this->doc->getToc($this->currentPage);

		if ($toc)
		{
			return $toc->ownerDocument->saveXml($toc);
		}

		return null;
	}

	/**
	 * A list of files that lead to the current page.
	 * 
	 * @return array
	 */
	public function getBreadCrumbs()
	{
		return $this->doc->getBreadCrumbs($this->currentPage);
	}
}