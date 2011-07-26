<?php

require_once('TestHelper.php');

class Vidola_EndToEndTests_DirectoryTest extends PHPUnit_Framework_TestCase
{
	public function setup()
	{
		$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR;
		if (file_exists($dir . 'Index.html'))
		{
			unlink($dir . 'Index.html');
		}
		if (file_exists($dir . 'OtherDocument.html'))
		{
			unlink($dir . 'OtherDocument.html');
		}
	}

	public function teardown()
	{
		$this->setup();
	}

	/**
	 * @test
	 */
	public function directoryAsInputTransformsIndex()
	{
		// given
		$_SERVER['argv']['source'] = __DIR__
			. DIRECTORY_SEPARATOR . 'Support'
			. DIRECTORY_SEPARATOR . 'FolderWithTwoDocuments';
		$_SERVER['argv']['target.dir'] = sys_get_temp_dir();

		// when
		\Vidola\Vidola::run();

		// then
		$this->assertTrue(
			file_exists(
				sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'Index.html'
			)
		);
		$this->assertFalse(
			file_exists(
				sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'OtherDocument.html'
			)
		);
	}
}