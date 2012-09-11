<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_BlockquoteTest extends \Vidola\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\Blockquote();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	/**
	 * @test
	 */
	public function blockquotesArePrecededByGreaterThanSignsOnEveryLine()
	{
		$text =
"paragraph

> quote
> continued

paragraph";

		$dom = new \DOMElement('blockquote', "quote\ncontinued\n\n");
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function greaterThanSignIsOnlyNecessaryOnFirstLine()
	{
		$text =
"paragraph

> quote
continued

paragraph";

		$dom = new \DOMElement('blockquote', "quote\ncontinued\n\n");
		$this->assertCreatesDomFromText($dom, $text);		
	}

	/**
	 * @test
	 */
	public function canContainABlockquote()
	{
		$text =
"paragraph

> quote
>
> > subquote
>
> quote continued

paragraph";

		$dom = new \DOMElement('blockquote', "quote\n\n&gt; subquote\n\nquote continued\n\n");
		$this->assertCreatesDomFromText($dom, $text);		
	}
}