<?php
// *   Аўтар: "БуслікДрэў" ( https://buslikdrev.by/ )
// *   © 2016-2022; BuslikDrev - Усе правы захаваныя.
// *   Спецыяльна для сайта: "OpenCart.pro" ( https://opencart.pro/ )

namespace Bus_Cache;
//namespace Opencart\Extension\Bus_Cache\System\library\Bus_Cache;

// забараняем прамы доступ
if (!defined('VERSION')) {
	header('Refresh: 1; URL=/');
	exit('ЗАПРЫШЧАЮ!');
}

class APC {
	private $expire;
	private $active = false;

	public function __construct($expire = 3600) {
		if (!defined('CACHE_PREFIX')) {
			define('CACHE_PREFIX', 'cache_');
		}
		$this->expire = $expire;
		$this->active = ini_get('apc.enabled') && function_exists('apc_cache_info');
	}

	public function get($key) {
		return $this->active ? apc_fetch(CACHE_PREFIX . $key) : false;
	}

	public function set($key, $value, $expire = 0) {
		return $this->active ? apc_store(CACHE_PREFIX . $key, $value, $this->expire) : false;
	}

	public function delete($key) {
		if (!$this->active) {
			return false;
		}

		$cache_info = apc_cache_info('user');
		$cache_list = $cache_info['cache_list'];
		foreach ($cache_list as $entry) {
			if (strpos($entry['info'], CACHE_PREFIX . $key) === 0) {
				apc_delete($entry['info']);
			}
		}
	}

	public function flush($timer = 5) {
		$status = false;

		if (function_exists('apc_clear_cache')) {
			apc_clear_cache('user');
			$status = true;
		}

		return $status;
	}
}