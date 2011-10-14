<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_TextualListTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->list = new \Vidola\Pattern\Patterns\TextualList();
	}

	/**
	 * @test
	 */
	public function blankLineNecessaryBefore()
	{
		$text = "not a paragraph\n* an item\n* other item\n\nparagraph";
		$this->assertEquals($text, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function noBlankLineBeforeNecessaryWhenIndented()
	{
		$text = "paragraph\n * an item\n * other item\n\nparagraph";
		$html = "paragraph\n{{ul}}\n* an item\n* other item\n{{/ul}}\n\nparagraph";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function canBeUnindented()
	{
		$text = "\n\n* an item\n* other item\n\n";
		$html = "\n\n{{ul}}\n* an item\n* other item\n{{/ul}}\n\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function canBeIndented()
	{
		$text = "\n\n * an item\n * other item\n\n";
		$html = "\n\n{{ul}}\n* an item\n* other item\n{{/ul}}\n\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function noListWhenBlankLineAndTabIndented()
	{
		$text = "!note\n\n\t* an item\n\t* other item";
		$this->assertEquals($text, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function noListWhenBlankLineAndMoreThanThreeSpacesIndented()
	{
		$text = "!note\n\n    * an item\n    * other item";
		$this->assertEquals($text, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function canBeStartOfFile()
	{
		$text = " * an item\n * other item\n\n";
		$html = "{{ul}}\n* an item\n* other item\n{{/ul}}\n\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function canBeEndOfFile()
	{
		$text = "\n\n * an item\n * other item";
		$html = "\n\n{{ul}}\n* an item\n* other item\n{{/ul}}";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function listsCanContainBlankLines()
	{
		$text =
"

 * an item

   item continues

 * other item

";

		$html =
"

{{ul}}
* an item

  item continues

* other item
{{/ul}}

";

		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function afterBlankLineItemMustBeIndentedOnFirstLine()
	{
		$text = "\n\n * an item\n\nitem continues\n\n";
		$html = "\n\n{{ul}}\n* an item\n{{/ul}}\n\nitem continues\n\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleLists()
	{
		$text =
"paragraph

 * an item
 * other item

paragraph

 * an item
 * other item

paragraph";

		$html =
"paragraph

{{ul}}
* an item
* other item
{{/ul}}

paragraph

{{ul}}
* an item
* other item
{{/ul}}

paragraph";

		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function orderedListsAreCreatedByNumberFollowedByDotAsListMarker()
	{
		$text = "not a paragraph\n\n1. an item\n2. other item\n\nparagraph";
		$html = "not a paragraph\n\n{{ol}}\n1. an item\n2. other item\n{{/ol}}\n\nparagraph";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function orderedListsCanAlsoBeCreatedByHashSignFollowedByDot()
	{
		$text = "not a paragraph\n\n#. an item\n#. other item\n\nparagraph";
		$html = "not a paragraph\n\n{{ol}}\n#. an item\n#. other item\n{{/ol}}\n\nparagraph";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function actualNumberDoesNotNeedToBeOneTwoThreeEtc()
	{
		$text = "not a paragraph\n\n15. an item\n52. other item\n\nparagraph";
		$html = "not a paragraph\n\n{{ol}}\n15. an item\n52. other item\n{{/ol}}\n\nparagraph";
		$this->assertEquals($html, $this->list->replace($text));
	}
}