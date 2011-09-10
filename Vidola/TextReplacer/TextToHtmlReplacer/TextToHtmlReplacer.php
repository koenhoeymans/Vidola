<?php

/**
 * @package Vidola
 */
namespace Vidola\TextReplacer\TextToHtmlReplacer;

use Vidola\Pattern\Pattern;
use Vidola\Pattern\PatternList;
use Vidola\Processor\Processor;
use Vidola\TextReplacer\TextReplacer;

/**
 * @package vidola
 */
class TextToHtmlReplacer implements TextReplacer
{
	private $patternList;

	private $preProcessors = array();

	private $postProcessors = array();

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
	public function addPreProcessor(Processor $processor)
	{
		$this->preProcessors[] = $processor;
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::addPostProcessor()
	 */
	public function addPostProcessor(Processor $processor)
	{
		$this->postProcessors[] = $processor;
	}

	/**
	 * @see Vidola\TextReplacer.TextReplacer::replace()
	 */
	public function replace($text)
	{
		foreach ($this->preProcessors as $processor)
		{
			$text = $processor->process($text);
		}

		foreach( $this->patternList->getRootPatterns() as $pattern)
		{
			$text = RecursivePatternReplacer::replaceRecursively(
				$text, $pattern, $this->patternList
			);
		}

		foreach ($this->postProcessors as $processor)
		{
			$text = $processor->process($text);
		}

		return $text;
	}
}