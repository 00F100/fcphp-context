<?php

use PHPUnit\Framework\TestCase;
use FcPhp\Context\Interfaces\IContext;
use FcPhp\Context\Context;
use FcPhp\Autoload\Interfaces\IAutoload;
use FcPhp\Cache\Interfaces\ICache;

class ContextUnitTest extends TestCase
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
		$this->autoload = $this->createMock('\FcPhp\Autoload\Interfaces\IAutoload');
		$this->cache = $this->createMock('\FcPhp\Cache\Interfaces\ICache');
		$this->cache
			->expects($this->any())
			->method('has')
			->will($this->returnValue(true));
		$this->cache
			->expects($this->any())
			->method('get')
			->will($this->returnValue([]));
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
		$this->assertEquals($this->instance->get(), $this->context);
		$this->instance->autoload($this->vendorPath, $this->autoload, $this->cache);
	}

	public function testUpdateContext()
	{
		$this->instance->autoload($this->vendorPath, $this->autoload, $this->cache);
		$this->instance->updateCache();
		$this->assertEquals($this->instance->get(), $this->context);
	}

	public function testAutoloadNoCache()
	{
		$this->cache = $this->createMock('\FcPhp\Cache\Interfaces\ICache');
		$this->cache
			->expects($this->any())
			->method('has')
			->will($this->returnValue(false));
		$this->instance->autoload($this->vendorPath, $this->autoload, $this->cache);
		$this->instance->updateCache();
		$this->assertEquals($this->instance->get(), $this->context);
	}
}