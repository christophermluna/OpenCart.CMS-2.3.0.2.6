<?php
// *   Аўтар: "БуслікДрэў" ( https://buslikdrev.by/ )
// *   © 2016-2022; BuslikDrev - Усе правы захаваныя.
// *   Спецыяльна для сайта: "OpenCart.pro" ( https://opencart.pro/ )

//https://ospanel.io/forum/viewtopic.php?f=3&t=2191&start=10
namespace Bus_Cache;
//namespace Opencart\Extension\Bus_Cache\System\library\Bus_Cache;

// забараняем прамы доступ
if (!defined('VERSION')) {
	header('Refresh: 1; URL=/');
	exit('ЗАПРЫШЧАЮ!');
}

class Mem {
	private $expire;
	private $memcache;

	public function __construct($expire = 3600) {
		if (!defined('CACHEDUMP_LIMIT')) {
			define('CACHEDUMP_LIMIT', 9999);
		}
		if (!defined('CACHE_HOSTNAME')) {
			define('CACHE_HOSTNAME', 'localhost');
		}
		if (!defined('CACHE_PORT')) {
			define('CACHE_PORT', 11211);
		}
		if (!defined('CACHE_PREFIX')) {
			define('CACHE_PREFIX', 'cache_');
		}
		$this->expire = $expire;
		$this->memcache = new \Memcache();
		$this->memcache->pconnect(CACHE_HOSTNAME, CACHE_PORT);
	}

	public function get($key) {
		return $this->memcache->get(CACHE_PREFIX . $key);
	}

	public function set($key, $value, $expire = 3600) {
		return $this->memcache->set(CACHE_PREFIX . $key, $value, MEMCACHE_COMPRESSED, $this->expire);
	}

	public function delete($key) {
		$this->memcache->delete(CACHE_PREFIX . $key);
	}

	public function flush($timer = 5) {
		$status = false;

		if (method_exists($this->memcache, 'flush')) {
			$this->memcache->flush();
			$status = true;
		}
		if (method_exists($this->memcache, 'close')) {
			$this->memcache->close();
		}

		return $status;
	}
}