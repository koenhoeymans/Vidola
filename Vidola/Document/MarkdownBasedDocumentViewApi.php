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

	public function previousPageLink()
	{
		return $this->doc->getPreviousFileLink($this->currentPage);
	}

	public function previousPageTitle()
	{
		return $this->doc->getPreviousFileTitle($this->currentPage);
	}

	public function nextPageLink()
	{
		return $this->doc->getNextFileLink($this->currentPage);
	}

	public function nextPageTitle()
	{
		return $this->doc->getNextFileTitle($this->currentPage);
	}

	public function pageTitle()
	{
		return $this->doc->getFileTitle($this->currentPage);
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
		return $this->doc->getStartFileLink();
	}

	public function getPageLink($page)
	{
		return $this->doc->getLink($page);
	}

	public function getPageTitle($page)
	{
		return $this->doc->getFileTitle($page);
	}

	public function getBreadCrumbs()
	{
		return $this->doc->getBreadCrumbs($this->currentPage);
	}
}