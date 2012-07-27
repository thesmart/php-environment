Environment PHP Library by John Smart ([@thesmart](https://github.com/thesmart))
==========================

Easily manage database connection strings or other configuration data that changes across server environments such a dev and prod.

Example
-------

This runs one time at the start of your void main:

	// construct an instance
	new Env('dev');

	// development http server url
	Env::set('url', array(
		'scheme' => isset($_SERVER) && isset($_SERVER['HTTPS']) ? 'https' : 'http',
		'domain' => 'dev-github.com'
	));

	// development memcached server connection strings
	Env::set('memached.main', array(
		'port' => 11211,
		'addr' => '127.0.0.1'
	));

	// alternative syntax
	Env::set('facebook.appId', '012345678');
	Env::set('facebook.secret', '000000000000000000000000000000'); // keep secret
	Env::set('facebook.namespace', 'app_name_space');
	Env::set('facebook.url', 'https://apps.facebook.com/app_name_space');

Later, you can easily access current environment variables:

	// access using dot-notation
	$facebookConfig = Env::get('facebook');

	$memcached = new \Memcached();
	$memcached->addServer(Env::get('memcached.main.addr'), Env::get('memcached.main.port'));
	
	if (Env::has('memcached')) {
		// go cache some shit
	}

Mix with OS environment variables to define which configuration to use:

	$configToSet	= isset($_SERVER['conf']) ? $_SERVER['conf'] : 'prod';
	require_once PATH_CONFIG . $configToSet . '.php';

# Like this project?

Check out my others.
[@thesmart](https://twitter.com/thesmart)