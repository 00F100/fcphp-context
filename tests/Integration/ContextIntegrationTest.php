<?php

use PHPUnit\Framework\TestCase;
use FcPhp\Context\Context;
use FcPhp\Context\Interfaces\IContext;
use FcPhp\Di\Facades\DiFacade;
use FcPhp\Autoload\Autoload;
use FcPhp\Cache\Facades\CacheFacade;

class ContextIntegrationTest extends TestCase
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

	public function testGet()
	{
		$this->assertEquals($this->instance->get('cache.file'), 'tests/var/cache');
	}

	public function testSetGet()
	{
		$this->instance->set('cache.redis.test', 'test');
		$this->assertEquals($this->instance->get('cache.redis.test'), 'test');
	}

	public function testGetAllContext()
	{
		$array = $this->instance->get();
		$this->assertTrue(isset($array['cache']));
	}

	public function testGetOneExpression()
	{
		$array = $this->instance->get('cache');
		$this->assertTrue(isset($array['file']));
	}
}