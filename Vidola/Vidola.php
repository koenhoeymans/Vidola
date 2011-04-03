<?php

namespace Vidola;

class Vidola
{
	public static function run()
	{
		$config = new Util\CommandLineIniConfig(
			$_SERVER['argv'],
			__DIR__ . DIRECTORY_SEPARATOR . 'Vidola.ini'
		);

		$patternList = new Patterns\PatternList();

		$patternBuilder = new Util\PatternBuilder();
		$patternBuilder->build($patternList, $config);

		$htmlBuilder = new \Vidola\TextReplacer\HtmlBuilder($patternList);
		$html = $htmlBuilder->replace(file_get_contents($config->get('file')));

		$writer = new Util\Writer();
		$writer->write($html, $config->get('target'));
	}
}

?>