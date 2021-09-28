<?php

namespace Incapption\SimpleApi\Models;

class RouteParameter
{
	/**
	 * @var string
	 */
	private $placeholder;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $value;

	/**
	 * @return string
	 */
	public function getPlaceholder(): string
	{
		return $this->placeholder;
	}

	/**
	 * @param string $placeholder
	 */
	public function setPlaceholder(string $placeholder): void
	{
		$this->placeholder = $placeholder;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getValue(): string
	{
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	public function setValue(string $value): void
	{
		$this->value = $value;
	}
}