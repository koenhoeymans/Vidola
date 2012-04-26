<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
class SimplePage implements Page
{
	private $content = '';

	private $filename = '';

	private $title = '';

	private $previousPage = false;

	private $nextPage = false;

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setFilename($filename)
	{
		$this->filename = $filename;
	}

	public function getFilename()
	{
		return $this->filename;
	}

	public function setTitle($title)
	{
		$this->title = $title;
	}

	public function getTitle()
	{
		return $this->title;
	}

	public function setNextPageName($nextPageName)
	{
		$this->nextPage = $nextPageName;
	}

	public function getNextPageName()
	{
		return $this->nextPage;
	}

	public function setPreviousPageName($previousPageName)
	{
		$this->previousPage = $previousPageName;
	}

	public function getPreviousPageName()
	{
		return $this->previousPage;
	}
}