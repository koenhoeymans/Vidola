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

	public function previousPageName()
	{
		return $this->doc->getPreviousPageName($this->currentPage);
	}

	public function nextPageName()
	{
		return $this->doc->getNextPageName($this->currentPage);
	}

	public function title()
	{
		return $this->doc->getTitle($this->currentPage);
	}
}