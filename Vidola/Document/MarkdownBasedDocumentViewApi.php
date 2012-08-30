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
class MarkdownBasedDocumentViewApi implements ViewApi
{
	const VIEW_ACCESS_NAME = 'document';

	private $doc;

	private $currentPage;

	public function __construct(MarkdownBasedDocument $doc, $currentPage)
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
		return $this->doc->getContent($this->currentPage);
	}

	/**
	 * The url pointing to the previous page or null if there is no previous page.
	 * 
	 * @return string|null
	 */
	public function previousPageLink()
	{
		return $this->doc->getPreviousFileLink($this->currentPage);
	}

	/**
	 * The title of the previous page or null if there is no previous page.
	 * 
	 * @return string|null
	 */
	public function previousPageTitle()
	{
		return $this->doc->getPreviousFileTitle($this->currentPage);
	}

	/**
	 * The url pointing to the next page or null if there is no next page.
	 *
	 * @return string|null
	 */
	public function nextPageLink()
	{
		return $this->doc->getNextFileLink($this->currentPage);
	}

	/**
	 * The title of the next page or null if there is no next page.
	 *
	 * @return string|null
	 */
	public function nextPageTitle()
	{
		return $this->doc->getNextFileTitle($this->currentPage);
	}

	/**
	 * The title of the current page.
	 */
	public function currentPageTitle()
	{
		return $this->doc->getFileTitle($this->currentPage);
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
			$toc->ownerDocument->appendChild($toc);
			return $toc->ownerDocument->saveHtml();
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
		return $this->doc->getStartFileLink($this->currentPage);
	}

	/**
	 * The url pointing to a given page.
	 * 
	 * @param string $page
	 */
	public function getPageLink($page)
	{
		return $this->linkTo($page);
	}

	/**
	 * Point to the source relative to the current document.
	 * 
	 * @param string $source
	 */
	public function linkTo($source)
	{
		return $this->doc->getLink($source, $this->currentPage);
	}

	/**
	 * The title of a given page.
	 * 
	 * @param string $page
	 */
	public function getPageTitle($page)
	{
		return $this->doc->getFileTitle($page);
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