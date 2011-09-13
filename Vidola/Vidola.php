<?php

namespace Vidola;

class Vidola
{
	public static function run()
	{
		// configuration
		// -----------------
		$config = new Config\CommandLineConfig($_SERVER['argv']);

		// setting up the object graph constructor
		// ---------------------------------------
		$ogc = new \Vidola\Util\ObjectGraphConstructor();
		$ogc->willUse('Vidola\\TextReplacer\\TextToHtmlReplacer\\TextToHtmlReplacer');
		$ogc->willUse('Vidola\\OutputBuilder\\TemplateOutputBuilder');

		// filling the pattern list with the patterns
		// ------------------------------------------
		$patternListFiller = $ogc->getInstance('Vidola\\Util\\PatternListFiller');
		$patternList = $ogc->getInstance('Vidola\\Pattern\\PatternList');
		$patternListFiller->fill($patternList, __DIR__ . DIRECTORY_SEPARATOR . 'Patterns.ini');

		// adding processors
		// -----------------
		$htmlBuilder = $ogc->getInstance('Vidola\\TextReplacer\\TextToHtmlReplacer\\TextToHtmlReplacer');
		$htmlBuilder->addPreProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\EmptyLineFixer')
		);
		$htmlBuilder->addPreProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\LineEndingsStandardizer')
		);
		$htmlBuilder->addPostProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\VidolaTagsToHtmlTags')
		);
		$htmlBuilder->addPreProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\LinkDefinitionCollector')
		);
		$htmlBuilder->addPreProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\Escaper')
		);
		$htmlBuilder->addPostProcessor(
			$ogc->getInstance('Vidola\\Processor\\Processors\\EscapeRemover')
		);

		// set the source directory or file
		// --------------------------------
		$fileRetriever = $ogc->getInstance('Vidola\\Util\\FileRetriever');
		$fileRetriever->setSourceDir(self::getSourceDir($config->get('source')));

		// set the output directory
		// ------------------------
		$writer = $ogc->getInstance('Vidola\\Util\\Writer');
		$writer->setOutputDir(self::getOutputDir($config->get('target.dir')));
		$writer->setExtension('.html');

		// choose the template
		// -------------------
		$template = $config->get('template') ?:
						__DIR__
						. DIRECTORY_SEPARATOR . 'Templates'
						. DIRECTORY_SEPARATOR . 'Default'
						. DIRECTORY_SEPARATOR . 'Index.php';
		$templateBuilder = $ogc->getInstance('Vidola\\OutputBuilder\\OutputBuilder');
		$templateBuilder->setTemplate($template);

		// build the document(s)
		// ---------------------
		$documentBuilder = $ogc->getInstance('Vidola\\DocumentBuilder\\DocumentBuilder');
		$documentBuilder->build($config->get('source'));
	}

	private static function getSourceDir($source)
	{
		if (is_file($source))
		{
			$source = substr($source, 0, strrpos($source, DIRECTORY_SEPARATOR));
		}

		return realpath($source);
	}

	private static function getOutputDir($output)
	{
		return realpath($output);
	}
}

?>