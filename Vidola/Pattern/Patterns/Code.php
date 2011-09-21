<?php

/**
 * @package Vidola
 */
namespace Vidola\Pattern\Patterns;

use Vidola\Pattern\Pattern;

/**
 * @package Vidola
 */
abstract class Code implements Pattern
{
	protected function createCodeInHtml($code, $pre = true)
	{
		$code = '{{code}}' . $code . '{{/code}}';

		if ($pre)
		{
			return '{{pre}}' . $code . '{{/pre}}';
		}
		return $code;
	}
}