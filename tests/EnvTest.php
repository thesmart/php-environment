<?php

namespace environment;

require_once 'bootstrap.php';

class EnvTest extends \PHPUnit_Framework_TestCase {

	public function tearDown() {
		new Env('test');
	}

	public function testNewInstance() {
		$myEnv = new Env('test');
		$this->assertEquals($myEnv, Env::getInstance());
		$this->assertEquals($myEnv, Env::getInstance('test'));

		$theirEnv = new Env('test2');
		$this->assertEquals($theirEnv, Env::getInstance());
		$this->assertEquals($theirEnv, Env::getInstance('test2'));
		$this->assertEquals($myEnv, Env::getInstance('test'));
	}

	public function testAccessors() {
		Env::set('foobar', 'hello');
		$this->assertTrue(Env::has('foobar'));
		$this->assertFalse(Env::has('barbat'));
		$this->assertEquals('hello', Env::get('foobar'));
		$this->assertNull(Env::get('barbat'), 'unset key should be null');

		Env::set('foobar', 'goodbye');
		$this->assertEquals('goodbye', Env::get('foobar'), 'set value should have changed');
		Env::set('barbat', 'hello');
		$this->assertEquals('hello', Env::get('barbat'));

		$this->assertNotNull(Env::name());
		$this->assertTrue(Env::is(Env::name()));
	}

	public function testReadme() {
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

		// access using dot-notation
		$facebookConfig = Env::get('facebook');

//		$memcached = new \Memcached();
//		$memcached->addServer(Env::get('memcached.main.addr'), Env::get('memcached.main.port'));

//

		$this->assertEquals('11211', Env::get('memached.main.port'));
		$this->assertEquals('012345678', $facebookConfig['appId']);
		$this->assertEquals('012345678', Env::get('facebook.appId'));
	}
}