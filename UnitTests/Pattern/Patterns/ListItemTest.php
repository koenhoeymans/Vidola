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
		$text = "\n\n * an item\n * other item\n\n";
		$html = "\n\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function listItemsCanAlsoBePrecededByPlusSign()
	{
		$text = "\n\n + an item\n + other item\n\n";
		$html = "\n\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededByMinusSign()
	{
		$text = "\n\n - an item\n - other item\n\n";
		$html = "\n\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededWithNumbersFollowedByDot()
	{
		$text = "\n\n 1. an item\n 2. other item\n\n";
		$html = "\n\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function listItemsCanBePrecededWithHashFollowedByDot()
	{
		$text = "\n\n #. an item\n #. other item\n\n";
		$html = "\n\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
		$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function canBeUnindented()
	{
		$text = "\n\n* an item\n* other item\n\n";
		$html = "\n\n{{li}}an item{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function listItemsCanContinueUnindentedOnFollowingLine()
	{
		$text = "\n\n * an item\nitem continues\n * other item\n\n";
		$html = "\n\n{{li}}an item\nitem continues{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function listItemsCanContinueIndentedOnFollowingLine()
	{
		$text = "\n\n\t* an item\n\titem continues\n\t* other item\n\n";
		$html = "\n\n{{li}}an item\n\titem continues{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function itemsCanContainWhiteLines()
	{
		$text = "\n\n * an item\n\n item continues\n * other item\n\n";
		$html = "\n\n{{li}}an item\n\n item continues{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function afterWhiteLineItemMustBeIndentedOnFirstLine()
	{
		$text = "\n\n * an item\n\nitem doesnt continue\n\n";
		$html = "\n\n{{li}}an item{{/li}}\n\nitem doesnt continue\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}

	/**
	 * @test
	 */
	public function aListItemCanContainAsterisks()
	{
		$text = "\n\n * an *item*\n * other item\n\n";
		$html = "\n\n{{li}}an *item*{{/li}}\n{{li}}other item{{/li}}\n\n";
		$this->assertEquals(
			$html, $this->list->replace($text)
		);
	}
}