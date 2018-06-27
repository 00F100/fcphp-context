<?php

namespace FcPhp\Context
{
	use FcPhp\Cache\Interfaces\ICache;
	use FcPhp\Context\Interfaces\IContext;
	use FcPhp\Context\Exceptions\CacheNotFound;
	use FcPhp\Autoload\Interfaces\IAutoload;
	
	class Context implements IContext
	{
		/**
		 * @var int $ttl Time to live cache
		 */
		private $ttl = 84000;

		/**
		 * @var string $key Key to store cache
		 */
		private $key;

		/**
		 * @var FcPhp\Cache\Interfaces\ICache $cache
		 */
		private $cache;

		/**
		 * @var FcPhp\Autoload\Interfaces\IAutoload $autoload
		 */
		private $autoload;

		/**
		 * @var string $vendorPath Vendor path to autoload
		 */
		private $vendorPath;

		/**
		 * @var bool $useCache 
		 */
		private $useCache;

		/**
		 * @var array $context Context of application
		 */
		private $context = [];

		/**
		 * Method to construct instance of Context
		 *
		 * @param bool $useCache Flag to use cache (or not)
		 * @param array $context Context to apply
		 * @return void
		 */
		public function __construct(array $context = [])
		{
			$this->key = md5('context');
			$this->context = $context;
		}

		/**
		 * Method to autoload context inside packages
		 *
		 * @param string $vendorPath Path to autoload run
		 * @param FcPhp\Autoload\Interfaces\IAutoload $autoload Instance of Autoload
		 * @param FcPhp\Cache\Interfaces\ICache $cache Instance of Cache
		 * @return void
		 */
		public function autoload(string $vendorPath, IAutoload $autoload, ?ICache $cache = null) :void
		{
			$hasCache = false;
			$this->vendorPath = $vendorPath;
			$this->autoload = $autoload;
			if(!is_null($cache)) {
				$this->useCache = true;
				$this->cache = $cache;
				if($this->cache->has($this->key)) {
					$hasCache = true;
					$this->context = array_replace_recursive($this->cache->get($this->key), $this->context);
				}
			}
			if(!$hasCache) {
				$this->autoload->path($this->vendorPath, ['context'], ['php']);
				$this->context = array_replace_recursive($this->autoload->get('context'), $this->context);
				if($this->useCache) {
					$this->cache->set($this->key, $this->context, $this->ttl);
				}
			}
		}

		/**
		 * Method to update cache of Context
		 *
		 * @return void
		 */
		public function updateCache() :void
		{
			if($this->useCache) {
				$this->autoload->path($this->vendorPath, ['context'], ['php']);
				$this->context = array_replace_recursive($this->autoload->get('context'), $this->context);
				$this->cache->set($this->key, $this->context, $this->ttl);
			}
		}

		/**
		 * Method to get information of Context
		 *
		 * @param string $expression Expression to find inside Context
		 * @return array|string
		 */
		public function get(string $expression = null)
		{
			if(empty($expression)) {
				return $this->context;
			}
			$index = $this->context;
			$keys = explode('.', $expression);
		    foreach ($keys as $key) {
		    	if(isset($index[$key])) {
		        	$index = $index[$key];
		    	}else{
		    		return null;
		    	}
		    }
		    return $index;
		}

		/**
		 * Method to configure information in Context
		 *
		 * @param string $expression Expression to find inside Context
		 * @param string $value Value to save inside key in Context
		 * @return void
		 */
		public function set(string $expression, $value) :void
		{
			$index = &$this->context;
			$keys = explode('.', $expression);
			$count = count($keys)-1;
		    foreach ($keys as $i => $key) {
		    	if($i < $count) {
		    		if(isset($index[$key])) {
		    			$index = &$index[$key];
		    		}else{
		    			$index[$key] = [];
	        			$index = &$index[$key];
		    		}
		    	}else{
		    		$index[$key] = $value;
		    	}
		    }
		}
	}
}