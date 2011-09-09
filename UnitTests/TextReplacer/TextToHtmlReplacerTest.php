<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_TextReplacer_TextToHtmlReplacerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->patternList = new \Vidola\Pattern\PatternList();
		$this->htmlBuilder = new \Vidola\TextReplacer\TextToHtmlReplacer(
			$this->patternList
		);		
	}

	/**
	 * @test
	 */
	public function usesPatternsToTransformText()
	{
		// given
		$text = 'a text with mockpattern text in it';
		$mockPattern = new \Vidola\UnitTests\Support\MockPattern();

		// when
		$this->patternList->addRootPattern($mockPattern);
		$text = $this->htmlBuilder->replace($text);

		// then
		$this->assertEquals('a text with <mock>mockpattern</mock> text in it', $text);
	}

	/**
	 * @test
	 */
	public function textIsPresentedFirstToPreProcessors()
	{
		$preProcessor = $this->getMock('\Vidola\Processor\Processor');
		$preProcessor
			->expects($this->once())
			->method('process')
			->with('short text');
		$this->htmlBuilder->addPreProcessor($preProcessor);

		$text = $this->htmlBuilder->replace('short text');
	}

	/**
	 * @test
	 */
	public function textIsPresentedafterwardsToPostProcessors()
	{
		$preProcessor = $this->getMock('\Vidola\Processor\Processor');
		$preProcessor
		->expects($this->once())
		->method('process')
		->with('short text');
		$this->htmlBuilder->addPostProcessor($preProcessor);
	
		$text = $this->htmlBuilder->replace('short text');
	}

	/**
	 * @test
	 */
	public function doesNotPresentVidolaTagsInTextToPatterns()
	{
		// given
		$text = 'a text with {{a}}tags{{/a}} in it';
		$mockPattern = $this->getMock('\Vidola\Pattern\Pattern');

		// expect
		$mockPattern
			->expects($this->at(0))
			->method('replace')
			->with('a text with ');
		$mockPattern
			->expects($this->at(1))
			->method('replace')
			->with(' in it');

		// when
		$this->patternList->addRootPattern($mockPattern);
		$this->htmlBuilder->replace($text);
	}

	/**
	 * @test
	 */
	public function possibleToSpecifySubpatterns()
	{
		// given
		$text = 'some text';
		$mockPatternA = $this->getMock('\Vidola\Pattern\Pattern');
		$mockPatternB = $this->getMock('\Vidola\Pattern\Pattern');

		// expect
		$mockPatternA
			->expects($this->at(0))
			->method('replace')
			->with('some text');
		$mockPatternB
			->expects($this->never())
			->method('replace');

		// when
		$this->patternList->addRootPattern($mockPatternA);
		$this->patternList->addSubpattern($mockPatternB, $mockPatternA);
		$this->htmlBuilder->replace($text);
	}

	/**
	 * @test
	 */
	public function subpatternsAreAllowedToTransformMatchingTextOfParentPattern()
	{
		// given
		$text = 'some text to test subpattern handling';
		$mockPatternA = $this->getMock('\Vidola\Pattern\Pattern');
		$mockPatternB = $this->getMock('\Vidola\Pattern\Pattern');

		// expect
		$mockPatternA
			->expects($this->at(0))
			->method('replace')
			->with('some text to test subpattern handling')
			->will($this->returnValue('some text to test {{sub}}subpattern{{/sub}} handling'));
		$mockPatternB
			->expects($this->at(0))
			->method('replace')
			->with('subpattern');	

		// when
		$this->patternList->addRootPattern($mockPatternA);
		$this->patternList->addSubpattern($mockPatternB, $mockPatternA);
		$this->htmlBuilder->replace($text);
	}
}