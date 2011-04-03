<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class Block implements Pattern
{
	const pattern = '[a-zA-Z]+:';

	private $identifier;

	private $elementName;

	private $className;

	private $componentPatterns = array();

	/**
	 * @param string $identifier
	 * @param string $elementName
	 */
	public function __construct($identifier, $elementName, $className = null)
	{
		$this->identifier = $identifier;
		$this->elementName = $elementName;
		$this->className = $className;
	}

	/**
	 * @see Vidola\Patterns.Pattern::replace()
	 */
	public function replace($text)
	{	
		$componentPatterns = $this->componentPatterns;
		$elementName = $this->elementName;
		$className = $this->className;

		return preg_replace_callback(
			"#(?<=\n\n)(\s+)" . $this->identifier . "\s*(.+)(((\n\\1|\n\n\\1\s).+)*)(?=\n\n)#i",
			function($match) use ($componentPatterns, $elementName, $className)
			{
				$classAttr = ($className !== null) ? ' class="' . $className . '"' : '';
				$contents = isset($match[3]) ? $match[2] . $match[3] : $match[2];
				return 
					$match[1]
					. "<" . $elementName . $classAttr . ">"
					. $contents
					. "</$elementName>";
			},
			$text
		);
	}
}