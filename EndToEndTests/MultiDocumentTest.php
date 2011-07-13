<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_MultiDocumentTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'ParentDocument.html'))
		{
			unlink($dir . 'ParentDocument.html');
		}
		if (file_exists($dir . 'SubDocument.html'))
		{
			unlink($dir . 'SubDocument.html');
		}
		if (file_exists($dir . 'Subfolder/Subdocument.html'))
		{
			unlink($dir . 'Subfolder/Subdocument.html');
		}
		if (file_exists($dir . 'ParentDocumentSubfolderSubdocument.html'))
		{
			unlink($dir . 'ParentDocumentSubfolderSubdocument.html');
		}
	}

	public function teardown()
	{
		$this->setup();
	}

	/**
	 * @test
	 */
	public function multipleDocumentsAreConnectedThroughTableOfContents()
	{
		// given
		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'ParentDocument.txt';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertEquals(
			file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'ParentDocument.html'
			),
			file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'ParentDocument.html'
			)
		);

		$this->assertEquals(
			file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SubDocument.html'
			),
			file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'SubDocument.html'
			)
		);
	}

	/**
	 * @test
	 */
	public function subDocumentCanExistInSubDirectory()
	{
		// given
		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.txt';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertEquals(
			file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.html'
			),
			file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.html'
			)
		);
	}
}