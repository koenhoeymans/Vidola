<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_PageListFillerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->contentRetriever = $this->getMockBuilder('\\Vidola\\Util\\ContentRetriever')
			->disableOriginalConstructor()
			->getMock();
		$this->toc = $this->getMockBuilder('\\Vidola\\Pattern\\Patterns\\TableOfContents')
			->disableOriginalConstructor()
			->getMock();
		$this->filler = new \Vidola\Document\PageListFiller(
			$this->contentRetriever, $this->toc
		);
		$this->pageList = $this->getMock('\\Vidola\\Document\\PageList');
	}

	private function page($name, $content)
	{
		return new \Vidola\Document\Page($name, $content);
	}

	/**
	 * @test
	 */
	public function addsPagesToList()
	{
		$this->pageList
			->expects($this->once())
			->method('add')
			->with($this->page('a_page', 'content'));

		$this->contentRetriever
			->expects($this->once())
			->method('retrieve')
			->with('a_page')
			->will($this->returnValue('content'));

		$this->toc
			->expects($this->once())
			->method('getSubpages')
			->with('content')
			->will($this->returnValue(array()));

		$this->filler->fill($this->pageList, 'a_page');
	}

	/**
	 * @test
	 */
	public function addsSubpagesToList()
	{
		$this->pageList
			->expects($this->at(0))
			->method('add')
			->with($this->page('a_page', 'content'));

		$this->contentRetriever
			->expects($this->at(0))
			->method('retrieve')
			->with('a_page')
			->will($this->returnValue('content'));

		$this->toc
			->expects($this->at(0))
			->method('getSubpages')
			->with('content')
			->will($this->returnValue(array('subpage')));

		$this->pageList
			->expects($this->at(1))
			->method('add')
			->with($this->page('subpage', 'subcontent'), $this->page('a_page', 'content'));

		$this->contentRetriever
			->expects($this->at(1))
			->method('retrieve')
			->with('subpage')
			->will($this->returnValue('subcontent'));

		$this->toc
			->expects($this->at(1))
			->method('getSubpages')
			->with('subcontent')
			->will($this->returnValue(array()));

		$this->filler->fill($this->pageList, 'a_page');
	}
}