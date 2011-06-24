<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_TextReplacer_HtmlBuilderTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->patternList = new \Vidola\Patterns\PatternList();
		$this->htmlBuilder = new \Vidola\TextReplacer\HtmlBuilder(
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
	public function doesNotPresentTagsInTextToPatterns()
	{
		// given
		$text = 'a text with <a>tags</a> in it';
		$mockPattern = $this->getMock('\Vidola\Patterns\Pattern');

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
		$mockPatternA = $this->getMock('\Vidola\Patterns\Pattern');
		$mockPatternB = $this->getMock('\Vidola\Patterns\Pattern');

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
		$mockPatternA = $this->getMock('\Vidola\Patterns\Pattern');
		$mockPatternB = $this->getMock('\Vidola\Patterns\Pattern');

		// expect
		$mockPatternA
			->expects($this->at(0))
			->method('replace')
			->with('some text to test subpattern handling')
			->will($this->returnValue('some text to test <sub>subpattern</sub> handling'));
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