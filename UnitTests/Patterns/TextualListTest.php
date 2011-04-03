<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Patterns_TextualListTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->list = new \Vidola\Patterns\TextualList();
	}

	/**
	 * @test
	 */
	public function shouldBeIndented()
	{
		$text = "\n\n* an item\n* other item\n\n";
		$html = "\n\n* an item\n* other item\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function mustBePrecededAndFollowedByBlankLineAtStartAndEndOfList()
	{
		$text = "\n * an item\n * other item\n";
		$html = "\n * an item\n * other item\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function listsCanContainWhiteLines()
	{
		$text = "\n\n * an item\n\n item continues\n * other item\n\n";
		$html = "\n\n<ul>\n * an item\n\n item continues\n * other item\n</ul>\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function afterWhiteLineItemMustBeIndentedOnFirstLine()
	{
		$text = "\n\n * an item\n\nitem continues\n * other item\n\n";
		$html = "\n\n<ul>\n * an item\n</ul>\n\nitem continues\n * other item\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function textCanContainMultipleLists()
	{
		$text = "\n\n\t\t* an item\n\t\t* other item\n\n\tpara indented one tab\n\n\t\t* an item\n\t\t* other item\n\n";
		$html = "\n\n<ul>\n\t\t* an item\n\t\t* other item\n</ul>\n\n\tpara indented one tab\n\n<ul>\n\t\t* an item\n\t\t* other item\n</ul>\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}
}