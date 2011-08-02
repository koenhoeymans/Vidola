<?php

/**
 * @package Vidola
 */
namespace Vidola\OutputBuilder;

/**
 * @package Vidola
 */
interface OutputBuilder
{
	public function setContent($content);

	public function setFileName($fileName);

	public function setTitle($title);

	public function build();
}