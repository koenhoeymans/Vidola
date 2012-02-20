<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_HeaderTest extends \Vidola\UnitTests\Support\PatternReplacementAssertions
{
	public function setup()
	{
		$this->pattern = new \Vidola\Pattern\Patterns\Header();
	}

	protected function getPattern()
	{
		return $this->pattern;
	}

	// ------------ Setext style ------------

	/**
	 * @test
	 */
	public function headerIsFollowedByLineOfAtLeastThreeCharacters()
	{
		$text = "\n\nheader\n---\n\n";
		$dom = new \DOMElement('h1', 'header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function theLineOfAtLeastThreeCharactersMayNotBePrecededByABlankLine()
	{
		$text = "\n\nno header\n\n---\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function headerIsOptionallyPrecededByLineOfCharacters()
	{
		$text = "\n\n---\na header\n---\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function characterLinesCanBeMoreThanThreeCharacters()
	{
		$text = "\n\n-----\na header\n-----\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function onlyTheFirstThreeCharactersCount()
	{
		$text = "\n\na header\n---###\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function characterLinesCannotBeLessThanThreeCharacters()
	{
		$text = "\n\n--\nthis is no header\n--\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainDashSigns()
	{
		$text = "\n\n---\na header\n---\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainEqualSigns()
	{
		$text = "\n\n===\na header\n===\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainPlusSigns()
	{
		$text = "\n\n+++\na header\n+++\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainStarSigns()
	{
		$text = "\n\n***\na header\n***\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainCaretSigns()
	{
		$text = "\n\n^^^\na header\n^^^\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainNumberSignSigns()
	{
		$text = "\n\n###\na header\n###\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function lineOfStartingAndEndingCharactersMustNotBeSame()
	{
		$text = "\n\n=-=\na header\n=-=\n\n";
		$dom = new \DOMElement('h1', 'a header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsAssignedByOrderOfAppearance()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$dom = new \DOMElement('h1', 'first');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$dom = new \DOMElement('h2', 'second');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$dom = new \DOMElement('h3', 'third');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$dom = new \DOMElement('h4', 'fourth');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$dom = new \DOMElement('h5', 'fifth');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "\n\nsixth\n###\n\n";
		$dom = new \DOMElement('h6', 'sixth');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsRemembered()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$dom = new \DOMElement('h1', 'first');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$dom = new \DOMElement('h2', 'second');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "para\n\nother second\n===\n\npara";
		$dom = new \DOMElement('h2', 'other second');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function headerCanBeStartOfDocument()
	{
		$text = "header\n---\n\n";
		$dom = new \DOMElement('h1', 'header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function headerCanFollowStartPlusNewline()
	{
		$text = "\nheader\n---\n\n";
		$dom = new \DOMElement('h1', 'header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function headerMustNotFollowABlankLine()
	{
		$text = "para\nheader\n---\n\n";
		$dom = new \DOMElement('h1', 'header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function headerMustNotBeFollowedByBlankLine()
	{
		$text = "\n\nheader\n---\nparagarph\n\n";
		$dom = new \DOMElement('h1', 'header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function canBeIndentedByUptoThreeSpaces()
	{
		$text = "\n\n   header preceded by 3 spaces\n---\n\n";
		$dom = new \DOMElement('h1', 'header preceded by 3 spaces');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "\n\n    header preceded by 4 spaces\n---\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}

	//	------------ atx style ------------

	/**
	 * @test
	 */
	public function oneToSixHashesBeforeHeaderDeterminesHeaderLevel()
	{
		$text = "paragraph\n\n# level 1\n\nparagraph";
		$dom = new \DOMElement('h1', 'level 1');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "paragraph\n\n## level 2\n\nparagraph";
		$dom = new \DOMElement('h2', 'level 2');
		$this->assertCreatesDomFromText($dom, $text);

		$text = "paragraph\n\n###### level 6\n\nparagraph";
		$dom = new \DOMElement('h6', 'level 6');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function closingHashesAreOptional()
	{
		$text = "paragraph\n\n## level 2 #####\n\nparagraph";
		$dom = new \DOMElement('h2', 'level 2');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 */
	public function headerMustNotBeFollowedByBlankLine_2()
	{
		$text = "\n\n# header\nparagarph\n\n";
		$dom = new \DOMElement('h1', 'header');
		$this->assertCreatesDomFromText($dom, $text);
	}

	/**
	 * @test
	 * 
	 * Note difference with Setext style
	 */
	public function headerMustBePrecededByBlankLine()
	{
		$text = "paragraph\n# header\n\n";
		$this->assertDoesNotCreateDomFromText($text);
	}
}