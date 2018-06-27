<?php

namespace FcPhp\Context
{
	// use FcPhp\Cache\Interfaces\ICache;
	use FcPhp\Context\Interfaces\IContext;
	use FcPhp\Context\Exceptions\CacheNotFound;
	use FcPhp\Autoload\Interfaces\IAutoload;
	
	class Context implements IContext
	{
		private $ttl = 84000;
		private $key;
		private $cache;
		private $autoload;
		private $vendorPath;
		private $useCache;
		private $context = [];

		public function __construct(string $vendorPath, bool $useCache = true, array $context = [])
		{
			$this->key = md5('context');
			$this->context = $context;
			$this->vendorPath = $vendorPath;
			$this->useCache = $useCache;
		}

		public function autoload(IAutoload $autoload, ?ICache $cache = null) :void
		{
			$hasCache = false;
			$this->autoload = $autoload;
			if($this->useCache) {
				if(is_null($cache)) {
					throw new CacheNotFound();
				}
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

		public function set(string $expression, $value)
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
		    		if(isset($index[$key])) {
		    			$index[$key] = [];
		    		}
		    		$index[$key] = $value;
		    	}
		    }
		    return true;
		}
	}
}