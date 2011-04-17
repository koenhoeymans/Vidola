<?php

namespace Vidola;

class Vidola
{
	public static function run()
	{
		$config = new Util\CommandLineIniConfig(
			$_SERVER['argv'],
			__DIR__ . DIRECTORY_SEPARATOR . 'Patterns.ini'
		);

		$patternListFiller = new Util\PatternListFiller();
		$patternList = $patternListFiller->fill(new Patterns\PatternList(), $config);

		$documentBuilder = new \Vidola\Util\DocumentBuilder(
			new \Vidola\TextReplacer\HtmlBuilder($patternList),
			new Util\Writer()
		);
		$documentBuilder->build(
			$config->get('source'),
			$config->get('target.dir'),
			$config->get('target.name')
		);
	}
}

?>