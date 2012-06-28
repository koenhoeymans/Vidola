<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\View\ViewApi;

/**
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

	public function getName()
	{
		return self::VIEW_ACCESS_NAME;
	}

	public function currentPageContent()
	{
		return $this->doc->getContent($this->currentPage);
	}

	public function currentFilename()
	{
		// @todo
		die('@todo');
	}

	public function previousPageLink()
	{
		return $this->doc->getPreviousPageLink($this->currentPage);
	}

	public function previousPageName()
	{
		return $this->doc->getPreviousPageName($this->currentPage);
	}

	public function nextPageLink()
	{
		return $this->doc->getNextPageLink($this->currentPage);
	}

	public function nextPageName()
	{
		return $this->doc->getNextPageName($this->currentPage);
	}

	public function pageName()
	{
		return $this->doc->getPageName($this->currentPage);
	}

	public function pageHasToc()
	{
		return $this->toc() ? true : false;
	}

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

	public function startPageLink()
	{
		return $this->doc->getStartPageLink();
	}
}