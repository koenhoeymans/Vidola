<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Pattern_Patterns_ListItemTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->list = new \Vidola\Pattern\Patterns\ListItem();
	}

	/**
	 * @test
	 */
	public function listItemsArePrecededByAnAsterisk()
	{
		$text = "\n * an item\n * other item\n";
		$html = "\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanAlsoBePrecededByPlusSign()
	{
		$text = "\n + an item\n + other item\n";
		$html = "\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededByMinusSign()
	{
		$text = "\n - an item\n - other item\n";
		$html = "\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededWithNumbersFollowedByDot()
	{
		$text = "\n 1. an item\n 2. other item\n";
		$html = "\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededWithHashFollowedByDot()
	{
		$text = "\n #. an item\n #. other item\n";
		$html = "\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function canBeUnindented()
	{
		$text = "\n* an item\n* other item\n";
		$html = "\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanContinueUnindentedOnFollowingLine()
	{
		$text = "\n * an item\nitem continues\n * other item\n";
		$html = "\n{{li}}an item\nitem continues{{/li}}\n{{li}}other item{{/li}}\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function listItemsCanContinueIndentedOnFollowingLine()
	{
		$text =
"
 * an item
   item continues
 * other item
";

		$html =
"
{{li}}an item
item continues{{/li}}
{{li}}other item{{/li}}
";

		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function itemsCanContainWhiteLines()
	{
		$text =
"
 * an item

 item continues
 * other item
";

		$html =
"
{{li}}an item

item continues{{/li}}
{{li}}other item{{/li}}
";

		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function afterWhiteLineItemMustBeIndentedOnFirstLine()
	{
		$text = "\n * an item\n\nitem doesnt continue\n";
		$html = "\n{{li}}an item{{/li}}\n\nitem doesnt continue\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function aListItemCanContainAsterisks()
	{
		$text = "\n * an *item*\n * other item\n";
		$html = "\n{{li}}an *item*{{/li}}\n{{li}}other item{{/li}}\n";
		$this->assertEquals($html, $this->list->replace($text));
	}

	/**
	 * @test
	 */
	public function listItemsCanBeSeperatedByABlankLine()
	{
		$text = "\n * an item\n\n * other item\n";
		$html = "\n{{li}}an item\n\n{{/li}}\n\n{{li}}other item\n\n{{/li}}\n";
		$this->assertEquals($html, $this->list->replace($text));
	}
}