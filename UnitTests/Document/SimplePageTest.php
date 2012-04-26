<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_SimplePageTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @test
	 */
	public function containsContent()
	{
		$page = new \Vidola\Document\SimplePage();
		$page->setContent('foo');
		$this->assertEquals('foo', $page->getContent());
	}

	/**
	 * @test
	 */
	public function hasFilename()
	{
		$page = new \Vidola\Document\SimplePage();
		$page->setFilename('foo');
		$this->assertEquals('foo', $page->getFilename());
	}

	/**
	 * @test
	 */
	public function hasTitle()
	{
		$page = new \Vidola\Document\SimplePage();
		$page->setTitle('foo');
		$this->assertEquals('foo', $page->getTitle());
	}

	/**
	 * @test
	 */
	public function providesPreviousPageName()
	{
		$page = new \Vidola\Document\SimplePage();
		$page->setPreviousPageName('foo');
		$this->assertEquals('foo', $page->getPreviousPageName());
	}

	/**
	 * @test
	 */
	public function hasNextPageName()
	{
		$page = new \Vidola\Document\SimplePage();
		$page->setNextPageName('foo');
		$this->assertEquals('foo', $page->getNextPageName());
	}
}