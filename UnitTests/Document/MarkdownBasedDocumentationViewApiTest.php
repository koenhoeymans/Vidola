<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Document_MarkdownBasedDocumentationViewApiTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->currentPage = new \Vidola\Document\Page('a_page', 'content');
		$this->pageGuide = $this->getMock('\\Vidola\\Document\\PageGuide');
		$this->structure = $this->getMock('\\Vidola\\Document\\Structure');

		$this->api = new \Vidola\Document\MarkdownBasedDocumentationViewApi(
			$this->currentPage,
			$this->pageGuide,
			$this->structure
		);
	}

	/**
	 * @test
	 */
	public function isAccessibleByNameDocument()
	{
		$this->assertEquals('document', $this->api->getName());
	}

	/**
	 * @test
	 */
	public function getCurrentPageContent()
	{
		$this->pageGuide
			->expects($this->once())
			->method('getParsedContent')
			->with($this->currentPage)
			->will($this->returnValue('parsed content'));

		$this->assertEquals('parsed content', $this->api->currentPageContent());
	}

	/**
	 * @test
	 */
	public function getsUrlToCertainPageRelativeToCurrentPage()
	{
		$this->structure
			->expects($this->once())
			->method('getUrl')
			->with($this->currentPage, new \Vidola\Document\Page('foo', 'bar'))
			->will($this->returnValue('url'));

		$this->assertEquals(
			'url', $this->api->getPageUrl(new \Vidola\Document\Page('foo', 'bar'))
		);
	}

	/**
	 * @test
	 */
	public function getsUrlToPreviousPage()
	{
		$previousPage = new \Vidola\Document\Page('foo', 'bar');

		$this->structure
			->expects($this->at(0))
			->method('getPreviousPage')
			->with($this->currentPage)
			->will($this->returnValue($previousPage));
		$this->structure
			->expects($this->at(1))
			->method('getUrl')
			->with($this->currentPage, $previousPage)
			->will($this->returnValue('url'));

		$this->assertEquals(
			'url', $this->api->previousPageUrl(new \Vidola\Document\Page('foo', 'bar'))
		);
	}

	/**
	 * @test
	 */
	public function getsUrlToNextPage()
	{
		$nextPage = new \Vidola\Document\Page('foo', 'bar');
	
		$this->structure
			->expects($this->at(0))
			->method('getNextPage')
			->with($this->currentPage)
			->will($this->returnValue($nextPage));
		$this->structure
			->expects($this->at(1))
			->method('getUrl')
			->with($this->currentPage, $nextPage)
			->will($this->returnValue('url'));
	
		$this->assertEquals(
			'url', $this->api->nextPageUrl(new \Vidola\Document\Page('foo', 'bar'))
		);
	}

	/**
	 * @test
	 */
	public function getsUrlToStartPage()
	{
		$startPage = new \Vidola\Document\Page('foo', 'bar');
	
		$this->structure
			->expects($this->at(0))
			->method('getStartPage')
			->will($this->returnValue($startPage));
		$this->structure
			->expects($this->at(1))
			->method('getUrl')
			->with($this->currentPage, $startPage)
			->will($this->returnValue('url'));
	
		$this->assertEquals(
			'url', $this->api->startPageUrl(new \Vidola\Document\Page('foo', 'bar'))
		);
	}

	/**
	 * @test
	 */
	public function getsLinkToInternalResource()
	{
		$resource = new \Vidola\Document\Resource('my.css');
	
		$this->structure
			->expects($this->any())
			->method('getUrl')
			->with($this->currentPage, $resource)
			->will($this->returnValue('url_my.css'));

		$this->assertEquals('url_my.css', $this->api->urlTo('my.css'));
	}

	/**
	 * @test
	 */
	public function getsTitleOfCurrentPage()
	{
		$this->pageGuide
			->expects($this->once())
			->method('getTitle')
			->with($this->currentPage)
			->will($this->returnValue('title'));

		$this->assertEquals('title', $this->api->currentPageTitle());
	}

	/**
	 * @test
	 */
	public function getsTitleOfPreviousPage()
	{
		$previousPage = new \Vidola\Document\Page('prev_page', '');

		$this->structure
			->expects($this->any())
			->method('getPreviousPage')
			->with($this->currentPage)
			->will($this->returnValue($previousPage));

		$this->pageGuide
			->expects($this->once())
			->method('getTitle')
			->with($previousPage)
			->will($this->returnValue('title'));

		$this->assertEquals('title', $this->api->previousPageTitle());
	}

	/**
	 * @test
	 */
	public function getsTitleOfNextPage()
	{
		$nextPage = new \Vidola\Document\Page('next_page', '');
	
		$this->structure
			->expects($this->any())
			->method('getNextPage')
			->with($this->currentPage)
			->will($this->returnValue($nextPage));
	
		$this->pageGuide
			->expects($this->once())
			->method('getTitle')
			->with($nextPage)
			->will($this->returnValue('title'));
	
		$this->assertEquals('title', $this->api->nextPageTitle());
	}

	/**
	 * @test
	 */
	public function getsTitleOfAnyPage()
	{
		$aPage = new \Vidola\Document\Page('a_page', '');

		$this->pageGuide
			->expects($this->once())
			->method('getTitle')
			->with($aPage)
			->will($this->returnValue('title'));

		$this->assertEquals('title', $this->api->getPageTitle($aPage));
	}

	/**
	 * @test
	 */
	public function tocOfCurrentPage()
	{
		$domDoc = new \DOMDocument();
		$ul = $domDoc->createElement('ul');
		$li = $domDoc->createElement('li', 'title');
		$domDoc->appendChild($ul);
		$ul->appendChild($li);

		$this->pageGuide
			->expects($this->any())
			->method('getToc')
			->with($this->currentPage)
			->will($this->returnValue($ul));

		$this->assertEquals('<ul><li>title</li></ul>', $this->api->toc());
	}

	/**
	* @test
	*/
	public function tocOfCurrentPageWithMaxDepth()
	{
		$domDoc = new \DOMDocument();
		$ul = $domDoc->createElement('ul');
		$li = $domDoc->createElement('li', 'title');
		$domDoc->appendChild($ul);
		$ul->appendChild($li);

		$this->pageGuide
			->expects($this->any())
			->method('getToc')
			->with($this->currentPage, 2)
			->will($this->returnValue($ul));

		$this->assertEquals('<ul><li>title</li></ul>', $this->api->toc(2));
	}

	/**
	 * @test
	 */
	public function aListOfFilesThatLeadToCurrentPage()
	{
		$this->structure
			->expects($this->any())
			->method('getBreadCrumbs')
			->with($this->currentPage)
			->will($this->returnValue(array('file1', 'file2')));

		$this->assertEquals(array('file1', 'file2'), $this->api->getBreadCrumbs());
	}
}