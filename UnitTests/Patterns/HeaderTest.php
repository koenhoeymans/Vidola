<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_HeaderTest extends PHPUnit_Framework_TestCase
{
	public function header()
	{
		return new \Vidola\Patterns\Header();
	}

	/**
	 * @test
	 */
	public function headerIsFollowedByLineOfAtLeastThreeCharacters()
	{
		$text = "\n\nthis is a header\n---\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function theLineOfAtLeastThreeCharactersMustNotBePrecededByABlankLine()
	{
		$text = "\n\nthis is not a header\n\n---\n\n";
		$html = "\n\nthis is not a header\n\n---\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function headerIsOptionallyPrecededByLineOfCharacters()
	{
		$text = "\n\n---\nthis is a header\n---\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function characterLinesCanBeMoreThanThreeCharacters()
	{
		$text = "\n\n-----\nthis is a header\n-----\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function characterLinesCannotBeLessThanThreeCharacters()
	{
		$text = "\n\n--\nthis is a header\n--\n\n";
		$html = "\n\n--\nthis is a header\n--\n\n";;
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainDashSigns()
	{
		$text = "\n\n---\nthis is a header\n---\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainEqualSigns()
	{
		$text = "\n\n===\nthis is a header\n===\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainPlusSigns()
	{
		$text = "\n\n+++\nthis is a header\n+++\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainStarSigns()
	{
		$text = "\n\n***\nthis is a header\n***\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainCaretSigns()
	{
		$text = "\n\n^^^\nthis is a header\n^^^\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainNumberSignSigns()
	{
		$text = "\n\n###\nthis is a header\n###\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineOfStartingAndEndingCharactersMustNotBeSame()
	{
		$text = "\n\n=-=\nthis is a header\n=-=\n\n";
		$html = "\n\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsAssignedByOrderOfAppearance()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$html = "\n\n<h1>first</h1>\n\n<h2>second</h2>\n\n<h3>third</h3>\n\n<h4>fourth</h4>\n\n<h5>fifth</h5>\n\n<h6>sixth</h6>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsRemembered()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nsecond\n===\n\nthird\n+++\n\n";
		$html = "\n\n<h1>first</h1>\n\n<h2>second</h2>\n\n<h3>third</h3>\n\n<h2>second</h2>\n\n<h3>third</h3>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function headerCanBeStartOfDocument()
	{
		$text = "this is a header\n---\n\n";
		$html = "<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function headerMustNotFollowABlankLine()
	{
		$text = "some text\nthis is a header\n---\n\n";
		$html = "some text\n<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function headerCanBeIndentedWithTabs()
	{
		$text = "\n\n\tthis is a header\n\t---\n\n";
		$html = "\n\n\t<h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function indentationCanBeIndentedBySpaces()
	{
		$text = "\n\n this is a header\n ---\n\n";
		$html = "\n\n <h1>this is a header</h1>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function multipleHeadersCanBeIndentedDifferently()
	{
		$text = "\n\n\ta header\n\t---\n\n\t\tanother header\n+++\n\n";
		$html = "\n\n\t<h1>a header</h1>\n\n\t\t<h2>another header</h2>\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}
}