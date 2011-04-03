<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_BlockTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function blocksAreIntroducedByTwoNewLinesBoldTextFollowedByAColon()
	{
		$text = "A paragraph.\n\n\tBLOCK: some text\n\nAnother paragraph.";
		$block = new \Vidola\Patterns\Block('BLOCK:', 'block');
		$this->assertEquals(
			"A paragraph.\n\n\t<block>some text</block>\n\nAnother paragraph.",
			$block->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function blocksAreEndedByABlankLineFollowedByTextLessIndented()
	{
		$block = new \Vidola\Patterns\Block('BLOCK:', 'block');

		$text = "A paragraph.\n\n\t\tBLOCK: some text\n\n\tAnother paragraph.";
		$this->assertEquals(
			"A paragraph.\n\n\t\t<block>some text</block>\n\n\tAnother paragraph.",
			$block->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function blocksAreAlsoEndedByABlankLineFollowedByEquallyIndentedTextOnFirstLine()
	{
		$block = new \Vidola\Patterns\Block('BLOCK:', 'block');

		$text = "A paragraph.\n\n\t\tBLOCK: some text\n\n\t\tAnother paragraph.";
		$this->assertEquals(
			"A paragraph.\n\n\t\t<block>some text</block>\n\n\t\tAnother paragraph.",
			$block->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function blocksCanSpanMultipleParagraphs()
	{
		$block = new \Vidola\Patterns\Block('BLOCK:', 'block');

		$text = "A paragraph.\n\n\t\tBLOCK: some text\n\n\t\t\ta paragraph in a block.\n\nregular paragraph";
		$this->assertEquals(
			"A paragraph.\n\n\t\t<block>some text\n\n\t\t\ta paragraph in a block.</block>\n\nregular paragraph",
			$block->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function blocksCanSpanMultipleLines()
	{
		$text = "A paragraph.\n\n\tBLOCK: some text\n\tcontinued on another line\n\nAnother paragraph.";
		$block = new \Vidola\Patterns\Block('BLOCK:', 'block');
		$this->assertEquals(
			"A paragraph.\n\n\t<block>some text\n\tcontinued on another line</block>\n\nAnother paragraph.",
			$block->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function stopsWhenAnotherBlockBeginsAtSameIndentLevel()
	{
		$block = new \Vidola\Patterns\Block('BLOCK:', 'block');

		$text = "A paragraph.\n\n\tBLOCK: a block\n\n\tANOTHERBLOCK: another block\n\nanother paragraph";
		$this->assertEquals(
			"A paragraph.\n\n\t<block>a block</block>\n\n\tANOTHERBLOCK: another block\n\nanother paragraph",
			$block->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aBlockWithinABlockIsLeftAsIs()
	{
		$block = new \Vidola\Patterns\Block('BLOCK:', 'block');

		$text = "A paragraph.\n\n\tBLOCK: a block\n\n\t\tBLOCK: deeper nested block\n\nanother paragraph";
		$this->assertEquals(
			"A paragraph.\n\n\t<block>a block\n\n\t\tBLOCK: deeper nested block</block>\n\nanother paragraph",
			$block->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aClassNameCanBeSpecifified()
	{
		$block = new \Vidola\Patterns\Block('BLOCK:', 'block', 'block');
		
		$text = "A paragraph.\n\n\tBLOCK: a block\n\nanother paragraph";
		$html = "A paragraph.\n\n\t<block class=\"block\">a block</block>\n\nanother paragraph";
		$this->assertEquals(
			$html, $block->replace($text)
		);
	}
}