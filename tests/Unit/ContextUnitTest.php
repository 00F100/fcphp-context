<?php

use PHPUnit\Framework\TestCase;
use FcPhp\Context\Context;
use FcPhp\Context\Interfaces\IContext;

class ContextUnitTest extends TestCase
{
	private $instance;

	public function setUp()
	{
		$context = [
			'cache' => [
				'file' => 'tests/var/cache',
			]
		];

		$this->instance = new Context('tests/var/*/*/config', true, $context);
	}

	public function testInstance()
	{
		$this->assertTrue($this->instance instanceof IContext);
	}
}