<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_MultiDocumentTest extends \Vidola\EndToEndTests\Support\Tidy
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
		$_SERVER['argv']['template'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'MiniTemplate.php';

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'ParentDocument.html'
			)),
			$this->tidy(file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'ParentDocument.html'
			))
		);

		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'SubDocument.html'
			)),
			$this->tidy(file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'SubDocument.html'
			))
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
		$_SERVER['argv']['template'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'MiniTemplate.php';

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.html'
			)),
			$this->tidy(file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.html'
			))
		);

		$this->assertEquals(
			$this->tidy(file_get_contents(
				__DIR__
				. DIRECTORY_SEPARATOR . 'Support'
				. DIRECTORY_SEPARATOR . 'Subfolder'
				. DIRECTORY_SEPARATOR . 'Subdocument.html'
			)),
			$this->tidy(file_get_contents(
				$_SERVER['argv']['target.dir']
				. DIRECTORY_SEPARATOR . 'Subfolder'
				. DIRECTORY_SEPARATOR . 'Subdocument.html'
			))
		);
	}

	/**
	 * @test
	 */
	public function linksToPreviousAndNextDocumentsAreAvailable()
	{
		// given
		// note: using default template
		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'ParentDocument.txt';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertTrue(
			is_string(strstr(
				file_get_contents(
					$_SERVER['argv']['target.dir']
					. DIRECTORY_SEPARATOR . 'ParentDocument.html'
				),
				'next'
			))
		);

		$this->assertTrue(
			is_string(strstr(
				file_get_contents(
					$_SERVER['argv']['target.dir']
					. DIRECTORY_SEPARATOR . 'SubDocument.html'
				),
				'previous'
			))
		);
	}

	/**
	 * @test
	 */
	public function subdocumentLinksToParentDocument()
	{
		// given
		// note: using default template
		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'ParentDocumentSubfolderSubdocument.html';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertTrue(
			is_string(strstr(
				file_get_contents(
					$_SERVER['argv']['target.dir']
					. DIRECTORY_SEPARATOR . 'Subfolder'
					. DIRECTORY_SEPARATOR . 'Subdocument.html'
				),
				'../ParentDocumentSubfolderSubdocument.html'
			))
		);
	}
}