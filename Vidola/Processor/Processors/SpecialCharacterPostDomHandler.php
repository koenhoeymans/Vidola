<?php

/**
 * @package Vidola
 */
namespace Vidola\Processor\Processors;

use Vidola\Processor\DomProcessor;

/**
 * @package Vidola
 */
class SpecialCharacterPostDomHandler implements DOMProcessor
{
	public function process(\DOMDocument $domDocument)
	{
		$xpath = new \DOMXPath($domDocument);

		$nodes = $xpath->query('//*');
		foreach ($nodes as $node)
		{
			foreach ($node->attributes as $attribute)
			{
				$newValue = $attribute->value;
				$newValue = $this->readdBackslash($newValue);
				$newValue = $this->decodeEscapedCharsEntities($newValue);
				$newValue = htmlspecialchars($newValue, ENT_COMPAT, 'UTF-8', false);
				# needed because $attribute->value = $newValue removes the &amp;
				$newValue = preg_replace("@&@", "&amp;", $newValue);

				$attribute->value = $newValue;

				$this->encodeIfEmailElement($attribute);
			}
		}

		$textNodes = $xpath->query('//text()');
		foreach ($textNodes as $textNode)
		{
			$newValue = $textNode->nodeValue;

			if($textNode->parentNode->nodeName === 'code')
			{
				$newValue = $this->readdBackslash($newValue);
			}
			$newValue = $this->decodeEscapedCharsEntities($newValue);
			if($textNode->parentNode->nodeName === 'code')
			{
				$newValue = htmlspecialchars($newValue, ENT_NOQUOTES, 'UTF-8', true);
			}
			else
			{
				$newValue = htmlspecialchars($newValue, ENT_NOQUOTES, 'UTF-8', false);
			}

			$textNode->nodeValue = $newValue;
		}
	}

	private function readdBackslash($text)
	{
		return preg_replace("#,,,,(&(.)+?;),,,,#", "\\\\,,,,\${1},,,,", $text);
	}

	/**
	 * Decode entities that were encoded by preHandler surrounded by ,,,,
	 * to set them apart as escaped character.
	 */
	private function decodeEscapedCharsEntities($text)
	{
		return preg_replace_callback(
			'@
			,,,,(&(.)+?;),,,,
			@x',
			function ($match)
			{
				# http://stackoverflow.com/questions/3005116/how-to-convert-all-characters-to-their-html-entity-equivalent-using-php/
				$convmap = array(0x0, 0xffff, 0, 0xffff);
				$decoded = mb_decode_numericentity($match[1], $convmap, 'UTF-8');
						return $decoded;
			},
			$text
		);
	}

	private function encodeIfEmailElement(\DOMAttr $attr)
	{
		if ($attr->ownerElement->nodeName !== 'a')
		{
			return;
		}

		if(substr($attr->value, 0, 7) !== 'mailto:')
		{
			return;
		}

		$email = $attr->value;
		$anchorText = $attr->ownerElement->nodeValue;

		$attr->value = '';
		$attr->ownerElement->nodeValue = '';

		# first the link itself
		$emailChars = $this->encodeForEmailUse($email);
		$encodedEmail = implode('', $emailChars);
		$attr->appendChild($attr->ownerDocument->createTextNode($encodedEmail));

		# next the anchor text of the a element
		if ($email ===  'mailto:' . $anchorText)
		{
			$anchorTextChars = array_slice($emailChars, 7);
		}
		else
		{
			$anchorTextChars = $this->encodeForEmailUse($anchorText);
		}
		$anchorText = implode('', $anchorTextChars);
		$attr->ownerElement->appendChild(
			$attr->ownerDocument->createTextNode($anchorText)
		);
	}

	/**
	 * @return array with list of characters in $text encoded
	 */
	private function encodeForEmailUse($text)
	{
		// based on/mostly copied from PHPMarkdowns Implementation
		$chars = preg_split('/(?<!^)(?!$)/', $text);
		$seed = (int)abs(crc32($text) / strlen($text)); # Deterministic seed.
		
		foreach ($chars as $key => $char) {
			$ord = ord($char);
			# Ignore non-ascii chars.
			if ($ord < 128) {
				$r = ($seed * (1 + $key)) % 100; # Pseudo-random function.
				# roughly 10% raw, 45% hex, 45% dec
				# '@' *must* be encoded. I insist.
				if ($r > 90 && $char != '@') /* do nothing */;
				else if ($r < 45) $chars[$key] = '&#x'.dechex($ord).';';
				else              $chars[$key] = '&#'.$ord.';';
			}
		}

		return $chars;
	}
}