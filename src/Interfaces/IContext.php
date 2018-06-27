<?php

namespace FcPhp\Context\Interfaces
{
	use FcPhp\Cache\Interfaces\ICache;
	use FcPhp\Autoload\Interfaces\IAutoload;

	interface IContext
	{
		public function __construct(array $context = []);

		public function autoload(string $vendorPath, IAutoload $autoload, ?ICache $cache = null) :void;

		public function updateCache() :void;

		public function get(string $expression = null);

		public function set(string $expression, $value) :void;
	}
}