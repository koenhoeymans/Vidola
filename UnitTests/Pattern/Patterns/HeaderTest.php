<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_HeaderTest extends PHPUnit_Framework_TestCase
{
	public function header()
	{
		return new \Vidola\Pattern\Patterns\Header();
	}

	// ------------ Setext style ------------

	/**
	 * @test
	 */
	public function headerIsFollowedByLineOfAtLeastThreeCharacters()
	{
		$text = "\n\nheader\n---\n\n";
		$html = "\n\n{{h1 id=\"header\"}}header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function theLineOfAtLeastThreeCharactersMustNotBePrecededByABlankLine()
	{
		$text = "\n\nno header\n\n---\n\n";
		$html = "\n\nno header\n\n---\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function headerIsOptionallyPrecededByLineOfCharacters()
	{
		$text = "\n\n---\na header\n---\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function characterLinesCanBeMoreThanThreeCharacters()
	{
		$text = "\n\n-----\na header\n-----\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function onlyTheFirstThreeCharactersCount()
	{
		$text = "\n\na header\n-----\n\nanother header\n---\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n{{h1 id=\"another_header\"}}another header{{/h1}}\n\n";
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
		$text = "\n\n---\na header\n---\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainEqualSigns()
	{
		$text = "\n\n===\na header\n===\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainPlusSigns()
	{
		$text = "\n\n+++\na header\n+++\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainStarSigns()
	{
		$text = "\n\n***\na header\n***\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainCaretSigns()
	{
		$text = "\n\n^^^\na header\n^^^\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineCharactersMayContainNumberSignSigns()
	{
		$text = "\n\n###\na header\n###\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function lineOfStartingAndEndingCharactersMustNotBeSame()
	{
		$text = "\n\n=-=\na header\n=-=\n\n";
		$html = "\n\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsAssignedByOrderOfAppearance()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nfourth\n***\n\nfifth\n^^^\n\nsixth\n###\n\n";
		$html = "\n\n{{h1 id=\"first\"}}first{{/h1}}\n\n{{h2 id=\"second\"}}second{{/h2}}\n\n{{h3 id=\"third\"}}third{{/h3}}\n\n{{h4 id=\"fourth\"}}fourth{{/h4}}\n\n{{h5 id=\"fifth\"}}fifth{{/h5}}\n\n{{h6 id=\"sixth\"}}sixth{{/h6}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function levelOfHeadersIsRemembered()
	{
		$text = "\n\nfirst\n---\n\nsecond\n===\n\nthird\n+++\n\nsecond\n===\n\nthird\n+++\n\n";
		$html = "\n\n{{h1 id=\"first\"}}first{{/h1}}\n\n{{h2 id=\"second\"}}second{{/h2}}\n\n{{h3 id=\"third\"}}third{{/h3}}\n\n{{h2 id=\"second\"}}second{{/h2}}\n\n{{h3 id=\"third\"}}third{{/h3}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function headerCanBeStartOfDocument()
	{
		$text = "a header\n---\n\n";
		$html = "{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function headerMustNotFollowABlankLine()
	{
		$text = "some text\na header\n---\n\n";
		$html = "some text\n{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function headerCanBeIndentedWithTabs()
	{
		$text = "\n\n\ta header\n\t---\n\n";
		$html = "\n\n\t{{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function canBeIndentedBySpaces()
	{
		$text = "\n\n  a header\n  ---\n\n";
		$html = "\n\n  {{h1 id=\"a_header\"}}a header{{/h1}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function multipleHeadersCanBeIndentedDifferently()
	{
		$text = "\n\n\ta header\n\t---\n\n\t\tanother header\n+++\n\n";
		$html = "\n\n\t{{h1 id=\"a_header\"}}a header{{/h1}}\n\n\t\t{{h2 id=\"another_header\"}}another header{{/h2}}\n\n";
		$this->assertEquals($html, $this->header()->replace($text));
	}

	// ------------ atx style ------------

	/**
	 * @test
	 */
	public function oneToSixHashesBeforeHeaderDeterminesHeaderLevel()
	{
		$text =
"paragraph

# level 1

## level 2

### level 3

#### level 4

##### level 5

###### level 6

paragraph";

		$html =
"paragraph

<h1>level 1</h1>

<h2>level 2</h2>

<h3>level 3</h3>

<h4>level 4</h4>

<h5>level 5</h5>

<h6>level 6</h6>

paragraph";

		$this->assertEquals($html, $this->header()->replace($text));
	}

	/**
	 * @test
	 */
	public function closingHashesAreOptional()
	{
		$text =
"paragraph

## level 2 #####

paragraph";

		$html =
"paragraph

<h2>level 2</h2>

paragraph";

		$this->assertEquals($html, $this->header()->replace($text));
	}
}