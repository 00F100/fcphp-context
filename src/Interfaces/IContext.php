<?php

namespace FcPhp\Context\Interfaces
{
    use FcPhp\Cache\Interfaces\ICache;
    use FcPhp\Autoload\Interfaces\IAutoload;

    interface IContext
    {
        /**
         * Method to construct instance of Context
         *
         * @param bool $useCache Flag to use cache (or not)
         * @param array $context Context to apply
         * @return void
         */
        public function __construct(array $context = []);

        /**
         * Method to autoload context inside packages
         *
         * @param string $vendorPath Path to autoload run
         * @param FcPhp\Autoload\Interfaces\IAutoload $autoload Instance of Autoload
         * @param FcPhp\Cache\Interfaces\ICache $cache Instance of Cache
         * @return void
         */
        public function autoload(string $vendorPath, IAutoload $autoload, ?ICache $cache = null) :void;

        /**
         * Method to update cache of Context
         *
         * @return void
         */
        public function updateCache() :void;

        /**
         * Method to get information of Context
         *
         * @param string $expression Expression to find inside Context
         * @return array|string
         */
        public function get(string $expression = null);

        /**
         * Method to configure information in Context
         *
         * @param string $expression Expression to find inside Context
         * @param string $value Value to save inside key in Context
         * @return void
         */
        public function set(string $expression, $value) :void;
    }
}
