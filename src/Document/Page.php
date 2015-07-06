<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

/**
 * @package Vidola
 */
class Page implements Linkable
{
	private $url;

	private $rawContent;

	public function __construct($url, $rawContent)
	{
		$this->url = $url;
		$this->rawContent = $rawContent;
	}

	/**
	 * @see Vidola\Document.Resource::getUrl()
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Get content as in file.
	 *
	 * @return string
	 */
	public function getRawContent()
	{
		return $this->rawContent;
	}
}