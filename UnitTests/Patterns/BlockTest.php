<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_BlockTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->block = new \Vidola\Patterns\Block('BLOCK:', 'block');
	}

	/**
	 * @test
	 */
	public function blocksAreIntroducedByBlankLineWordAndColonWithTextIndentedOnFollowingLine()
	{
		$text =
"A paragraph.

BLOCK:
	some text

Another paragraph.";

		$html =
"A paragraph.

<block>
some text
</block>

Another paragraph.";

		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function blocksCanAlsoStartWithTwoBlankLinesAndWhateverIndentation()
	{
		$text =
"A paragraph.


	BLOCK:
		some text

Another paragraph.";

	$html =
"A paragraph.

<block>
some text
</block>

Another paragraph.";
	
		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function aBlankLineIsNotSufficientToStartABlockIfPrecedingTextIsLessIndented()
	{
		$text =
"A paragraph.

	BLOCK:
		some text

Another paragraph.";

	$html =
"A paragraph.

	BLOCK:
		some text

Another paragraph.";
	
		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function blocksAreEndedByABlankLineFollowedByTextEquallyIndented()
	{
		$text =
"	A paragraph.


	BLOCK:
		some text

	Another paragraph.";

		$html =
"	A paragraph.

<block>
some text
</block>

	Another paragraph.";

		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function blocksAreEndedByABlankLineFollowedByTextLessIndented()
	{
		$text =
"	A paragraph.


	BLOCK:
		some text

Another paragraph.";

		$html =
"	A paragraph.

<block>
some text
</block>

Another paragraph.";

		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function contentsIsUnindentedForLengthOfIndentationOfBlockWord()
	{
		$text =
"A paragraph.


	BLOCK:
			some text

Another paragraph.";
	
		$html =
"A paragraph.

<block>
	some text
</block>

Another paragraph.";
	
		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function textCanSpanMultipleLines()
	{
		$text =
"A paragraph.

BLOCK:
	some text
	continued on another line

Another paragraph.";

		$html =
"A paragraph.

<block>
some text
continued on another line
</block>

Another paragraph.";

		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function textCanSpanMultipleLinesLazyStyle()
	{
		$text =
"A paragraph.

BLOCK:
	some text
continued on another line

Another paragraph.";

		$html =
"A paragraph.

<block>
some text
continued on another line
</block>

Another paragraph.";

		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function aBlockWithinABlockIsLeftAsIs()
	{
		$text =
"A paragraph.

BLOCK:
	a block

	BLOCK:
		deeper nested block

another paragraph";

		$html =
"A paragraph.

<block>
a block

BLOCK:
	deeper nested block
</block>

another paragraph";

		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function aBlockCanContinueIndentedAfterNestedBlock()
	{
		$text =
"A paragraph.

BLOCK:
	a block

	block continued

	BLOCK:
		deeper nested block

	Block continued

another paragraph";

		$html =
"A paragraph.

<block>
a block

block continued

BLOCK:
	deeper nested block

Block continued
</block>

another paragraph";

		$this->assertEquals($html, $this->block->replace($text));
	}

	/**
	 * @test
	 */
	public function aClassNameCanBeSpecifified()
	{
		$block = new \Vidola\Patterns\Block('BLOCK:', 'block', 'block');
		
		$text =
"A paragraph.

BLOCK:
	a block

another paragraph";

		$html =
"A paragraph.

<block class=\"block\">
a block
</block>

another paragraph";

		$this->assertEquals($html, $block->replace($text));
	}
}