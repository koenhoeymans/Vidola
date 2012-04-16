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

	public function setPreviousDoc($previous);

	public function setNextDoc($next);

	public function build();
}