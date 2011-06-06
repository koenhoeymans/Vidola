<?php

namespace Vidola;

class Vidola
{
	public static function run()
	{
		// the value objects
		// -----------------
		$config = new Util\CommandLineIniConfig(
			$_SERVER['argv'],
			__DIR__ . DIRECTORY_SEPARATOR . 'Patterns.ini'
		);

		// setting up the object graph constructor
		// ---------------------------------------
		$ogc = new \Vidola\Util\ObjectGraphConstructor();
		$ogc->willUse('Vidola\\TextReplacer\\HtmlBuilder');

		// filling the pattern list with the patterns
		// ------------------------------------------
		$patternListFiller = $ogc->getInstance('Vidola\\Util\\PatternListFiller');
		$patternList = $ogc->getInstance('Vidola\\Patterns\\PatternList');
		$patternListFiller->fill($patternList, $config);

		// set the documentation directory
		// -------------------------------
		$fileRetriever = $ogc->getInstance('Vidola\\Services\\FileRetriever');
		$fileRetriever->setSourceDir(self::getSourceDir($config->get('source')));

		// build the document(s)
		// ---------------------
		$documentBuilder = $ogc->getInstance('Vidola\\Util\\DocumentBuilder');
		$documentBuilder->build(
			$config->get('source'),
			$config->get('target.dir')
		);
	}

	private static function getSourceDir($source)
	{
		if (is_file($source))
		{
			$source = substr($source, 0, strrpos($source, DIRECTORY_SEPARATOR));
		}

		return realpath($source);
	}
}

?>