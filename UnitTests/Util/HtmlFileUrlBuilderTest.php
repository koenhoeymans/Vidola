<?php

require_once dirname(__FILE__)
	. DIRECTORY_SEPARATOR . '..'
	. DIRECTORY_SEPARATOR . 'TestHelper.php';

class Vidola_Util_HtmlFileBuilderTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$this->urlBuilder = new \Vidola\Util\HtmlFileUrlBuilder();
	}

	/**
	 * @test
	 */
	public function createsLinkToPageWithHtmlExtension()
	{
		$this->assertEquals('file.html', $this->urlBuilder->createLink('file'));
	}

	/**
	 * @test
	 */
	public function ifLinkedPageHasAlreadyExtensionNoHtmlExtensionIsAdded()
	{
		$this->assertEquals('file.js', $this->urlBuilder->createLink('file.js'));
	}

	/**
	 * @test
	 */
	public function keepsHierarchy()
	{
		$this->assertEquals('subfolder/index.html',
		$this->urlBuilder->createLink('subfolder/index'));
	}

	/**
	 * @test
	 */
	public function createsLinkRelativeToGivenFile()
	{
		$this->assertEquals(
			'../file.html', $this->urlBuilder->createLink('file', 'subfolder/subfile')
		);
	}

	/**
	 * @test
	 */
	public function createsLinkToDifferentDirectoryOnSameLevel()
	{
		$this->assertEquals(
			'../subdir2/foo.html',
			$this->urlBuilder->createLink('subdir2/foo', 'subdir1/subfile')
		);
	}

	/**
	 * @test
	 */
	public function createsLinkToFileInDirMultipleLevelsHigher()
	{
		$this->assertEquals(
			'../../foo.html',
			$this->urlBuilder->createLink('foo', 'subdir/subsubdir/subfile')
		);
	}

	/**
	 * @test
	 */
	public function addsHtmlExtensionWhenAskedToProvideExtension()
	{
		$this->assertEquals('file.html', $this->urlBuilder->addExtension('file'));
	}
}