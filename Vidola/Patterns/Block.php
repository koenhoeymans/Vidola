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
	private $identifier;

	private $elementName;

	private $className;

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
		$elementName = $this->elementName;
		$className = $this->className;

		return preg_replace_callback(
			'@
			(							# relative to previous text:
			\n\n+(?=[^\s]|[ ]{1,3})		# at least a blank line when not indented or
			|							# indented with max 1-3 spaces 
			\n\n\n+(?=[\s]+)			# at least two blank lines when indented more 
			)
			(\s*)						# ${2}
			' . $this->identifier . '
			\n							# text on new line
			(\\2\t|\\2[ ]{4})			# ${3}, extra indented tab or four lines
			(.+)						# ${4}, followed by text
			(							# ${5}
			((\n|\n\n\\3).+)*			# text can continue on next line or 
			)							# blank line and indented
			(?=\n\n|$)
			@ix',
			function($match) use ($elementName, $className)
			{
				$classAttr = ($className !== null) ? ' class="' . $className . '"' : '';
				$contents = preg_replace("$\n$match[3]$", "\n", $match[4] . $match[5]);

				return
					"\n\n<" . $elementName . $classAttr . ">\n"
					. $contents
					. "\n</$elementName>";
			},
			$text
		);
	}
}