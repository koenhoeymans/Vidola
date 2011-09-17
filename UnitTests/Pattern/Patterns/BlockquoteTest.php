<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_BlockquoteTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\Blockquote();
	}

	/**
	 * @test
	 */
	public function blockquotesArePrecededByGreaterThanSignsOnEveryLine()
	{
		$code =
"paragraph

> quote
> continued

paragraph";

		$html =
"paragraph

{{blockquote}}
quote
continued
{{/blockquote}}

paragraph";

		$this->assertEquals($html, $this->pattern->replace($code));
	}

	/**
	 * @test
	 */
	public function greaterThanSignIsOnlyNecessaryOnFirstLine()
	{
		$code =
"paragraph

> quote
continued

paragraph";

		$html =
"paragraph

{{blockquote}}
quote
continued
{{/blockquote}}

paragraph";
		
		$this->assertEquals($html, $this->pattern->replace($code));		
	}

	/**
	 * @test
	 */
	public function canContainABlockquote()
	{
		$code =
"paragraph

> quote
>
> > subquote
>
> quote continued

paragraph";

		$html =
"paragraph

{{blockquote}}
quote

> subquote

quote continued
{{/blockquote}}

paragraph";
		
		$this->assertEquals($html, $this->pattern->replace($code));		
	}
}