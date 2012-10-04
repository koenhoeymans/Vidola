<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\Parser\Parser;
use Vidola\Util\ContentRetriever;

/**
 * @package Vidola
 */
class MdContent implements Content
{
	private $parser;

	private $retriever;

	/**
	 * $file => $parsedContent
	 * 
	 * @var array
	 */
	private $parsedCache = array();

	public function __construct(Parser $parser, ContentRetriever $retriever)
	{
		$this->parser = $parser;
		$this->retriever = $retriever;
	}

	/**
	 * Get content parsed by the different patterns.
	 * 
	 * @param string $page
	 * @return \DomDocument
	 */
	public function getParsedContent($page)
	{
		if (isset($this->parsedCache[$page]))
		{
			return $this->parsedCache[$page];
		}

		$content = $this->getRawContent($page);
		$content = $this->parser->parse($content);
		$this->parsedCache[$page] = $content;

		return $content;
	}

	/**
	 * Get content as in file.
	 * 
	 * @param string $page
	 * @return string
	 */
	public function getRawContent($page)
	{
		if (isset($this->rawCache[$page]))
		{
			return $this->rawCache[$page];
		}

		$content = $this->retriever->retrieve($page);
		$this->rawCache[$page] = $content;

		return $content;
	}
}