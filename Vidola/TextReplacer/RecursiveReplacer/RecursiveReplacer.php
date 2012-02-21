<?php

/**
 * @package Vidola
 */
namespace Vidola\TextReplacer\RecursiveReplacer;

use Vidola\Pattern\Pattern;
use Vidola\Pattern\PatternList;
use Vidola\Processor\TextProcessor;
use Vidola\Processor\DomProcessor;
use Vidola\TextReplacer\TextReplacer;


/**
 * @package vidola
 */
class RecursiveReplacer implements TextReplacer
{
	private $patternList;

	private $preTextProcessors = array();

	private $postTextProcessors = array();

	private $preDomProcessors = array();

	private $postDomProcessors = array();

	public function __construct(PatternList $patternList)
	{
		$this->patternList = $patternList;
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::getExtension()
	 */
	public function getExtension()
	{
		return 'html';
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::addPreProcessor()
	 */
	public function addPreTextProcessor(TextProcessor $processor)
	{
		$this->preTextProcessors[] = $processor;
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::addPostProcessor()
	 */
	public function addPostTextProcessor(TextProcessor $processor)
	{
		$this->postTextProcessors[] = $processor;
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::addPreDomProcessor()
	 */
	public function addPreDomProcessor(DomProcessor $domProcessor)
	{
		$this->preDomProcessors[] = $domProcessor;
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::addPostDomProcessor()
	 */
	public function addPostDomProcessor(DomProcessor $domProcessor)
	{
		$this->postDomProcessors[] = $domProcessor;
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::replace()
	 */
	public function replace($text)
	{
		# adding the \n for texts containing only a paragraph
		$text = $this->preProcess($text . "\n");

		$domDoc = new \DOMDocument();
		$document = $domDoc->createElement('doc');
		$textNode = $domDoc->createTextNode($text);
		$domDoc->appendChild($document);
		$document->appendChild($textNode);

		$this->preProcessDom($domDoc);

		$this->applyPatterns($textNode);

		$this->postProcessDom($domDoc);

		$text = trim($domDoc->saveXML($document));

		$text = $this->postProcess($text);

		return $text;
	}

	private function preProcess($text)
	{
		foreach ($this->preTextProcessors as $processor)
		{
			$text = $processor->process($text);
		}

		return $text;
	}

	private function postProcess($text)
	{
		foreach ($this->postTextProcessors as $processor)
		{
			$text = $processor->process($text);
		}

		return $text;
	}

	private function preProcessDom(\DOMDocument $document)
	{
		foreach ($this->preDomProcessors as $processor)
		{
			$processor->process($document);
		}
	}

	private function postProcessDom(\DOMDocument $document)
	{
		foreach ($this->postDomProcessors as $processor)
		{
			$processor->process($document);
		}
	}

	private function applyPatterns(\DOMText $node, Pattern $parentPattern = null)
	{
		$document = $node->ownerDocument;
		$parentNode = $node->parentNode;
		$textToReplace = $node->nodeValue;
		$totalBytes = strlen($textToReplace);
		$currentByteOffset = 0;
		$endOfTextReached = false;
		$patterns = ($parentPattern == null) ?
			$this->patternList->getPatterns() :
			$this->patternList->getSubpatterns($parentPattern);

		while (!$endOfTextReached)
		{
			foreach($patterns as $pattern)
			{
				$regex = $pattern->getRegex();
				preg_match($regex . 'A', $textToReplace, $match, 0, $currentByteOffset);

				if (!empty($match))
				{
					# create dom node from match
					$patternCreatedDom = $pattern->handleMatch($match, $node, $parentPattern);

					# if pattern decides there's no match after examining regex match
					# we can continue
					if (!$patternCreatedDom)
					{
						continue;
					}

					# add text node from text before match
					$textBeforeMatch = substr($textToReplace, 0, $currentByteOffset);
					$parentNode->replaceChild(
						$document->createTextNode($textBeforeMatch), $node
					);

					# applying subpatterns to dom node from match
					$parentNode->appendChild($patternCreatedDom);
					$this->applySubpatterns($patternCreatedDom, $pattern);

					# create text node from text following match
					$textFollowingMatch = substr(
						$textToReplace, strlen($match[0]) + $currentByteOffset
					);
					$textFollowingMatchNode = $parentNode->appendChild(
						$document->createTextNode($textFollowingMatch)
					);
					$this->applyPatterns($textFollowingMatchNode, $parentPattern);

					return;
				}
			}

			if ($currentByteOffset == $totalBytes)
			{
				$endOfTextReached = true;
			}
			else
			{
				$currentByteOffset++;
			}
		}
	}

	private function applySubpatterns(\DOMNode $node, Pattern $parentPattern)
	{
		if (!$node->hasChildNodes())
		{
			return;
		}

		foreach ($node->childNodes as $childNode)
		{
			if ($childNode instanceof \DOMText)
			{
				$this->applyPatterns($childNode, $parentPattern);
			}
			else
			{
				$this->applySubpatterns($childNode, $parentPattern);
			}
		}
	}
}