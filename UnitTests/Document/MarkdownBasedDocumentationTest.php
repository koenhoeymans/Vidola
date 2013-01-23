<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_MarkdownBasedDocumentationTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		$this->internalUrlBuilder = $this->getMock('\\AnyMark\\Util\\InternalUrlBuilder');

		$this->mdDoc = new \Vidola\Document\MarkdownBasedDocumentation(
			$this->internalUrlBuilder
		);
	}

	/**
	 * @test
	 */
	public function keepsListOfAllPages()
	{
		$page1 = new \Vidola\Document\Page('page1', 'content');
		$page2 = new \Vidola\Document\Page('page2', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->assertEquals(array($page1, $page2), $this->mdDoc->getPages());
	}

	/**
	 * @test
	 */
	public function getPreviousPage()
	{
		$page1 = new \Vidola\Document\Page('page1', 'content');
		$page2 = new \Vidola\Document\Page('page2', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->assertEquals($page1, $this->mdDoc->getPreviousPage($page2));
	}

	/**
	 * @test
	 */
	public function getNextPage()
	{
		$page1 = new \Vidola\Document\Page('page1', 'content');
		$page2 = new \Vidola\Document\Page('page2', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->assertEquals($page2, $this->mdDoc->getNextPage($page1));
	}

	/**
	 * @test
	 */
	public function startPageIsFirstPageAdded()
	{
		$page1 = new \Vidola\Document\Page('page1', 'content');
		$page2 = new \Vidola\Document\Page('page2', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->assertEquals($page1, $this->mdDoc->getStartPage());
	}

	/**
	 * @test
	 */
	public function createsUrlFromPageToAnother()
	{
		$page1 = new \Vidola\Document\Page('index', 'content');
		$page2 = new \Vidola\Document\Page('../subpage', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2);

		$this->internalUrlBuilder
			->expects($this->atLeastOnce())
			->method('createRelativeLink')
			->with()
			->will($this->returnValue('../index'));

		$this->assertEquals('../index', $this->mdDoc->getUrl($page2, $page1));
	}

	/**
	 * @test
	 */
	public function providesBreadCrumbsAsListOfPages()
	{
		$page1 = new \Vidola\Document\Page('index', 'content');
		$page2 = new \Vidola\Document\Page('../subpage', 'content');
		$page3 = new \Vidola\Document\Page('../sub/subpage', 'content');
		$this->mdDoc->add($page1);
		$this->mdDoc->add($page2, $page1);
		$this->mdDoc->add($page3, $page2);

		$this->assertEquals(
			array($page1, $page2, $page3),
			$this->mdDoc->getBreadCrumbs($page3)
		);
	}
}