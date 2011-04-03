<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_ParagraphTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->pattern = new \Vidola\Patterns\Paragraph();
	}

	/**
	 * @test
	 */
	public function emptyLineThenTextThenEmptyLineIsParagraph()
	{
		$text = "\n\nparagraph\n\n";
		$html = $this->pattern->replace($text);
		$this->assertEquals(
			"\n\n<p>paragraph</p>\n\n",
			$html
		);
	}

	/**
	 * @test
	 */
	public function multipleParagraphsCanBePlacedAfterEachOther()
	{
		$text = "\n\nparagraph\n\nanother\n\nyet another\n\n";
		$html = $this->pattern->replace($text);
		$this->assertEquals(
			"\n\n<p>paragraph</p>\n\n<p>another</p>\n\n<p>yet another</p>\n\n",
			$html
		);
	}

	/**
	 * @test
	 */
	public function useOfWhitespaceDoesntMatterForRecognition()
	{
		$text = "\n\n\tparagraph\n\n";
		$this->assertEquals(
			"\n\n\t<p>paragraph</p>\n\n",
			$this->pattern->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function multipleLinesMustBeIndentedTheSameLength()
	{
		$text = "\n\n\tparagraph\n\tparagraph continued\n\n";
		$html = $this->pattern->replace($text);
		$this->assertEquals(
			"\n\n\t<p>paragraph\n\tparagraph continued</p>\n\n",
			$html
		);
	}
}