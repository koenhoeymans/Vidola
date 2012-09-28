<?php

/**
 * @package Vidola
 */
namespace Vidola\Document;

use Vidola\Parser\Parser;
use Vidola\Util\ContentRetriever;
use Vidola\Processor\TextProcessor;

/**
 * @package Vidola
 */
class MdContent implements Content
{
	private $parser;

	private $retriever;

	private $postTextProcessors = array();

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

	public function addPostTextProcessor(TextProcessor $processor)
	{
		$this->postTextProcessors[] = $processor;
	}

	/**
	 * @see Vidola\Document.Content::getContent()
	 * @return string
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
			$domDoc = $this->parser->parse($content);
			$content = $domDoc->saveXml($domDoc->documentElement);

			# DomDocument::saveXml encodes entities like `&` when added within
			# a text node.
			$content = str_replace(
				array('&amp;amp;', '&amp;copy;', '&amp;quot;', '&amp;#'),
				array('&amp;', '&copy;', '&quot;', '&#'),
				$content
			);

			$this->parsedCache[$page] = $content;

			$this->postProcess($content);
		}

		return $content;
	}

	private function postProcess($text)
	{
		foreach ($this->postTextProcessors as $processor)
		{
			$text = $processor->process($text);
		}
	
		return $text;
	}
}