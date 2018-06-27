<?php

use PHPUnit\Framework\TestCase;
use FcPhp\Context\Context;
use FcPhp\Context\Interfaces\IContext;
use FcPhp\Di\Facades\DiFacade;
use FcPhp\Autoload\Autoload;
// use FcPhp\Autoload\Interfaces\IAutoload;
use FcPhp\Cache\Facades\CacheFacade;

class ContextIntegrationTest extends TestCase
{
	private $instance;
	private $autoload;
	private $vendorPath;
	private $cache;
	private $context = [
		'cache' => [
			'file' => 'tests/var/cache',
		]
	];

	public function setUp()
	{
		$this->instance = new Context($this->context);
		$this->vendorPath = 'tests/var/*/*/config';
		$this->autoload = new Autoload();
		$this->cache = CacheFacade::getInstance('tests/var/cache');
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

	public function testAutoload()
	{
		$this->instance->autoload($this->vendorPath, $this->autoload, $this->cache);
		$this->assertEquals($this->instance->get(), array_replace_recursive($this->context, require('tests/var/00f100/package/config/context.php')));
		$this->instance->autoload($this->vendorPath, $this->autoload, $this->cache);
	}

	public function testUpdateContext()
	{
		$this->instance->autoload($this->vendorPath, $this->autoload, $this->cache);
		$this->instance->updateCache();
		$this->assertEquals($this->instance->get(), array_replace_recursive($this->context, require('tests/var/00f100/package/config/context.php')));
	}
}