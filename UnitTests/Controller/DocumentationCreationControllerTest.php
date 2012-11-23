<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..' 
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Controller_DocumentationCreationControllerTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->filenameCreator = $this->getMock('\\Vidola\\Document\\FilenameCreator');
		$this->documentationApiBuilder = $this->getMock('\\Vidola\\Document\\DocumentationApiBuilder');
		$this->structure = $this->getMock('\\Vidola\\Document\\Structure');
		$this->view = $this->getMock('\\Vidola\\View\\TemplatableFileView');
		$this->controller = new \Vidola\Controller\DocumentationCreationController(
			$this->filenameCreator,
			$this->documentationApiBuilder,
			$this->structure,
			$this->view
		);
	}

	/**
	 * @test
	 */
	public function tellsDocumentModelToBuildApi()
	{
		$page = new \Vidola\Document\Page('url', 'content');

		$this->documentationApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->structure
				->expects($this->atLeastOnce())
				->method('getPages')
				->will($this->returnValue(array($page)));

		$this->controller->createDocumentation();
	}

	/**
	 * @test
	 */
	public function addsApiToView()
	{
		$page = new \Vidola\Document\Page('url', 'content');

		$this->documentationApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->structure
				->expects($this->atLeastOnce())
				->method('getPages')
				->will($this->returnValue(array($page)));
		$this->view
				->expects($this->once())
				->method('addApi');

		$this->controller->createDocumentation();
	}

	/**
   	 * @test
	 */
	public function tellsViewWhichFilenameToUse()
	{
		$page = new \Vidola\Document\Page('url', 'content');

		$this->documentationApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->structure
				->expects($this->atLeastOnce())
				->method('getPages')
				->will($this->returnValue(array($page)));
		$this->filenameCreator
				->expects($this->atLeastOnce())
				->method('createFilename')
				->will($this->returnValue('foo'));
		$this->view
				->expects($this->once())
				->method('setFilename')
				->with('foo');

		$this->controller->createDocumentation();
	}

	/**
	 * @test
	 */
	public function tellsViewToRender()
	{
		$page = new \Vidola\Document\Page('url', 'content');

		$this->documentationApiBuilder
				->expects($this->once())
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->structure
				->expects($this->atLeastOnce())
				->method('getPages')
				->will($this->returnValue(array($page)));
		$this->view
				->expects($this->once())
				->method('render');

		$this->controller->createDocumentation();
	}

	/**
	 * @test
	 */
	public function takesCareOfSubpages()
	{
		$page = new \Vidola\Document\Page('url', 'content');
		$subpage = new \Vidola\Document\Page('suburl', 'content');

		$this->documentationApiBuilder
				->expects($this->exactly(2))
				->method('buildApi')
				->will($this->returnValue($this->getMock('\\Vidola\\View\\ViewApi')));
		$this->structure
				->expects($this->at(0))
				->method('getPages')
				->will($this->returnValue(array($page, $subpage)));

		$this->controller->createDocumentation();
	}
}