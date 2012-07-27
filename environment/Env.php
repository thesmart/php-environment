<?php
namespace environment;

use dotty\Dotty;

/**
 * Handles configuration switching
 */
class Env extends \singleton\GlobalSingleton {

	/**
	 * The name of this Env instance
	 * @var string
	 */
	public $name;

	/**
	 * An array of configuration settings, like database connection strings etc.
	 * @var array
	 */
	public $data = array();

	/**
	 * User to handle $this->data
	 * @var Dotty
	 */
	protected $dotty;

	public function __construct($name) {
		$this->name		= $name;
		$this->dotty	= Dotty::with($this->data);

		// save this as the new default instance
		static::setInstance($this);
		static::setInstance($this, $name);
	}

	/**
	 * Get the current instance name
	 * @static
	 * @return null|string
	 */
	public static function name() {
		/** @var $env Env */
		$env = static::getInstance();
		return $env->name;
	}

	/**
	 * Check if a Env name is the current Env
	 * @static
	 * @param string $name				The name of the configuration
	 * @return bool
	 */
	public static function is($name) {
		/** @var $env Env */
		$env = static::getInstance();
		return $name === $env->name;
	}

	/**
	 * Has a configuration variable set
	 *
	 * @static
	 * @param string $key		The name of the configuration variable to get.
	 * @return bool
	 */
	public static function has($key) {
		/** @var $env Env */
		$env = static::getInstance();
		return isset($env) && $env->dotty->one($key)->hasResult();
	}

	/**
	 * Get an configuration variable
	 *
	 * @static
	 * @param string $key		The name of the configuration variable to get.
	 * @return mixed
	 */
	public static function get($key) {
		/** @var $env Env */
		$env = static::getInstance();

		if (static::has($key)) {
			return $env->dotty->result();
		}

		return null;
	}

	/**
	 * @static
	 * @param string $key		The name of the configuration variable to set.
	 * @param mixed $data		The data to set
	 * @return mixed
	 */
	public static function set($key, $data) {
		if (!static::hasInstance()) {
			throw new \RuntimeException(sprintf('Cannot set Env variable "%s" because there is no Env instance.', $key));
		}

		/** @var $env Env */
		$env = static::getInstance();
		$ref = &$env->dotty->ensure($key)->result();
		$ref = $data;

		return $data;
	}
}