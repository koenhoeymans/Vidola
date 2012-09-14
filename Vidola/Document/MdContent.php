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

	public function __construct(Parser $parser, ContentRetriever $retriever)
	{
		$this->parser = $parser;
		$this->retriever = $retriever;
	}

	/**
	 * @see Vidola\Document.Content::getContent()
	 */
	public function getContent($page, $raw = false)
	{
		$content = $this->retriever->retrieve($page);

		if ($raw)
		{
			return $content;
		}

		return $this->parser->parse($content);
	}
}