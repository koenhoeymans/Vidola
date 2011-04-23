<?php

/**
 * @package Vidola
 */
namespace Vidola\Patterns;

/**
 * @package Vidola
 */
class LinkDefinition
{
	private $name;

	private $url;

	private $title;

	public function __construct($name, $url, $title = null)
	{
		$this->name = $name;
		$this->url = $url;
		$this->title = $title;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getTitle()
	{
		return $this->title;
	}
}