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
	 * @see Vidola\Document.Content::getContent()
	 */
	public function getContent($page, $parse = true)
	{
		if ($parse && isset($this->parsedCache[$page]))
		{
			return $this->parsedCache[$page];
		}

		$content = $this->retriever->retrieve($page);

		if ($parse)
		{
			$content = $this->parser->parse($content);
			$this->parsedCache[$page] = $content;
		}

		return $content;
	}
}