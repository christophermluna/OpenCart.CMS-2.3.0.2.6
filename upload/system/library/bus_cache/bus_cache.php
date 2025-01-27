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

class Bus_Cache {
	private $registry;
	private $config;
	private $request;
	private $session;
	private $db;
	private $response;
	private $cart;
	private $customer;
	private $affiliate;
	private $document;
	private $fileType = array(
		'woff'  => array('type' => 'font/woff', 'as' => 'font'),
		'woff2' => array('type' => 'font/woff2', 'as' => 'font'),
		'eot'   => array('type' => 'font/eot', 'as' => 'font'),
		'otf'   => array('type' => 'font/otf', 'as' => 'font'),
		'ttf'   => array('type' => 'font/ttf', 'as' => 'font'),
		'svg'   => array('type' => 'image/svg+xml', 'as' => 'image'),
		'svgz'  => array('type' => 'image/svg+xml', 'as' => 'image'),
		'png'   => array('type' => 'image/png', 'as' => 'image'),
		'jpg'   => array('type' => 'image/jpeg', 'as' => 'image'),
		'jpeg'  => array('type' => 'image/jpeg', 'as' => 'image'),
		'gif'   => array('type' => 'image/gif', 'as' => 'image'),
		'webp'  => array('type' => 'image/webp', 'as' => 'image'),
		'bmp'   => array('type' => 'image/bmp', 'as' => 'image'),
		'ico'   => array('type' => 'image/x-icon', 'as' => 'image'),
		'mp3'   => array('type' => 'audio/mp3', 'as' => 'audio'),
		'mp4'   => array('type' => 'video/mp4', 'as' => 'video'),
		'webm'  => array('type' => 'video/webm', 'as' => 'video'),
		'css'   => array('type' => 'text/css', 'as' => 'style'),
		'js'    => array('type' => 'text/javascript', 'as' => 'script'),
		//'html'  => array('type' => 'text/html', 'as' => 'document'),
		//'xml'   => array('type' => 'text/xml', 'as' => 'document'),
		//'json'  => array('type' => 'text/plain', 'as' => 'xhr'),
	);
	private $setting_default = array(
		'status'                             => false,
		'cache_status'                       => false,
		'cache_con'                          => false,
		'cache_ses'                          => false,
		'cache_cok'                          => false,
		'cache_par'                          => array(
			'cache_off',
			'_route_',
			'route',
			'blog_article_id',
			'article_id',
			'category_id',
			'path',
			'manufacturer_id',
			'product_id',
			'page',
			'sort',
			'order',
			'limit',
			'language_id'
		),
		'cache_onrot'                        => false,
		'cache_rot'                          => false,
		'cache_customer'                     => false,
		'cache_cart'                         => false,
		'cache_cart_count'                   => 0,
		'cache_oc'                           => false,
		'cache_controller'                   => false,
		'cache_model'                        => false,
		'cache_engine'                       => false,
		'cache_engine_mine'                  => false,
		'cache_expire'                       => 3600,
		'cache_expire_cart'                  => 3600,
		'cache_expire_controller'            => 3600,
		'cache_expire_model'                 => 3600,
		'cache_expire_all'                   => 3600,
		'pagespeed_status'                   => false,
		'pagespeed_onrot'                    => false,
		'pagespeed_rot'                      => false,
		'pagespeed_attribute_w_h'            => false,
		'pagespeed_lazy_load_images'         => 0,
		'pagespeed_lazy_load_html'           => array(),
		'pagespeed_html_replace'             => false,
		'pagespeed_html_min'                 => 0,
		'pagespeed_css_replace'              => false,
		'pagespeed_css_min'                  => 0,
		'pagespeed_css_min_links'            => false,
		'pagespeed_css_min_download'         => false,
		'pagespeed_css_min_exception'        => false,
		'pagespeed_css_min_font'             => false,
		'pagespeed_css_min_display'          => false,
		'pagespeed_css_min_url'              => false,
		'pagespeed_css_critical'             => false,
		'pagespeed_css_critical_status'      => false,
		'pagespeed_css_critical_name'        => '',
		'pagespeed_css_critical_all'         => true,
		'pagespeed_css_critical_time'        => 1,
		'pagespeed_css_critical_elements'    => "font-face\r\nkeyframes\r\n*\r\n::after, ::before\r\n]",
		'pagespeed_css_critical_links'       => false,
		'pagespeed_css_inline_transfer'      => 0,
		'pagespeed_css_inline_exception'     => false,
		'pagespeed_css_events'               => false,
		'pagespeed_css_style'                => false,
		'pagespeed_js_replace'               => false,
		'pagespeed_js_min'                   => 0,
		'pagespeed_js_min_links'             => false,
		'pagespeed_js_min_download'          => false,
		'pagespeed_js_min_exception'         => false,
		'pagespeed_js_inline_event'          => false,
		'pagespeed_js_inline_event_time'     => 0,
		'pagespeed_js_inline_transfer'       => 0,
		'pagespeed_js_inline_transfer_onrot' => false,
		'pagespeed_js_inline_exception'      => false,
		'pagespeed_js_events'                => false,
		'pagespeed_js_script'                => false,
		'debug'                              => false,
		'getDebugSpeed'                      => false,
	);
	private $outputTransfer = array(
		'css' => array('', '', '', '', ''),
		'css_inline' => array('', '', '', '', ''),
		'js' => array('', '', '', '', ''),
		'js_inline' => array('', '', '', '', ''),
	);
	private $output = '';
	private $getDebugSpeed = '';
	private $getDebugTime = 0;
	private $isUser = false;
	private $cacheData = array();

	//private function start() {}
	//public function output() {}
	//public function controller() {}
	//public function model() {}
	//private function realUrlCSS() {}
	//private function minCSS() {}
	//private function minJS() {}
	//private function minHTML() {}
	//private function setDebugSpeed() {}
	//private function examination() {}

	public function __construct($registry = false, $cache_timer = 0) {
		if ($registry) {
			$this->registry = $registry;
			$this->start($cache_timer);
		}
	}

	private function start($cache_timer = 0) {
		// загрузка данных
		$this->config = $this->registry->get('config');
		$setting = $this->config->get('bus_cache');
		if (empty($setting['status']) || !isset($setting['store'][$this->config->get('config_store_id')])) {
			return false;
		}
		$this->request = $this->registry->get('request');
		$this->session = $this->registry->get('session');
		$this->db = $this->registry->get('db');
		$this->response = $this->registry->get('response');
		$this->cart = $this->registry->get('cart');
		$this->customer = $this->registry->get('customer');
		$this->affiliate = $this->registry->get('affiliate');

		$setting_default = $this->setting_default;
		$setting_default['config_theme'] = ($this->config->get('config_template') ? $this->config->get('config_template') : ($this->config->get('theme_' . str_replace('theme_', '', $this->config->get('config_theme')) . '_directory') ? $this->config->get('theme_' . str_replace('theme_', '', $this->config->get('config_theme')) . '_directory') : $this->config->get('config_theme')));
		$setting_default['config_store_id'] = (int)$this->config->get('config_store_id');
		$setting_default['config_language_id'] = (int)$this->config->get('config_language_id');
		$setting_default['config_customer_group_id'] = (int)$this->config->get('config_customer_group_id');
		$setting_default['config_maintenance'] = (int)$this->config->get('config_maintenance');
		$setting_default['server'] = (!empty($this->request->server['HTTPS']) ? $this->config->get('config_ssl') : $this->config->get('config_url'));
		$setting_default['HTTP_HOST'] = (empty($this->request->server['HTTP_HOST']) ? $this->request->server['HTTP_HOST'] : parse_url($setting_default['server'], PHP_URL_HOST));

		$setting_default['route'] = false;
		$setting_default['keyword'] = false;
		$setting_default['config'] = array();
		$setting_default['session'] = array();
		$setting_default['cookie'] = array();
		$setting_default['params'] = array();

		if (is_array($setting)) {
			foreach ($setting as $key => $result) {
				$setting_default[$key] = $result;
			}
		}

		$setting = $setting_default;
		$this->setting_default = $setting_default;
		if (!defined('BUS_CACHE_SETTING')) {
			//('BUS_CACHE_SETTING', $setting_default);
		}

		// время загрузки страницы
		$cache_timer = (float)$cache_timer;
		$cache_time = $cache_timer;
		if (version_compare(phpversion(), '5.4.0', '>=') && isset($this->request->server['REQUEST_TIME_FLOAT'])) {
			$cache_timer = (float)str_replace(',', '.', $this->request->server['REQUEST_TIME_FLOAT']);
		} elseif (defined('BUS_CACHE_TIMER')) {
			$cache_timer = (float)BUS_CACHE_TIMER;
		}

		// администратор
		if (version_compare(VERSION, '2.2.0', '<')) {
			$user = new \User($this->registry);
		} else {
			$user = new \Cart\User($this->registry);
		}

		// debug режим
		if ($user->isLogged()) {
			$this->isUser = true;
			if (!$setting['debug']) {
				$setting['cache_status'] = false;
			}
		} else {
			$setting['debug'] = false;
			$this->setting_default['debug'] = false;
		}

		if ($setting['debug']) {
			ini_set('display_errors', 1);
			ini_set('display_startup_errors', 1);
			ini_set('error_reporting', E_ALL);
		}

		// critical
		if ($user->isLogged() && $setting['pagespeed_css_critical'] && $setting['pagespeed_status'] && $setting['pagespeed_css_min']) {
			if (!empty($this->request->post['bus_critical_name']) && utf8_strtolower(pathinfo($this->request->post['bus_critical_name'], PATHINFO_EXTENSION)) == 'css') {
				$setting['pagespeed_css_critical_name'] = 'critical_' . preg_replace('/[^A-Z0-9\.]/i', '', $this->request->post['bus_critical_name']);
			}
			if (!empty($this->request->post['bus_critical']) && $setting['pagespeed_css_critical_name'] && !is_file(DIR_IMAGE . 'bus_cache/' . $setting['pagespeed_css_critical_name'])) {
				if (!is_dir(DIR_IMAGE . 'bus_cache/')) {
					mkdir(DIR_IMAGE . 'bus_cache/', 0755);
				}
				file_put_contents(DIR_IMAGE . 'bus_cache/' . $setting['pagespeed_css_critical_name'], $this->minCSS(html_entity_decode($this->request->post['bus_critical'], ENT_QUOTES, 'UTF-8'), $setting['pagespeed_css_min']));
			}
		}

		// отключаем кэш, если пользователь авторизован
		if (!$setting['cache_customer'] && $setting['cache_status']) {
			if ($this->customer->isLogged() || method_exists($this->affiliate, 'isLogged') && $this->affiliate->isLogged()) {
				$setting['cache_status'] = false;
			}
		}

		// отключаем кэш, если товаров в корзине много
		$cart = array();

		if ($setting['cache_status']) {
			$cart = $this->cart->getProducts();
			if (count($cart) > $setting['cache_cart_count']) {
				$setting['cache_status'] = false;
			}
		}

		// отключаем кэш, если магазин на обслуживании
		if ($setting['config_maintenance'] && !$setting['debug']) {
			$setting['cache_status'] = false;
		}

		// условие обработки роута и keyword
		if ($setting['cache_status'] || $setting['cache_controller'] || $setting['cache_model'] || $setting['pagespeed_status']) {
			if (!empty($this->request->get)) {
				foreach ($this->request->get as $key => $result) {
					$setting['params'][$key] = $result;
				}
			}
			if (!empty($this->request->post)) {
				foreach ($this->request->post as $key => $result) {
					$setting['params'][$key] = $result;
				}
			}
			if (isset($this->request->get['_route_'])) {
				$setting['route'] = $this->request->get['_route_'];
				$setting['keyword'] = utf8_strtolower(basename($setting['route']));
				$str = strstr($setting['keyword'], '.', true);
				if ($str) {
					$setting['keyword'] = $str;
				}

				if (version_compare(VERSION, '3.0.0', '>')) {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE `store_id` = '" . $setting['config_store_id'] . "' AND `language_id` = '" . $setting['config_language_id'] . "' AND `keyword` = '" . $this->db->escape($setting['keyword']) . "' LIMIT 1");
				} else {
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "url_alias WHERE `keyword` = '" . $this->db->escape($setting['keyword']) . "' LIMIT 1");
				}

				if ($query->num_rows && $query->row['query']) {
					$setting['route'] = $query->row['query'];
					$param = strstr($query->row['query'], '=', true);
					if ($param) {
						$setting['params'][$param] = substr(strstr($query->row['query'], '='), 1);
					}
				}
			} elseif (isset($this->request->get['route'])) {
				$setting['route'] = $this->request->get['route'];
			} elseif (isset($this->request->post['route'])) {
				$setting['route'] = $this->request->post['route'];
			} else {
				$setting['route'] = ($this->config->get('action_default') ? $this->config->get('action_default') : 'common/home');
			}
			if ($setting['route']) {
				$setting['route'] = utf8_strtolower($setting['route']);
			}

			// запрещаем кэшировать, если параметры не разрешены
			if ($setting['cache_par']) {
				if ($setting['cache_par'][0] == 'cache_off') {
					unset($setting['cache_par'][0]);
					$params_count = count($setting['params']);
				}
				foreach ($setting['params'] as $key => $result) {
					if (!in_array($key, $setting['cache_par'])) {
						unset($setting['params'][$key]);
					}
				}
				if (!empty($params_count) && $params_count != count($setting['params'])) {
					$setting['cache_status'] = false;
				}
			}
		}

		// параметры работы кэша
		if ($setting['cache_onrot'] && $setting['cache_status']) {
			$exceptions = utf8_strtolower($setting['cache_onrot']);
			$exceptions = explode("\r\n", $exceptions);

			$setting['cache_status'] = false;
			$setting['cache_rot'] = false;

			foreach ($exceptions as $exception) {
				$exception = explode('|', $exception);
				if ($exception[0] && strpos($setting['route'], $exception[0]) !== false || $setting['keyword'] && strpos($exception[0], $setting['keyword']) !== false) {
					if (!empty($exception[1])) {
						$setting['cache_expire_all'] = $exception[1];
					}
					if (!empty($exception[2])) {
						$setting['cache_con'] = str_replace(',', '|', $exception[2]);
					}
					if (!empty($exception[3])) {
						$setting['cache_ses'] = str_replace(',', '|', $exception[3]);
					}
					$setting['cache_status'] = true;
				}
			}
		}

		// параметры исключения из кэша
		if ($setting['cache_rot'] && $setting['cache_status']) {
			$exceptions = $setting['cache_rot'];
			$exceptions = explode("\r\n", $exceptions);

			foreach ($exceptions as $exception) {
				if ($exception && strpos($setting['route'], $exception) !== false || $setting['keyword'] && strpos($exception, $setting['keyword']) !== false) {
					$setting['cache_status'] = false;
				}
			}
		}

		// данные конфиг
		if ($setting['cache_con'] && ($setting['cache_status'] || $setting['cache_controller'] || $setting['cache_model'])) {
			$exceptions = $setting['cache_con'];
			$exceptions = explode("\r\n", $exceptions);

			foreach ($exceptions as $exception) {
				if ($exception && substr($exception, 0, 1) != ';') {
					$exception = explode('|', $exception);

					if ($this->config->has($exception[0])) {
						$setting['config'] = $this->examination($setting['config'], array($exception[0] => $this->config->get($exception[0])), $exception);
					}
				}
			}
		}

		// данные сессии
		if ($setting['cache_ses'] && ($setting['cache_status'] || $setting['cache_controller'] || $setting['cache_model'])) {
			$exceptions = $setting['cache_ses'];
			$exceptions = explode("\r\n", $exceptions);

			foreach ($exceptions as $exception) {
				if ($exception && substr($exception, 0, 1) != ';') {
					$exception = explode('|', $exception);

					if ($exception[0]) {
						$setting['session'] = $this->examination($setting['session'], $this->session->data, $exception);
					}
				}
			}

			unset($setting['session']['user_id'], $setting['session']['token'], $setting['session']['user_token']);
		}

		// данные cookie
		if ($setting['cache_cok'] && ($setting['cache_status'] || $setting['cache_controller'] || $setting['cache_model'])) {
			$exceptions = $setting['cache_cok'];
			$exceptions = explode("\r\n", $exceptions);

			foreach ($exceptions as $exception) {
				if ($exception && substr($exception, 0, 1) != ';') {
					$exception = explode('|', $exception);

					if ($exception[0]) {
						$setting['cookie'] = $this->examination($setting['cookie'], $this->request->cookie, $exception);
					}
				}
			}

			unset($setting['cookie']['user_id'], $setting['cookie']['token'], $setting['cookie']['user_token'], $setting['cookie']['PHPSESSID'], $setting['cookie']['OCSESSID']);
		}

		// устанавливаем индивидуальный обработчик кэша
		if ($setting['cache_engine_mine']) {
			$setting['cache_engine'] = $setting['cache_engine_mine'];
			$this->setting_default['cache_engine'] = $setting['cache_engine_mine'];
		}

		// контроллируем другой кэш OpenCart
		if ($setting['cache_oc']) {
			$this->registry->set('cache', new \Cache('Bus_Cache\\' . $setting['cache_engine'], $setting['cache_expire']));
		}

		// cache
		if ($setting['cache_status']) {
			// данные поддержки images
			if (isset($this->request->server['HTTP_ACCEPT']) && stripos($this->request->server['HTTP_ACCEPT'], 'image/webp') !== false) {
				$img = 'webp';
			} else {
				$img = 'img';
			}

			// разнообразие кэша по категориям и производителям
			$url = explode('=', $setting['route']);

			if (isset($url[1]) && in_array($url[0], array('path', 'category_id', 'manufacturer_id'))) {
				$category_id = explode('_', (string)$url[1]);
				$category_id = $url[0] . (int)array_pop($category_id);
			} else {
				$category_id = 'other';
			}

			// для точечного удаления кэша
			$name_route = substr(strstr($setting['route'], '/'), 1);
			if ($name_route && strstr($name_route, '/', true)) {
				$name_route = strstr($name_route, '/', true);
			}
			if (!$name_route) {
				$name_route = strstr($setting['route'], '_', true);
			}

			// загружаем кэш
			if ($setting['cache_engine'] == 'buslik') {
				$cache_dir = 'buslik/' . $name_route . '.' . md5($setting['debug'] . $img . $category_id . http_build_query(array($setting['config'], $setting['session'], $setting['cookie'], $cart))) . '/';
				$cache_name = $cache_dir . md5(http_build_query($setting['params']));

				if (!is_dir(DIR_CACHE . $cache_dir)) {
					mkdir(DIR_CACHE . $cache_dir, 0755, true);
				}

				$files = glob(DIR_CACHE . $cache_name . '*', GLOB_NOSORT|GLOB_BRACE);

				if ($files) {
					foreach ($files as $file) {
						$time = substr(strrchr($file, '.'), 1);

						if ($time < time()) {
							if (file_exists($file)) {
								unlink($file);
							}
						}
					}

					if (file_exists($files[0])) {
						$cache_data = json_decode(file_get_contents($files[0]), true);
					} else {
						$cache_data =  false;
					}
				} else {
					$cache_data =  false;
				}
			} else {
				$cache_name = 'buslik.' . $name_route . '.' . md5($setting['debug'] . $img . $category_id . http_build_query(array($setting['config'], $setting['session'], $setting['params'], $cart)));
				$cache_data = (new \Cache('Bus_Cache\\' . $setting['cache_engine'], $setting['cache_expire_all']))->get($cache_name);
			}

			// обрабатываем и выводим кэш
			if (!empty($cache_data['output'])) {
				if (!headers_sent()) {
					foreach ($cache_data['headers'] as $header) {
						header($header, true);
					}
				}

				// critical
				if ($setting['pagespeed_css_min'] && $setting['pagespeed_css_critical'] && !empty($cache_data['pagespeed_css_critical_name']) && is_file(DIR_IMAGE . 'bus_cache/' . $cache_data['pagespeed_css_critical_name'])) {
					$cache_data['output'] = str_replace('/' . str_replace('critical_', '', $cache_data['pagespeed_css_critical_name']), '/' . $cache_data['pagespeed_css_critical_name'], $cache_data['output']);
				}

				// debug режим
				if ($setting['debug']) {
					if (!empty($cache_data['debug_times'])) {
						$this->getDebugSpeed = $cache_data['debug_times'];
					}

					if (isset($cache_time)) {
						$this->setDebugSpeed(array('cache_time_status' => round(microtime(true) - $cache_time, 3)));
					}

					if (isset($cache_timer)) {
						$this->setDebugSpeed(array('cache_timer_status' => round(microtime(true) - $cache_timer, 3)));
					}

					$cache_data['output'] = str_replace('<body', $this->getDebugSpeed . '<body', $cache_data['output']);
				}

				echo $cache_data['output'];
				exit;
			}
		}

		// кэширование контроллеров
		if ($setting['cache_controller']) {
			if ($setting['debug']) {
				$this->getDebugTime = microtime(true);
			}

			$this->setting_default['cache_controller'] = array();

			$exceptions = $setting['cache_controller'];
			$exceptions = explode("\r\n", $exceptions);

			foreach ($exceptions as $exception) {
				if ($exception && substr($exception, 0, 1) != ';') {
					$exception = explode('|', $exception);
					if ($exception[0]) {
						$exception[0] = md5($exception[0]);
						$this->setting_default['cache_controller'][$exception[0]]['expire'] = (!empty($exception[1]) ? (int)$exception[1] : $setting['cache_expire_controller']);

						if (!empty($exception[2])) {
							$config = array();
							foreach (explode(',', $exception[2]) as $res) {
								$res = explode('/', $res);
								if ($this->config->has($res[0])) {
									$config = $this->examination($config, array($res[0] => $this->config->get($res[0])), $res);
								}
							}
						} else {
							$config = $setting['session'];
						}
						$this->setting_default['cache_controller'][$exception[0]]['config'] = $config;

						$session = array();
						if (!empty($exception[3])) {
							foreach (explode(',', $exception[3]) as $res) {
								$res = explode('/', $res);
								if ($res[0]) {
									$session = $this->examination($session, $this->session->data, $res);
								}
							}

							unset($session['user_id'], $session['token'], $session['user_token']);
						}
						$this->setting_default['cache_controller'][$exception[0]]['session'] = $session;

						$cookie = array();
						if (!empty($exception[4])) {
							foreach (explode(',', $exception[4]) as $res) {
								$res = explode('/', $res);
								if ($res[0]) {
									$cookie = $this->examination($cookie, $this->request->cookie, $res);
								}
							}

							unset($cookie['user_id'], $cookie['token'], $cookie['user_token'], $cookie['PHPSESSID'], $cookie['OCSESSID']);
						}
						$this->setting_default['cache_controller'][$exception[0]]['cookie'] = $cookie;

						$params = array();
						if (!empty($exception[5])) {
							foreach (explode(',', $exception[5]) as $res) {
								if ($res) {
									if (isset($this->request->get[$res])) {
										$params[$res] = $this->request->get[$res];
									}
									if (isset($this->request->post[$res])) {
										$params[$res] = $this->request->post[$res];
									}
								}
							}
						}
						$this->setting_default['cache_controller'][$exception[0]]['params'] = $params;
					}
				}
			}

			if ($setting['debug']) {
				$this->setDebugSpeed(array('name' => 'Время обработки кэша контроллеров', 'time' => round(microtime(true) - $this->getDebugTime, 3)));
			}
		}

		// кэширование моделей
		if ($setting['cache_model']) {
			if ($setting['debug']) {
				$this->getDebugTime = microtime(true);
			}

			$this->setting_default['cache_model'] = array();

			$exceptions = $setting['cache_model'];
			$exceptions = explode("\r\n", $exceptions);

			foreach ($exceptions as $exception) {
				if ($exception && substr($exception, 0, 1) != ';') {
					$exception = explode('|', $exception);
					if ($exception[0]) {
						$method = substr($exception[0], strrpos($exception[0], '/') + 1);
						$exception[0] = md5(substr($exception[0], 0, strrpos($exception[0], '/')));
						$this->setting_default['cache_model'][$exception[0]][$method]['method'] = $method;
						$this->setting_default['cache_model'][$exception[0]][$method]['expire'] = (!empty($exception[1]) ? (int)$exception[1] : $setting['cache_expire_model']);

						if (!empty($exception[2])) {
							$config = array();
							foreach (explode(',', $exception[2]) as $res) {
								$res = explode('/', $res);
								if ($this->config->has($res[0])) {
									$config = $this->examination($config, array($res[0] => $this->config->get($res[0])), $res);
								}
							}
						} else {
							$config = $setting['config'];
						}
						$this->setting_default['cache_model'][$exception[0]][$method]['config'] = $config;

						$session = array();
						if (!empty($exception[3])) {
							foreach (explode(',', $exception[3]) as $res) {
								$res = explode('/', $res);
								if ($res[0]) {
									$session = $this->examination($session, $this->session->data, $res);
								}
							}

							unset($session['user_id'], $session['token'], $session['user_token']);
						}
						$this->setting_default['cache_model'][$exception[0]][$method]['session'] = $session;

						$cookie = array();
						if (!empty($exception[4])) {
							foreach (explode(',', $exception[4]) as $res) {
								$res = explode('/', $res);
								if ($res[0]) {
									$cookie = $this->examination($cookie, $this->request->cookie, $res);
								}
							}

							unset($cookie['user_id'], $cookie['token'], $cookie['user_token'], $cookie['PHPSESSID'], $cookie['OCSESSID']);
						}
						$this->setting_default['cache_model'][$exception[0]][$method]['cookie'] = $cookie;

						$params = array();
						if (!empty($exception[5])) {
							foreach (explode(',', $exception[5]) as $res) {
								if ($res) {
									if (isset($this->request->get[$res])) {
										$params[$res] = $this->request->get[$res];
									}
									if (isset($this->request->post[$res])) {
										$params[$res] = $this->request->post[$res];
									}
								}
							}
						}
						$this->setting_default['cache_model'][$exception[0]][$method]['params'] = $params;
					}
				}
			}

			if ($setting['debug']) {
				$this->setDebugSpeed(array('name' => 'Время обработки кэша моделей', 'time' => round(microtime(true) - $this->getDebugTime, 3)));
			}
		}

		// lazy-load-html
		if ($setting['pagespeed_lazy_load_html']) {
			$exceptions = utf8_strtolower($setting['pagespeed_lazy_load_html']);
			$exceptions = explode("\r\n", $exceptions);
			$this->setting_default['pagespeed_lazy_load_html'] = array();

			foreach ($exceptions as $exception) {
				if ($exception && substr($exception, 0, 1) != ';') {
					$exception = explode('|', $exception);
					if ($exception[0] == '#' || $exception[0] && strpos($setting['route'], $exception[0]) !== false || $setting['keyword'] && strpos($exception[0], $setting['keyword']) !== false) {
						if (!empty($exception[1])) {
							$this->setting_default['pagespeed_lazy_load_html'][$exception[1]]['res'] = (int)(!empty($exception[2]) ? $exception[2] : 0);
						}
					}
				}
			}
		}

		// параметры работы оптимизации
		if ($setting['pagespeed_onrot'] && $setting['cache_status']) {
			$exceptions = utf8_strtolower($setting['pagespeed_onrot']);
			$exceptions = explode("\r\n", $exceptions);

			$setting['pagespeed_onrot'] = false;

			foreach ($exceptions as $exception) {
				if ($exception && strpos($setting['route'], $exception) !== false || $setting['keyword'] && strpos($exception, $setting['keyword']) !== false) {
					$setting['pagespeed_onrot'] = true;
				}
			}
		}

		// параметры исключения из оптимизации
		if ($setting['pagespeed_rot'] && $setting['pagespeed_status']) {
			$exceptions = utf8_strtolower($setting['pagespeed_rot']);
			$exceptions = explode("\r\n", $exceptions);

			foreach ($exceptions as $exception) {
				if ($exception && strpos($setting['route'], $exception) !== false || $setting['keyword'] && strpos($exception, $setting['keyword']) !== false) {
					$setting['pagespeed_status'] = false;
				}
			}
		}

		// отправка данных
		if ($setting['cache_status'] || $setting['pagespeed_status']) {
			if ($setting['cache_status']) {
				$setting['cache_name'] = $cache_name;
			}
			$setting['cache_timer'] = $cache_timer;
			$setting['cache_time'] = (microtime(true) - $cache_time);
			$setting['getDebugSpeed'] = $this->getDebugSpeed;

			$this->response->setBuslikCache($setting);
		}
	}

	public function output($output = '', $setting = array()) {
		if (!$output || !$setting) {
			return $output;
		}
		$setting_default = $this->setting_default;
		$setting_default['config_theme'] = false;
		$setting_default['server'] = false;
		$setting_default['HTTP_HOST'] = false;

		$setting_default['headers'] = array();
		$setting_default['route'] = false;
		$setting_default['keyword'] = false;
		$setting_default['styles'] = array();
		$setting_default['styles_after'] = array();
		$setting_default['scripts'] = array();
		$setting_default['scripts_after'] = array();

		if (is_array($setting)) {
			foreach ($setting as $key => $result) {
				$setting_default[$key] = $result;
			}
		}

		$setting = $setting_default;

		if ($setting['status']) {
			$cache_time = microtime(true);

			if (stripos($output, '<!DOCTYPE html') === false && !$setting['pagespeed_onrot']) {
				$setting['pagespeed_status'] = false;
			}

			// pagespeed
			if ($setting['pagespeed_status']) {
				if ($setting['debug']) {
					$this->getDebugTime = microtime(true);
				}

				// проставляем атрибуты на изображения
				if ($setting['pagespeed_attribute_w_h']) {
					$output = preg_replace('!<img(.*?)src="(.[^\"]*?)-(\d{1,5})x(\d{1,5})(.[^\"\d]*?)"!', '<img$1src="$2-$3x$4$5" width="$3" height="$4"', $output);
				}

				// подгружаем html
				if ($setting['pagespeed_lazy_load_images'] == 1) {
					$output = str_replace('<base', '<script src="' . $setting['server'] . 'catalog/view/theme/default/javascript/bus_cache/bus_loading_lazy.js?time=' . $setting['time_save'] . '&v=' . $setting['version'] . '" type="text/javascript" async="true"></script>' . PHP_EOL . '<base', $output);
					$output = str_replace('<img', '<img loading="lazy"', $output);
					$output = preg_replace('~<(frame|iframe)([^>]*)src=([^>]*)>~ix', '<$1$2loading="lazy" data-busloadinglazy-src=$3><noscript><$1$2src=$3></noscript>', $output);
				} elseif ($setting['pagespeed_lazy_load_images'] == 2) {
					$output = str_replace('<base', '<style type="text/css">body [loading="lazy"][data-busloadinglazy-src]{opacity:0}</style>' . PHP_EOL . '<noscript><style type="text/css">body [loading="lazy"][data-busloadinglazy-src]{display:none !important}</style></noscript>' . PHP_EOL . '<script src="' . $setting['server'] . 'catalog/view/theme/default/javascript/bus_cache/bus_loading_lazy.js?v=' . $setting['version'] . '" type="text/javascript" async="true"></script>' . PHP_EOL . '<base', $output);
					$output = preg_replace('~<(img|frame|iframe)([^>]*)src=([^>]*)>~ix', '<$1$2loading="lazy" data-busloadinglazy-src=$3><noscript><$1$2src=$3></noscript>', $output);
				} else {
					if ($setting['pagespeed_lazy_load_html']) {
						$output = str_replace('<base', '<script src="' . $setting['server'] . 'catalog/view/theme/default/javascript/bus_cache/bus_loading_lazy.js?v=' . $setting['version'] . '" type="text/javascript" async=""></script>' . PHP_EOL . '<base', $output);
					}
				}

				if ($setting['pagespeed_lazy_load_html']) {
					$output = preg_replace_callback('~<busloadinglazy>.*?</busloadinglazy>~iSs', function ($matches) {
						if (!empty($matches[0])) {
							$matches[0] = str_ireplace(array('<noscript', 'noscript>', 'busloadinglazy>'), array('<div data-busloadinglazy-remove=""', 'div>', 'noscript>'), $matches[0]);
						}

						return $matches[0];
					}, $output);
				}

				if ($setting['debug']) {
					$this->setDebugSpeed(array('name' => 'Время обработки атрибут width, height, loading', 'time' => round(microtime(true) - $this->getDebugTime, 3)));
				}

				// сжимаем стили
				if ($setting['pagespeed_css_min']) {
					if ($setting['debug']) {
						$this->getDebugTime = microtime(true);
					}

					$styles = $setting['styles'];
					$styles_replace = '';
					$setting['styles'] = array();
					$setting['styles_after'] = array();

					if ($setting['pagespeed_css_min_links']) {
						$css_links = str_replace('&amp;', '&', $setting['pagespeed_css_min_links']);
						$css_links = explode("\r\n", $css_links);
						foreach ($css_links as $style) {
							if ($style && substr($style, 0, 1) != ';') {
								$st = explode('|', $style);
								if (strpos($output, $st[0]) === false) {
									continue;
								}
								$style = array();
								$style['href'] = $st[0];
								$style['attribute'] = 'type="text/css" rel="preload" media="screen" as="style"';
								if (isset($st[1])) {
									$style['attribute'] = $st[1];
									unset($st[1]);
								}

								$styles_replace .= ($styles_replace ? '|' : false) . preg_quote($style['href'], '~');
								if (in_array($style['href'] . '|after', $css_links)) {
									$setting['styles_after'][] = array(
										'href'      => $style['href'],
										'attribute' => $style['attribute']
									);
								} else {
									$setting['styles'][] = array(
										'href'      => $style['href'],
										'attribute' => $style['attribute']
									);
								}
							}
						}
					}

					if ($styles) {
						$css_links = str_replace('&amp;', '&', $setting['pagespeed_css_min_exception']);
						$css_links = explode("\r\n", $css_links);
						foreach ($styles as $style) {
							if ($style) {
								$href = strstr($style['href'], '?', true);
								if (!$href) {
									$href = $style['href'];
								}
								if (!in_array($href, $css_links) || in_array(';' . $href, $css_links)) {
									$styles_replace .= ($styles_replace ? '|' : false) . preg_quote($style['href'], '~');
									if (in_array($href . '|after', $css_links) || in_array($style['href'] . '|after', $css_links)) {
										$setting['styles_after'][] = array(
											'href'      => $style['href'],
											'attribute' => 'type="text/css" rel="preload ' . $style['rel'] . '" media="' . $style['media'] . '" as="style" '
										);
									} else {
										$setting['styles'][] = array(
											'href'      => $style['href'],
											'attribute' => 'type="text/css" rel="preload ' . $style['rel'] . '" media="' . $style['media'] . '" as="style" '
										);
									}
								}
							}
						}
					}

					if ($styles_replace) {
						$output = preg_replace('~<link(.[^>]*?)href="(' . $styles_replace . ')([^>]*?)>~iS', '', $output);
					}
				}

				if ($setting['styles'] && is_array($setting['styles']) && $setting['pagespeed_css_min']) {
					$dir = str_replace(basename(DIR_APPLICATION) . '/', '', DIR_APPLICATION);
					$css = array(
						'name' => '',
						'content' => '',
						'styles' => array(),
					);

					foreach ($setting['styles'] as $style) {
						$her = (strpos($style['href'], '//') !== false);
						if (!$her || $her && $setting['HTTP_HOST'] && strpos($style['href'], $setting['HTTP_HOST']) !== false) {
							$href = explode('?', $style['href']);
							$href = $href[0];
							$href = str_replace(array('..', 'https://' . $setting['HTTP_HOST'] . '/', 'http://' . $setting['HTTP_HOST'] . '/', '://' . $setting['HTTP_HOST'] . '/', '//' . $setting['HTTP_HOST'] . '/'), '', $href);
							//$href = parse_url($href, PHP_URL_PATH);
							if (substr($href, 0, 1) == '.') {
								$href = substr($href, 1);
							}
							if (substr($href, 0, 1) == '/') {
								$href = substr($href, 1);
							}
							$file = $dir . $href;
						} else {
							$href = md5($style['href']) . '.css';
							$file = DIR_IMAGE . 'bus_cache/download/' . $href;
							$href = basename(DIR_IMAGE) . '/cache/bus_cache/download/' . $href;
						}
						if (\is_file($file)) {
							$file = file_get_contents($file);
							$css['name'] .= ($css['name'] ? '|' : false) . $href;
							$css['content'] .=  '/* ' . $href . ' */' . PHP_EOL;
							if (!$setting['pagespeed_css_min_url']) {
								$file = $this->realUrlCSS($file, $setting['server'], $href);
								foreach ($file['styles'] as $style) {
									$css['styles'][] = $style;
								}
								$css['content'] .= $file['content'];
							} else {
								$css['content'] .= $file;
							}
						}
					}

					if ($css['name'] && $css['content']) {
						$name_md = md5($css['name']) . '.css';
						$file = DIR_IMAGE . 'bus_cache/' . $name_md;
						if (!\is_file($file) || $setting['debug'] == 2) {
							if (!is_dir(DIR_IMAGE . 'bus_cache/')) {
								mkdir(DIR_IMAGE . 'bus_cache/', 0755);
							}
							if ($setting['pagespeed_css_min_url']) {
								$real_url = $this->realUrlCSS($css['content'], $setting['server'], $css['name']);
								$css['content'] = $real_url['content'];
								$css['styles'] = $real_url['styles'];
							}
							if ($setting['pagespeed_css_min_display']) {
								$css['content'] = str_replace(array('@font-face{', '@font-face {'), '@font-face{font-display:' . $setting['pagespeed_css_min_display'] . ';', $css['content']);
							}
							if ($setting['pagespeed_css_replace']) {
								$pagespeed_replace = html_entity_decode($setting['pagespeed_css_replace'], ENT_QUOTES, 'UTF-8');
								$pagespeed_replace = explode("\r\n", $pagespeed_replace);
								$pagespeed_replace_before = array();
								$pagespeed_replace_after = array();

								foreach ($pagespeed_replace as $result) {
									if ($result && substr($result, 0, 1) != ';') {
										$result = str_replace(array('[\r]', '[\n]'), array("\r", "\n"), $result);
										$result = explode('|', $result);
										$result[0] = utf8_strtolower($result[0]);
										if ($result[0] == '#' || $result[0] && strpos($setting['route'], $result[0]) !== false || $setting['keyword'] && strpos($result[0], $setting['keyword']) !== false) {
											if (isset($result[1]) && isset($result[2])) {
												$pagespeed_replace_before[] = $result[1];
												$pagespeed_replace_after[] = $result[2];
											}
										}
									}
								}

								if ($pagespeed_replace_before && $pagespeed_replace_after) {
									$css['content'] = str_replace($pagespeed_replace_before, $pagespeed_replace_after, $css['content']);
								}
							}
							if ($setting['pagespeed_css_style'] && \is_file(DIR_TEMPLATE . $setting['config_theme'] . '/stylesheet/bus_cache/bus_cache_replace.css')) {
								$css['content'] .= file_get_contents(DIR_TEMPLATE . $setting['config_theme'] . '/stylesheet/bus_cache/bus_cache_replace.css');
							}
							if ($setting['pagespeed_css_min'] > 1) {
								$css['content'] = $this->minCSS($css['content'], $setting['pagespeed_css_min']);
							}
							if ($css['styles']) {
								file_put_contents($file . '.json', json_encode($css['styles']));
							}
							file_put_contents($file, $css['content']);
						}

						if (!$css['styles'] && \is_file($file . '.json')) {
							$css['styles'] = json_decode(file_get_contents($file . '.json'), true);
						}

						$setting['styles'] = array();
						if (isset($css['styles'])) {
							$css_links = str_replace('&amp;', '&', $setting['pagespeed_css_min_font']);
							$css_links = explode("\r\n", $css_links);

							foreach ($css['styles'] as $result) {
								if (!$result['domain'] && in_array($result['href'], $css_links) && !in_array(';' . $result['href'], $css_links)) {
									$href = strstr($result['href'], '?', true);
									if (!$href) {
										$href = $result['href'];
									}
									$extension = pathinfo($href, PATHINFO_EXTENSION);
									if (isset($this->fileType[$extension]['as'])) {
										$key = md5($result['href']);
										$setting['styles'][$key . 1] = array(
											'href'      => $result['href'],
											'attribute' => 'type="' . $this->fileType[$extension]['type'] . '" ' . 'rel="preload" ' . 'as="' . $this->fileType[$extension]['as'] . '" ' . 'crossorigin="anonymous"'
										);
									}
								} elseif ($result['domain']) {
									$key = md5($result['domain']);
									$setting['styles'][$key . 1] = array(
										'href'      => $result['domain'],
										'attribute' => ' rel="preconnect" crossorigin="anonymous"'
									);
									$setting['styles'][$key . 2] = array(
										'href'      => $result['domain'],
										'attribute' => 'rel="dns-prefetch"'
									);
								}
							}

							foreach ($css_links as $href) {
								if ($href && substr($href, 0, 1) != ';') {
									$href2 = strstr($href, '?', true);
									if (!$href2) {
										$href2 = $href;
									}
									$extension = pathinfo($href2, PATHINFO_EXTENSION);
									if (isset($this->fileType[$extension]['as'])) {
										$key = md5($href);
										$setting['styles'][$key . 1] = array(
											'href'      => $href,
											'attribute' => 'type="' . $this->fileType[$extension]['type'] . '" ' . 'rel="preload" ' . 'as="' . $this->fileType[$extension]['as'] . '" ' . 'crossorigin="anonymous"'
										);
									} else {
										if ($setting['server'] && strpos($href, $setting['server']) === false) {
											$domain = parse_url($href, PHP_URL_HOST);
											if ($domain) {
												$domain = 'https://' . $domain . '/';
												$key = md5($domain);
												$setting['styles'][$key . 1] = array(
													'href'      => $domain,
													'attribute' => ' rel="preconnect" crossorigin="anonymous"'
												);
												$setting['styles'][$key . 2] = array(
													'href'      => $domain,
													'attribute' => 'rel="dns-prefetch"'
												);
											}
										}
									}
								}
							}
						}
						$setting['styles'][] = array(
							'href'      => $setting['server'] . 'image/bus_cache/' . $name_md . '?time=' . $setting['time_save'],
							'attribute' => 'type="text/css" rel="stylesheet preload" media="screen" as="style"'
						);

						foreach ($setting['styles'] as $style) {
							$this->outputTransfer['css'][1] .= '<link href="' . $style['href'] . '" ' . $style['attribute'] . ' />' . PHP_EOL;
						}
					}

					if ($setting['debug']) {
						$this->setDebugSpeed(array('styles_time' => round(microtime(true) - $this->getDebugTime, 3)));
					}
				}

				// Добавляем свои стили
				if (empty($name_md) && $setting['pagespeed_css_style'] && $setting['config_theme'] && \is_file(DIR_TEMPLATE . $setting['config_theme'] . '/stylesheet/bus_cache/bus_cache_replace.css')) {
					$this->outputTransfer['css'][1] .= '<link href="' . $setting['server'] . 'catalog/view/theme/' . $setting['config_theme'] . '/stylesheet/bus_cache/bus_cache_replace.css?time=' . $setting['time_save'] . '" type="text/css" rel="stylesheet preload" media="screen" as="style" />' . PHP_EOL;
				}

				// сжимаем скрипты
				if ($setting['pagespeed_js_min']) {
					if ($setting['debug']) {
						$this->getDebugTime = microtime(true);
					}

					$scripts = $setting['scripts'];
					$scripts_replace = '';
					$setting['scripts'] = array();
					$setting['scripts_after'] = array();

					if ($setting['pagespeed_js_min_links']) {
						$js_links = str_replace('&amp;', '&', $setting['pagespeed_js_min_links']);
						$js_links = explode("\r\n", $js_links);
						foreach ($js_links as $script) {
							if ($script && substr($script, 0, 1) != ';') {
								$sc = explode('|', $script);
								if (strpos($output, $sc[0]) === false) {
									continue;
								}
								$script = array();
								$script['href'] = $sc[0];
								$script['attribute'] = 'type="text/css" rel="preload" media="screen" as="style"';
								if (isset($sc[1])) {
									$script['attribute'] = $sc[1];
									unset($sc[1]);
								}

								$scripts_replace .= ($scripts_replace ? '|' : false) . preg_quote($script['href'], '~');
								if (in_array($script['href'] . '|after', $js_links)) {
									$setting['scripts_after'][] = array(
										'href'      => $script['href'],
										'attribute' => 'type="text/javascript" '
									);
								} else {
									$setting['scripts'][] = array(
										'href'      => $script['href'],
										'attribute' => 'type="text/javascript" '
									);
								}
							}
						}
					}

					if ($scripts) {
						$js_links = str_replace('&amp;', '&', $setting['pagespeed_js_min_exception']);
						$js_links = explode("\r\n", $js_links);
						foreach ($scripts as $script) {
							$href = strstr($script, '.js', true);
							if (!$href) {
								$href = strstr($script, '?', true);
								if (!$href) {
									$href = $script;
								}
							} else {
								$href .= '.js';
							}
							if (!in_array($href, $js_links) || in_array(';' . $href, $js_links)) {
								$scripts_replace .= ($scripts_replace ? '|' : false) . preg_quote($script, '~');
								if (in_array($href . '|after', $js_links) || in_array($script . '|after', $js_links)) {
									$setting['scripts_after'][] = array(
										'href'      => $href,
										'attribute' => 'type="text/javascript" '
									);
								} else {
									$setting['scripts'][] = array(
										'href'      => $href,
										'attribute' => 'type="text/javascript" '
									);
								}
							}
						}
					}

					if ($scripts_replace) {
						$output = preg_replace('~<link(.[^>]*?)href="(' . $scripts_replace . ')([^>]*?)>~i', '', $output);
						$output = preg_replace('~<script(.[^>]*?)src="(' . $scripts_replace . ')([^>]*?)></script>~i', '', $output);
					}
				}

				if ($setting['scripts'] && is_array($setting['scripts']) && $setting['pagespeed_js_min']) {
					$dir = str_replace(basename(DIR_APPLICATION) . '/', '', DIR_APPLICATION);
					$js = array(
						'name' => '',
						'content' => '',
						'styles' => array(),
					);

					foreach ($setting['scripts'] as $script)  {
						$her = (strpos($script['href'], '//') !== false);
						if (!$her || $her && $setting['HTTP_HOST'] && strpos($script['href'], $setting['HTTP_HOST']) !== false) {
							$href = explode('?', $script['href']);
							$href = $href[0];
							$href = str_replace(array('..', 'https://' . $setting['HTTP_HOST'] . '/', 'http://' . $setting['HTTP_HOST'] . '/', '://' . $setting['HTTP_HOST'] . '/', '//' . $setting['HTTP_HOST'] . '/'), '', $href);
							//$href = parse_url($href, PHP_URL_PATH);
							if (substr($href, 0, 1) == '.') {
								$href = substr($href, 1);
							}
							if (substr($href, 0, 1) == '/') {
								$href = substr($href, 1);
							}
							$file = $dir . $href;
						} else {
							$href = md5($script['href']) . '.js';
							$file = DIR_IMAGE . 'bus_cache/download/' . $href;
							$href = basename(DIR_IMAGE) . '/cache/bus_cache/download/' . $href;
						}
						if (\is_file($file)) {
							$file = file_get_contents($file);
							$js['name'] .= ($js['name'] ? '|' : false) . $href;
							$js['content'] .=  '/* ' . $href . ' */' . PHP_EOL;
							if (1 == 0) {
								$file = $this->realUrlCSS($file, $setting['server'], $href);
								foreach ($file['styles'] as $style) {
									$js['styles'][] = $style;
								}
								$js['content'] .= $file['content'];
							} else {
								$js['content'] .= $file;
							}
						}
					}

					if ($js['name'] && $js['content']) {
						$js['name_md'] = md5($js['name']) . '.js';
						$file = DIR_IMAGE . 'bus_cache/' . $js['name_md'];
						if (!\is_file($file) || $setting['debug'] == 2) {
							if (!is_dir(DIR_IMAGE . 'bus_cache/')) {
								mkdir(DIR_IMAGE . 'bus_cache/', 0755);
							}
							if (1 == 0) {
								$real_url = $this->realUrlCSS($js['content'], $setting['server'], $js['name']);
								$js['content'] = $real_url['content'];
								$js['styles'] = $real_url['styles'];
							}
							if ($setting['pagespeed_js_replace']) {
								$pagespeed_replace = html_entity_decode($setting['pagespeed_js_replace'], ENT_QUOTES, 'UTF-8');
								$pagespeed_replace = explode("\r\n", $pagespeed_replace);
								$pagespeed_replace_before = array();
								$pagespeed_replace_after = array();

								foreach ($pagespeed_replace as $result) {
									if ($result && substr($result, 0, 1) != ';') {
										$result = str_replace(array('[\r]', '[\n]'), array("\r", "\n"), $result);
										$result = explode('|', $result);
										$result[0] = utf8_strtolower($result[0]);
										if ($result[0] == '#' || $result[0] && strpos($setting['route'], $result[0]) !== false || $setting['keyword'] && strpos($result[0], $setting['keyword']) !== false) {
											if (isset($result[1]) && isset($result[2])) {
												$pagespeed_replace_before[] = $result[1];
												$pagespeed_replace_after[] = $result[2];
											}
										}
									}
								}

								if ($pagespeed_replace_before && $pagespeed_replace_after) {
									$js['content'] = str_replace($pagespeed_replace_before, $pagespeed_replace_after, $js['content']);
								}
							}
							if ($setting['pagespeed_js_script'] && \is_file(DIR_TEMPLATE . $setting['config_theme'] . '/javascript/bus_cache/bus_cache_replace.js')) {
								$js['content'] .= file_get_contents(DIR_TEMPLATE . $setting['config_theme'] . '/javascript/bus_cache/bus_cache_replace.js');
							}
							if ($setting['pagespeed_js_min'] > 1) {
								$js['content'] = $this->minJS($js['content'], $setting['pagespeed_js_min']);
							}
							file_put_contents($file, $js['content']);
						}

						$setting['scripts_preload'] = array();
						$setting['scripts_preload'][] = array(
							'href'      => $setting['server'] . 'image/bus_cache/' . $js['name_md'] . '?time=' . $setting['time_save'],
							'attribute' => 'rel="preload" as="script" '
						);

						$this->outputTransfer['css'][1] .= '<link href="' . $setting['scripts_preload'][0]['href'] . '" ' . $setting['scripts_preload'][0]['attribute'] . ' />' . PHP_EOL;

						$setting['scripts'] = array();
						$setting['scripts'][] = array(
							'href'      => $setting['server'] . 'image/bus_cache/' . $js['name_md'] . '?time=' . $setting['time_save'],
							'attribute' => 'type="text/javascript"'
						);

						foreach ($setting['scripts'] as $script) {
							$this->outputTransfer['js'][1] .= '<script src="' . $script['href'] . '" ' . $script['attribute'] . '></script>' . PHP_EOL;
						}
					}

					if ($setting['debug']) {
						$this->setDebugSpeed(array('scripts_time' => round(microtime(true) - $this->getDebugTime, 3)));
					}
				}

				// Добавляем свои скрипты
				if (empty($js['name_md']) && $setting['pagespeed_js_script'] && $setting['config_theme'] && \is_file(DIR_TEMPLATE . $setting['config_theme'] . '/javascript/bus_cache/bus_cache_replace.js')) {
					$this->outputTransfer['css'][1] .= '<link href="' . $setting['server'] . 'catalog/view/theme/' . $setting['config_theme'] . '/javascript/bus_cache/bus_cache_replace.js?time=' . $setting['time_save'] . '" rel="preload" as="script" />' . PHP_EOL;
					$this->outputTransfer['js'][1] .= '<script src="' . $setting['server'] . 'catalog/view/theme/' . $setting['config_theme'] . '/javascript/bus_cache/bus_cache_replace.js?time=' . $setting['time_save'] . '" type="text/javascript"></script>' . PHP_EOL;
				}

				// откладываем загрузку скриптов и стилей
				$html = "var busCache = {
	'timeinterval':false,
	'status':false,
	'start':function() {
		if (busCache.status == false) {
			busCache.status = true;";

			if (1 == 0) {
			$css_links = str_replace('&amp;', '&', $setting['pagespeed_css_events']);
			$css_links = explode("\r\n", $css_links);
			foreach ($css_links as $result) {
				if ($result && substr($result, 0, 1) != ';') {
					$html .= PHP_EOL . "			window.removeEventListener('" . $result . "', busCache.start, {once:true, passive:true});";
				}
			}
			}

			$js_links = str_replace('&amp;', '&', $setting['pagespeed_js_events']);
			$js_links = explode("\r\n", $js_links);
			foreach ($js_links as $result) {
				if ($result && substr($result, 0, 1) != ';') {
					$html .= PHP_EOL . "			window.removeEventListener('" . $result . "', busCache.start, {once:true, passive:true});";
				}
			}

			$html .= PHP_EOL . "
		} else {
			console.log('bus_cache уже работает!');
			return 'bus_cache уже работает!';
		}

		if (typeof window.CustomEvent !== 'function') {
			window.CustomEvent = function(event, params) {
				params = params || {bubbles:false, cancelable:false, detail:null};

				var evt = document.createEvent('CustomEvent');
				evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);
				return evt;
			};
		}

		document.dispatchEvent(new CustomEvent('busCache', {bubbles: true}));
	}
};
";

				$html .= PHP_EOL . "
window.addEventListener('DOMContentLoaded', function() {
";
				if (1 == 0) {
				foreach ($css_links as $result) {
					if ($result && substr($result, 0, 1) != ';') {
						$html .= PHP_EOL . "	window.addEventListener('" . $result . "', busCache.start, {once:true, passive:true});";
					}
				}
				}
				foreach ($js_links as $result) {
					if ($result && substr($result, 0, 1) != ';') {
						$html .= PHP_EOL . "	window.addEventListener('" . $result . "', busCache.start, {once:true, passive:true});";
					}
				}

				if ($setting['pagespeed_js_inline_event_time']) {
					$html .= PHP_EOL . "	setTimeout(busCache.start, " . (int)$setting['pagespeed_js_inline_event_time'] . ");";
				}

				$html .= PHP_EOL . "
}, {once:true, passive:true});
";

				if ($setting['scripts_after'] || $setting['styles_after']) {
					$html .= PHP_EOL . "
window.addEventListener('busCache', function() {
	var s, ss;
";

					foreach ($setting['styles_after'] as $script) {
						$html .= "
	s = document.createElement('link');
	s.type = 'text/css';
	s.href = '" . $script['href'] . "';
	s.rel = 'stylesheet';
	ss = document.getElementsByTagName('head')[0];
	ss.parentNode.appendChild(s);";
					}

					foreach ($setting['scripts_after'] as $script) {
						$html .= "
	s = document.createElement('script');
	s.async = true;
	s.type = 'text/javascript';
	s.src = '" . $script['href'] . "';
	ss = document.getElementsByTagName('head')[0];
	ss.parentNode.appendChild(s);
";
					}

					$html .= PHP_EOL . "});";
				}

				if ($setting['pagespeed_js_inline_transfer'] == 3 || $setting['pagespeed_js_inline_transfer'] == 4) {
					$this->outputTransfer['js_inline'][$setting['pagespeed_js_inline_transfer']] .= $html;
				} else {
					$this->outputTransfer['js_inline'][1] .= '<script type="text/javascript">' .  PHP_EOL . $html .  PHP_EOL . '</script>';
				}

				// critical
				if ($setting['pagespeed_css_min'] && $setting['pagespeed_css_critical'] && !empty($name_md)) {
					if (\is_file(DIR_IMAGE . 'bus_cache/' . 'critical_' . $name_md)) {
						$setting['pagespeed_css_critical_name'] = 'critical_' . $name_md;
						$this->outputTransfer['css'][1] = str_replace('/' . $name_md, '/' . $setting['pagespeed_css_critical_name'], $this->outputTransfer['css'][1]);
					} elseif (!\is_file(DIR_IMAGE . 'bus_cache/' . 'critical_' . $name_md) && empty($this->request->post['bus_critical_name'])) {
						$html = PHP_EOL . "<script src=\"catalog/view/theme/default/javascript/bus_cache/bus_ajax.js\" type=\"text/javascript\" async ></script>";
						$html .= PHP_EOL . "<script src=\"catalog/view/theme/default/javascript/bus_cache/bus_critical.js\" type=\"text/javascript\" async ></script>";
						$html .= PHP_EOL . "<script type=\"text/javascript\">";
						$html .= PHP_EOL . "window.addEventListener('busCritical', function() {";
						if ($setting['pagespeed_css_critical_all']) {
							$html .= PHP_EOL . "	window.busCritical.setting['html_all'] = true;";
						}
						if ($setting['pagespeed_css_critical_elements']) {
							$js_links = str_replace('&amp;', '&', $setting['pagespeed_css_critical_elements']);
							$js_links = json_encode(explode("\r\n", $js_links));
							$html .= PHP_EOL . "	window.busCritical.setting['html_elements'] = " . $js_links . ";";
						}

$html .= PHP_EOL . "	setTimeout(function() {
		var start = new Date().getTime();
		var critical = window.busCritical.critical('body');

		if (critical) {
			var myAjax = function() {
				window.busAjax(window.location.href, {
					metod: 'post',
					data: {bus_critical:critical,bus_critical_name:'$name_md'},
					success: function(data) {
						//console.log(data);
					}
				});
			};

			if ('busAjax' in window) {
				myAjax();
			} else {
				window.addEventListener('busAjax', myAjax);
			}
		}

		var end = new Date().getTime();
		console.log('Обработка ' + (end - start)/1000 + ' сек. или ' + (end - start) + ' мс.', critical);
	}, " . $setting['pagespeed_css_critical_time'] . ");
});";

						$html .= PHP_EOL . "</script>";

						$this->outputTransfer['js_inline'][1] .= $html;
						$setting['cache_status'] = false;
					}
				}

				// проталкиваем скрипты и стили для server-push
				if (!empty($name_md) && !empty($js['name_md'])) {
					$setting['headers'][] = 'Link: <' . $setting['server'] . 'image/bus_cache/' . ($setting['pagespeed_css_critical_name'] ? $setting['pagespeed_css_critical_name'] : $name_md) . '?time=' . $setting['time_save'] . '>; rel=preload; as=style' . (!empty($js['name_md']) ? ', <' . $setting['server'] . 'image/bus_cache/' . $js['name_md'] . '?time=' . $setting['time_save'] . '>; rel=preload; as=script' : false);
				}

				// замена на странице
				if ($setting['pagespeed_html_replace']) {
					if ($setting['debug']) {
						$this->getDebugTime = microtime(true);
					}

					$pagespeed_replace = html_entity_decode($setting['pagespeed_html_replace'], ENT_QUOTES, 'UTF-8');
					$pagespeed_replace = explode("\r\n", $pagespeed_replace);
					$pagespeed_replace_before = array();
					$pagespeed_replace_after = array();

					foreach ($pagespeed_replace as $result) {
						if ($result && substr($result, 0, 1) != ';') {
							$result = str_replace(array('[\r]', '[\n]'), array("\r", "\n"), $result);
							$result = explode('|', $result);
							$result[0] = utf8_strtolower($result[0]);
							if ($result[0] == '#' || $result[0] && strpos($setting['route'], $result[0]) !== false || $setting['keyword'] && strpos($result[0], $setting['keyword']) !== false) {
								if (isset($result[1]) && isset($result[2])) {
									$pagespeed_replace_before[] = $result[1];
									$pagespeed_replace_after[] = $result[2];
								}
							}
						}
					}

					if ($pagespeed_replace_before && $pagespeed_replace_after) {
						/* if ($this->outputTransfer['css_inline'][3]) {
							$this->outputTransfer['css_inline'][3] = str_replace($pagespeed_replace_before, $pagespeed_replace_after, $this->outputTransfer['css_inline'][3]);
						}
						if ($this->outputTransfer['js_inline'][3]) {
							$this->outputTransfer['js_inline'][3] = str_replace($pagespeed_replace_before, $pagespeed_replace_after, $this->outputTransfer['js_inline'][3]);
						} */
						$output = str_replace($pagespeed_replace_before, $pagespeed_replace_after, $output);
					}

					if ($setting['debug']) {
						$this->setDebugSpeed(array('name' => 'Время обработки замены в html коде', 'time' => round(microtime(true) - $this->getDebugTime, 3)));
					}
				}

				// Обработка inline кода
				if ($setting['debug']) {
					$this->getDebugTime = microtime(true);
				}

				$inline = array(
					'route'                    => $setting['route'],
					'keyword'                  => $setting['keyword'],

					'css_inline_transfer'      => $setting['pagespeed_css_inline_transfer'],
					'js_inline_event'          => array(),
					'js_inline_transfer'       => $setting['pagespeed_js_inline_transfer'],
					'js_inline_transfer_onrot' => array(),
					'js_inline_exception'      => array(),
				);

				if ($setting['pagespeed_js_inline_event']) {
					$inline['js_inline_event'] = str_replace('&amp;', '&', html_entity_decode($setting['pagespeed_js_inline_event'], ENT_QUOTES, 'UTF-8'));
					$inline['js_inline_event'] = explode("\r\n", $inline['js_inline_event']);
				}

				if ($setting['pagespeed_js_inline_transfer_onrot']) {
					$inline['js_inline_transfer_onrot'] = str_replace('&amp;', '&', html_entity_decode($setting['pagespeed_js_inline_transfer_onrot'], ENT_QUOTES, 'UTF-8'));
					$inline['js_inline_transfer_onrot'] = explode("\r\n", $inline['js_inline_transfer_onrot']);
					$inline['js_inline_exception'] = $inline['js_inline_transfer_onrot'];
				} elseif (!$setting['pagespeed_js_inline_transfer_onrot'] && $setting['pagespeed_js_inline_exception']) {
					$inline['js_inline_exception'] = str_replace('&amp;', '&', html_entity_decode($setting['pagespeed_js_inline_exception'], ENT_QUOTES, 'UTF-8'));
					$inline['js_inline_exception'] = explode("\r\n", $inline['js_inline_exception']);
				}

				// перемещаем inline стили
				if ($inline['css_inline_transfer']) {
					$output = preg_replace_callback('~(?:<!--[\s]*){0,}(?:<noscript>[\s]*){0,}<style([^>]*?){0,}>(.*?)</style>(?:[\s]*</noscript>){0,}(?:[\s]*-->){0,}~iSsu', function ($matches) use ($inline) {
						if ($inline['css_inline_transfer'] && substr($matches[0], 0, 6) == '<style') {
							if ($inline['css_inline_transfer'] == 3) {
								$matches[0] = preg_replace('~<style([^>]*?){0,}>(.*?)</style>~is', '$2', $matches[0]);;
							}

							$this->outputTransfer['css_inline'][$inline['css_inline_transfer']] .= $matches[0];
							return;
						}

						return $matches[0];
					}, $output);
				}

				// перемещаем inline скрипты
				if ($inline['js_inline_transfer'] || $inline['js_inline_event']) {
					/* $output = preg_replace_callback('~<script(.[^>]*?)?>(.*?)</script>~is', function ($matches) use ($inline) { */
					$output = preg_replace_callback('~(?:<!--[\s]*){0,}<script([^>]*?){0,}>(.*?)</script>(?:[\s]*-->){0,}~iSsu', function ($matches) use ($inline) {
						$js_inline_event = '';
						$js_inline_exception = ($inline['js_inline_transfer_onrot'] ? true : false);

						foreach ($inline['js_inline_event'] as $result) {
							if ($result && substr($result, 0, 1) != ';' && stripos($matches[0], substr(strstr($result, '|'), 1)) !== false) {
								$result = explode('|', $result);
								$result[0] = utf8_strtolower($result[0]);
								if ($result[0] == '#' || $result[0] && strpos($inline['route'], $result[0]) !== false || $inline['keyword'] && strpos($result[0], $inline['keyword']) !== false) {
									$js_inline_event = $result[1];
								}
							}
						}

						foreach ($inline['js_inline_exception'] as $result) {
							if ($result && substr($result, 0, 1) != ';' && stripos($matches[0], substr(strstr($result, '|'), 1)) !== false) {
								$result = explode('|', $result);
								$result[0] = utf8_strtolower($result[0]);
								if ($result[0] == '#' || $result[0] && strpos($inline['route'], $result[0]) !== false || $inline['keyword'] && strpos($result[0], $inline['keyword']) !== false) {
									$js_inline_exception = ($inline['js_inline_transfer_onrot'] ? false : true);
								}
							}
						}

						// откладываем по событию
						if ($js_inline_event) {
							$matches[0] = preg_replace('~(<script[^>]*?>)(?:[\s\r\n]*<!--|<!--){0,}(.*?)(?=' . preg_quote($js_inline_event, '~') . ')(.*?)(?://-->[\s\r\n]*|-->[\s\r\n]*|//-->|-->){0,}(</script>)~is', '$1' . PHP_EOL . 'window.addEventListener(\'busCache\', function() {$2$3});' . PHP_EOL . '$4', $matches[0]);
						}

						// перемещаем inline
						if ($inline['js_inline_transfer'] && !$js_inline_exception && substr($matches[0], 0, 7) == '<script' && !preg_match('~<script([^>]*?)src=([^>]*?)>~i', $matches[0])) {
							if ($inline['js_inline_transfer'] == 3 || $inline['js_inline_transfer'] == 4) {
								$matches[0] = preg_replace('~(<script[^>]*?>)(?:[\s\r\n]*<!--|<!--){0,}(.*?)(?://-->[\s\r\n]*|-->[\s\r\n]*|//-->|-->){0,}(</script>)~is', '$2', $matches[0]);
							}

							$this->outputTransfer['js_inline'][$inline['js_inline_transfer']] .= $matches[0];
							return;
						}

						return $matches[0];
					}, $output);
				}

				if ($setting['debug']) {
					$this->setDebugSpeed(array('name' => 'Время обработки css и js inline кода', 'time' => round(microtime(true) - $this->getDebugTime, 3)));
				}

				// устанавливаем код
				if ($setting['debug']) {
					$this->getDebugTime = microtime(true);
				}

				foreach ($this->outputTransfer as $key => $result) {
					if ($this->outputTransfer[$key][1]) {
						if ($key == 'js_inline') {
							$output = str_replace('</head>', $this->outputTransfer[$key][1] . PHP_EOL . '</head>', $output);
						} else {
							$output = str_replace('<base', $this->outputTransfer[$key][1] . PHP_EOL . '<base', $output);
						}
					}
					if ($this->outputTransfer[$key][2]) {
						$output = str_replace('</body>', $this->outputTransfer[$key][2] . PHP_EOL . '</body>', $output);
					}
					if ($this->outputTransfer[$key][4]) {
						$this->outputTransfer[$key][3] = $this->outputTransfer[$key][4];
					}
					if ($this->outputTransfer[$key][3] && $id = mb_strlen($this->outputTransfer[$key][3])) {
						$file = '';
						$href = '';
						if ($key == 'css_inline') {
							if ($setting['pagespeed_css_min'] > 1) {
								$this->outputTransfer[$key][3] = $this->minCSS($this->outputTransfer[$key][3], $setting['pagespeed_css_min']);
							}
							$id = mb_strlen($this->outputTransfer[$key][3]);
							if ($id) {
								$file = DIR_IMAGE . 'bus_cache/bus_cache_inline_' . $id . '.css';
								$href = '<link href="' . $setting['server'] . 'image/bus_cache/bus_cache_inline_' . $id . '.css?time=' . $setting['time_save'] . '" type="text/css" rel="stylesheet preload" media="screen" as="style" />' . PHP_EOL;
							}
						} elseif ($key == 'js_inline') {
							if ($setting['pagespeed_js_min'] > 1) {
								$this->outputTransfer[$key][3] = $this->minJS($this->outputTransfer[$key][3], $setting['pagespeed_js_min']);
							}
							$id = mb_strlen($this->outputTransfer[$key][3]);
							if ($id) {
								$file = DIR_IMAGE . 'bus_cache/bus_cache_inline_' . $id . '.js';
								$href = '';
								$href .= '<link href="' . $setting['server'] . 'image/bus_cache/bus_cache_inline_' . $id . '.js?time=' . $setting['time_save'] . '" rel="preload" media="screen" as="script" />' . PHP_EOL;
								$href .= '<script src="' . $setting['server'] . 'image/bus_cache/bus_cache_inline_' . $id . '.js?time=' . $setting['time_save'] . '" type="text/javascript"></script>' . PHP_EOL;
							}
						}

						if ($this->outputTransfer[$key][3]) {
							if ($this->outputTransfer[$key][4]) {
								$output = str_replace('</body>', $href . PHP_EOL . '</body>', $output);
							} else {
								$output = str_replace('<base', $href . PHP_EOL . '<base', $output);
							}
							if (!\is_file($file)) {
								file_put_contents($file, $this->outputTransfer[$key][3]);
							}
						}
					}
				}

				if ($setting['debug']) {
					$this->setDebugSpeed(array('name' => 'Время перемещения кода', 'time' => round(microtime(true) - $this->getDebugTime, 3)));
				}

				// сжимаем страницу
				if ($setting['pagespeed_html_min']) {
					if ($setting['debug']) {
						$this->getDebugTime = microtime(true);
					}

					$output = $this->minHTML($output, $setting['pagespeed_html_min']);

					if ($setting['debug']) {
						$this->setDebugSpeed(array('html_time' => round(microtime(true) - $this->getDebugTime, 3)));
					}
				}
			}

			if ($setting['debug']) {
				if ($setting['getDebugSpeed']) {
					$this->getDebugSpeed .= $setting['getDebugSpeed'];
				}

				if (isset($setting['cache_time'])) {
					$this->setDebugSpeed(array('cache_time' => round(microtime(true) - $cache_time + $setting['cache_time'], 3)));
				}

				if (isset($setting['cache_timer'])) {
					$this->setDebugSpeed(array('cache_timer' => round(microtime(true) - $setting['cache_timer'], 3)));
				}
			}

			if ($setting['cache_status']) {
				$cache_data = array(
					'headers'                     => $setting['headers'],
					'output'                      => $output,
					'debug_times'                 => $this->getDebugSpeed,
					'pagespeed_css_critical_name' => $setting['pagespeed_css_critical_name']
				);

				if ($setting['cache_engine'] == 'buslik') {
					file_put_contents(DIR_CACHE . $setting['cache_name'] . '.' . (time() + $setting['cache_expire_all']), json_encode($cache_data));
				} else {
					(new \Cache('Bus_Cache\\' . $setting['cache_engine'], $setting['cache_expire_all']))->set($setting['cache_name'], $cache_data);
				}
			}

			// debug режим
			if ($setting['debug']) {
				$output = str_ireplace('<body', $this->getDebugSpeed . '<body', $output);
			}
		}

		return $output;
	}

	public function controller($action, $route = '', $args = array()) {
		$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		$id = md5($route);
		if ($this->setting_default['debug'] == 3) {
			if (empty($this->setting_default['cache_controller'][$id]) && method_exists($action, 'execute')) {
				//$action->{$method} = function($args) use ($action, $route, $result, $args) {
					$time = microtime(true);
					echo 'Controller (не кэшируется): ' . $route . '|' . $this->setting_default['cache_expire_model'] . '<br>' . PHP_EOL;
					if (version_compare(VERSION, '2.1.0.0', '<')) {
						$output = $action->execute($this->registry);
					} elseif (version_compare(VERSION, '2.2.0.0', '<')) {
						if (is_file(DIR_APPLICATION . 'controller/' . $route . '.php')) {
							$method = 'index';
						} else {
							$method = substr($route, strrpos($route, '/') + 1);
						}
						$output = call_user_func_array(array($action, $method), array($args));
					} else {
						$output = $action->execute($this->registry, $args);
					}
					$time = round(microtime(true) - $time, 3);
					echo 'Время выполнения ' . $route . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . PHP_EOL;
					//echo 'Время выполнения ' . $route . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . json_encode($args) . '<br>' . PHP_EOL;
				//};
			}
		}
		if ($this->setting_default['cache_controller'] && $route) {
			if (!empty($this->setting_default['cache_controller'][$id]) && method_exists($action, 'execute')) {
				$result = $this->setting_default['cache_controller'][$id];
				if ($this->setting_default['cache_status'] == 2) {
					$this->response->setBuslikCache(array('cache_status' => false));
				}
				//$action->{'execute'} = function() use ($action, $route, $result, $args) {
					if ($this->setting_default['debug'] == 3) {
						$time = microtime(true);
						echo 'Controller (кэшируется): ' . $route . '|' . $result['expire'] . '<br>' . PHP_EOL;
					}
					$name = 'bus_cache_controller.' . md5(http_build_query(array($route, $args, $result['config'], $result['session'], $result['params'])));
					$cache = new \Cache('Bus_Cache\\' . $this->setting_default['cache_engine'], $result['expire']);
					$output = $cache->get($name);

					if (!$output) {
						if (version_compare(VERSION, '2.1.0.0', '<')) {
							$output = $action->execute($this->registry);
						} elseif (version_compare(VERSION, '2.2.0.0', '<')) {
							if (is_file(DIR_APPLICATION . 'controller/' . $route . '.php')) {
								$method = 'index';
							} else {
								$method = substr($route, strrpos($route, '/') + 1);
							}
							$output = call_user_func_array(array($action, $method), array($args));
						} else {
							$output = $action->execute($this->registry, $args);
						}

						if ($output) {
							if (is_array($args)) {
								$her = $args;
							} else {
								$her = array($args);
							}

							$module_id = array();
							if (!empty($her['module_id'])) {
								$module_id['module_id'] = $her['module_id'];
							} elseif (!empty($her[0]['module_id'])) {
								$module_id['module_id'] = $her[0]['module_id'];
							}
							if (!empty($her['name'])) {
								$module_id['name'] = $her['name'];
							} elseif (!empty($her[0]['name'])) {
								$module_id['name'] = $her[0]['name'];
							}
							$module_id = md5($route . http_build_query($module_id));

							if (isset($this->setting_default['pagespeed_lazy_load_html'][$module_id])) {
								$output = '<div loading="lazy" data-busloadinglazy-id="' . $module_id . '"' . ($this->setting_default['pagespeed_lazy_load_html'][$module_id]['res'] ? ' data-busloadinglazy-res="' . $this->setting_default['pagespeed_lazy_load_html'][$module_id]['res'] . '"' : false) . '><busloadinglazy>' . $output . '</busloadinglazy></div>';
							}
							if ($this->setting_default['debug'] == 5) {
								$output = '<div>data-busloadinglazy-id: ' . $module_id . ' (' . $route . ')</div><br />' . $output;
							}

							$styles = $this->registry->get('document')->getStyles();
							$scripts = array();
							foreach (array('header', 'footer') as $position) {
								$scripts[$position] = $this->registry->get('document')->getScripts($position);
							}
							$cache->set($name, array(
								'output'  => $output,
								'styles'  => $styles,
								'scripts' => $scripts,
							));
						}
					} elseif (!empty($output['output'])) {
						if (isset($output['styles'])) {
							foreach ($output['styles'] as $result) {
								$this->registry->get('document')->addStyle($result['href'], $result['rel'], $result['media']);
							}
						}
						if (isset($output['scripts'])) {
							foreach ($output['scripts'] as $position => $result) {
								foreach ($result as $key => $res) {
									$this->registry->get('document')->addScript($key, $position);
								}
							}
						}
						$output = $output['output'];
					}

					if ($this->setting_default['debug'] == 3) {
						$time = round(microtime(true) - $time, 3);
						echo 'Время выполнения ' . $route . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . PHP_EOL;
						//echo 'Время выполнения ' . $route . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . json_encode($args) . '<br>' . PHP_EOL;
					}
				//};
			}
		}

		if (empty($output)) {
			if (version_compare(VERSION, '2.1.0.0', '<')) {
				$output = $action->execute($this->registry);
			} elseif (version_compare(VERSION, '2.2.0.0', '<')) {
				if (is_file(DIR_APPLICATION . 'controller/' . $route . '.php')) {
					$method = 'index';
				} else {
					$method = substr($route, strrpos($route, '/') + 1);
				}
				$output = call_user_func_array(array($action, $method), array($args));
			} else {
				$output = $action->execute($this->registry, $args);
			}

			if ($output) {
				if (is_array($args)) {
					$her = $args;
				} else {
					$her = array($args);
				}

				$module_id = array();
				if (!empty($her['module_id'])) {
					$module_id['module_id'] = $her['module_id'];
				} elseif (!empty($her[0]['module_id'])) {
					$module_id['module_id'] = $her[0]['module_id'];
				}
				if (!empty($her['name'])) {
					$module_id['name'] = $her['name'];
				} elseif (!empty($her[0]['name'])) {
					$module_id['name'] = $her[0]['name'];
				}
				$module_id = md5($route . http_build_query($module_id));

				if (isset($this->setting_default['pagespeed_lazy_load_html'][$module_id])) {
					$output = '<div loading="lazy" data-busloadinglazy-id="' . $module_id . '"' . ($this->setting_default['pagespeed_lazy_load_html'][$module_id]['res'] ? ' data-busloadinglazy-res="' . $this->setting_default['pagespeed_lazy_load_html'][$module_id]['res'] . '"' : false) . '><busloadinglazy>' . $output . '</busloadinglazy></div>';
				}
				if ($this->setting_default['debug'] == 5) {
					$output = '<div>data-busloadinglazy-id: ' . $module_id . ' (' . $route . ')</div><br />' . $output;
				}
			}
		}

		return $output;
	}

	public function model($proxy, $route = '', $class = '') {
		//$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$route);
		if ($this->setting_default['debug'] == 4 && $route && $class) {
			$id = md5($route);
			foreach (get_class_methods($class) as $method) {
				if (is_callable(array($proxy, $method)) && $proxy->{$method} && (empty($this->setting_default['cache_model'][$id]) || empty($this->setting_default['cache_model'][$id][$method]))) {
					if ($this->setting_default['cache_status'] == 2) {
						$this->response->setBuslikCache(array('cache_status' => false));
					}
					$proxy->{$method} = function($args) use ($proxy, $route, $method, $class) {
						$time = microtime(true);
						echo 'Model (не кэшируется): ' . $route . '/' . $method . '|' . $this->setting_default['cache_expire_model'] . '<br>' . PHP_EOL;
						if (version_compare(VERSION, '2.2.0', '>=')) {
							$proxy = new $class($this->registry);
						}
						$output = call_user_func_array(array($proxy, $method), $args);
						$time = round(microtime(true) - $time, 3);
						echo 'Время выполнения ' . $route . '/' . $method . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . PHP_EOL;
						//echo 'Время выполнения ' . $route . '/' . $method . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . json_encode($args) . '<br>' . PHP_EOL;

						return $output;
					};
				}
			}
			/* foreach (get_object_vars($proxy) as $method => $result) {
				if (is_callable(array($proxy, $method)) && $proxy->{$method} && empty($this->setting_default['cache_model'][$id][$method])) {
					$proxy->{$method} = function($args) use ($proxy, $route, $method, $class) {
						echo 'Model: ' . $route . '/' . $method . '|' . $this->setting_default['cache_expire_model'] . '<br>' . PHP_EOL;
						$time = microtime(true);
						if (version_compare(VERSION, '2.2.0', '>=')) {
							$proxy = new $class($this->registry);
						}
						$output = call_user_func_array(array($proxy, $method), $args);
						$time = round(microtime(true) - $time, 3);
						echo 'Время выполнения ' . $route . '/' . $method . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . PHP_EOL;
						//echo 'Время выполнения ' . $route . '/' . $method . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . json_encode($args) . '<br>' . PHP_EOL;

						return $output;
					};
				}
			} */
		}
		if ($this->setting_default['cache_model'] && $route) {
			$id = md5($route);
			if (!empty($this->setting_default['cache_model'][$id])) {
				foreach ($this->setting_default['cache_model'][$id] as $result) {
					if (is_callable(array($proxy, $result['method'])) && $proxy->{$result['method']}) {
						$proxy->{$result['method']} = function($args) use ($proxy, $route, $result, $class) {
							if ($this->setting_default['debug'] == 4) {
								$time = microtime(true);
								echo 'Model (кэшируется): ' . $route . '/' . $result['method'] . '|' . $result['expire'] . '<br>' . PHP_EOL;
							}
							$name = 'bus_cache_model.' . md5(http_build_query(array($route, $result['method'], $args, $result['config'], $result['session'], $result['params'])));
							$cache = new \Cache('Bus_Cache\\' . $this->setting_default['cache_engine'], $result['expire']);
							$output = $cache->get($name);

							if (!$output) {
								//$output = $proxy->{$result['method']};
								//$output = $proxy->__get($result['method']);
								//$output = $proxy->__call($result['method'], $args);
								if (version_compare(VERSION, '2.2.0', '>=')) {
									$proxy = new $class($this->registry);
								}
								$output = call_user_func_array(array($proxy, $result['method']), $args);
								if ($output) {
									$cache->set($name, $output);
								}
							}

							if ($this->setting_default['debug'] == 4) {
								$time = round(microtime(true) - $time, 3);
								echo 'Время выполнения ' . $route . '/' . $result['method'] . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . PHP_EOL;
								//echo 'Время выполнения ' . $route . '/' . $result['method'] . ': ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>' . json_encode($args) . '<br>' . PHP_EOL;
							}

							return $output;
						};
					}
				}
			}
		}

		return $proxy;
	}

	private function realUrlCSS($content, $server, $css = '') {
		$styles = array();

		if (preg_match_all('/\b(' . str_replace('\|', '|', preg_quote($css, '/')) . '|\burl\(.[^\(\)]*?\))/iS', $content, $matches)) {
			if (!empty($matches[0])) {
				$dir = substr(DIR_APPLICATION, 0, -iconv_strlen(basename(DIR_APPLICATION) . '/'));
				$dir_len = iconv_strlen($dir, 'UTF-8');
				$server_len = iconv_strlen($server, 'UTF-8');
				$css_path = '';
				$do = array();
				$posle = array();

				if ($css && strpos($css, '|') === false) {
					if (strpos($css, '://') === false) {
						$css_path = substr($css, 0, -(int)iconv_strlen(basename($css)));
					} else {
						$css_path = substr(substr($css, 0, -(int)iconv_strlen(basename($css))), $server_len);
					}
				}

				foreach ($matches[0] as $orig) {
					if ($orig && strpos($orig, 'data:') === false) {
						//$result = utf8_strtolower($orig);
						$result = $orig;

						if (stripos($result, 'url(') === false && stripos($result, '.css') !== false) {
							$css = $result;
							if (strpos($css, '://') === false) {
								$css_path = substr($css, 0, -(int)iconv_strlen(basename($css)));
							} else {
								$css_path = substr(substr($css, 0, -(int)iconv_strlen(basename($css))), $server_len);
							}
						} else {
							//$result = preg_replace('/\burl\([\"\'\s]{0,}(.[^\(\)]*?)[\"\'\s]{0,}\)/S', '$1', $result);
							$result = str_replace(array('url(', ')', ' ', '"', "'"), '', $result);
							$domain = '';
							$href = '';
							$hach = '';

							if (strpos($result, '://') === false) {
								$href = strstr($result, '?', true);
								if (!$href) {
									$href = strstr($result, '#', true);
								}
								if (!$href) {
									$href = $result;
								}

								$href = str_replace('\\', '/', realpath($dir . $css_path . $href));

								if (substr($href, 0, $dir_len) == $dir) {
									$hach = strstr($result, '?');
									if (!$hach) {
										$hach = strstr($result, '#');
									}
									$href = $server . substr($href, $dir_len) . $hach;
								} else {
									$href = $server . ltrim(str_replace(array('\\', './', '.../'), array('/', '../', '../'), $result), '/');
								}

								$do[] = $orig;
								$posle[] = 'url(' . $href . ')';
							} else {
								$domain = parse_url($result, PHP_URL_HOST);

								if ($domain && strpos($server, $domain) === false) {
									$domain = parse_url($result, PHP_URL_SCHEME) . '://' . $domain . '/';
								} else {
									$domain = '';
								}
							}

							$styles[] = array(
								'css'       => $css,
								'domain'    => $domain,
								'href'      => $href,
								'hach'      => $hach
							);
						}
					}
				}

				if ($do) {
					$content = str_replace($do, $posle, $content);
				}
			}
		}

		return array(
			'styles'  => $styles,
			'content' => $content
		);
	}

	private function minCSS($output, $level) {
		if ($level > 1) {
			$do = array('if (', ') {', '" + "', "' + '", "\0", "\r", "\x0B");
			$posle = array('if(', '){', '"+"', "'+'", "", "", "");

			if ($level > 2) {
				$do[] = "\r";
				$do[] = "\n";
				$do[] = '	';
				$do[] = '  ';

				$posle[] = '';
				$posle[] = '';
				$posle[] = '';
				$posle[] = ' ';
			}

			$output = str_replace($do, $posle, $output);

			// нестабильное сжатие
			if ($level > 3) {
				$output = preg_replace('/(?:(?:\/\*[^*]*\*+([^\/][^*]*\*+)*\/))/S', '', $output);
			}

			if ($level > 4) {
				//$output = str_replace(array("\n\n\n", "\n\n", "{\n", ",\n", ";\n", "}\n}", "}\n)", "}\nif"), array("\n", "\n", '{', ',', ';', "}}", "})", "}if"), $output);
				//$output = preg_replace('/(?:;(.[\s\h]*?)(\r|\n){1}|}(.[\s\h]*?)})/', '', $output);
			}
		}

		return $output;
	}

	private function minJS($output, $level) {
		if ($level > 1) {
			$do = array('if (', ') {', '" + "', "' + '", "\0", "\r", "\x0B");
			$posle = array('if(', '){', '"+"', "'+'", "", "", "");

			if ($level > 2) {
				$do[] = "\r\n";
				$do[] = "\n\n";
				$do[] = '	';
				$do[] = '  ';

				$posle[] = '';
				$posle[] = "\n";
				$posle[] = '';
				$posle[] = '';
			}

			$output = str_replace($do, $posle, $output);

			// нестабильное сжатие
			if ($level > 3) {
				$output = preg_replace('/(?:(?:(\n|\s){1}\/\/[^\n]*)|(?:\/\*[^*]*\*+([^\/][^*]*\*+)*\/))/S', '', $output);
			}

			if ($level > 4) {
				$output = str_replace(array("\n\n\n", "\n\n", "{\n", ",\n", ";\n", "}\n}", "}\n)", "}\nif"), array("\n", "\n", '{', ',', ';', "}}", "})", "}if"), $output);
				//$output = preg_replace('/(?:;(.[\s\h]*?)(\r|\n){1}|}(.[\s\h]*?)})/', '', $output);
			}
		}

		return $output;
	}

	private function minHTML($output, $level) {
		// нестабильное сжатие
		$output = preg_replace('/(?:(?:(\n|\s){1}\/\/(?!-->)[^\n]*)|(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:[^>]\s|^)<!--(?!<!)[^\[>][\s\S]*?-->)/S', '', $output);

		if ($level > 1) {
			$do = array('if (', ') {', '" + "', "' + '", "\0", "\r", "\x0B");
			$posle = array('if(', '){', '"+"', "'+'", "", "", "");

			if ($level > 2) {
				//$do[] = "\n";
				//$do[] = "><!--";
				//$do[] = "//--><";
				//$do[] = '>  <div';
				//$do[] = '  ';

				//$posle[] = '';
				//$posle[] = "><!--\n";
				//$posle[] = "\n//--><";
				//$posle[] = '> <div';
				//$posle[] = ' ';
			}

			$output = str_replace($do, $posle, $output);
		}

		if ($level > 3) {
			$output = preg_replace('/>[^\>\<\d\w][\t\n\s\h\r]*?</S', '> <', $output);
		}

		if ($level > 4) {
			$output = preg_replace("/[\s]{2,}+/", '', $output);
			$output = preg_replace("/>[\s]{1,2}<div/", '><div', $output);
			$output = preg_replace("/>[\s]{1,2}<\/div/", '></div', $output);
		}

		return $output;
	}

	private function setDebugSpeed($data = array()) {
		$text = '%s: %s сек. или %s мс.<br>';
		$data_default = array(
			'name'               => '',
			'time'               => -1,
			'styles_time'        => -1,
			'scripts_time'       => -1,
			'html_time'          => -1,
			'cache_time'         => -1,
			'cache_timer'        => -1,
			'cache_time_status'  => -1,
			'cache_timer_status' => -1,
			'str_replace'        => array('pattern1' => '', 'pattern2' => ''),
			'str_ireplace'       => array('pattern1' => '', 'pattern2' => ''),
			'preg_replace'       => array('pattern1' => '', 'pattern2' => ''),
			'preg_match_all'     => array('pattern1' => '', 'pattern2' => ''),
			'limit'              => 1000,
			'output'             => ''
		);

		if (is_array($data)) {
			foreach ($data as $key => $result) {
				if (is_array($result)) {
					foreach ($result as $key2 => $result2) {
						if (isset($data_default[$key][$key2])) {
							$data_default[$key][$key2] = $result2;
						}
					}
				} else {
					if (isset($data_default[$key])) {
						$data_default[$key] = $result;
					}
				}
			}
		}

		$data = $data_default;
		$message = '';
		if ($data['name'] && $data['time'] >= 0) {
			$message .= sprintf($text, $data['name'], $data['time'], ($data['time'] * 1000));
		}

		if ($data['styles_time'] >= 0) {
			$message .= 'Время сжатия CSS: ' . $data['styles_time'] . ' сек. или ' . ($data['styles_time'] * 1000) . ' мс.<br>';
		}

		if ($data['scripts_time'] >= 0) {
			$message .= 'Время сжатия JS: ' . $data['scripts_time'] . ' сек. или ' . ($data['scripts_time'] * 1000) . ' мс.<br>';
		}

		if ($data['html_time'] >= 0) {
			$message .= 'Время сжатия HTML: ' . $data['html_time'] . ' сек. или ' . ($data['html_time'] * 1000) . ' мс.<br>';
		}

		if ($data['cache_time'] >= 0) {
			$message .= 'Время работы Буслік Cache без кэша примерно: ' . $data['cache_time'] . ' сек. или ' . ($data['cache_time'] * 1000) . ' мс.<br>';
		}

		if ($data['cache_timer'] >= 0) {
			$message .= 'Время загрузки страницы без кэша примерно: ' . $data['cache_timer'] . ' сек. или ' . ($data['cache_timer'] * 1000) . ' мс.<br>';
		}

		if ($data['cache_time_status'] >= 0) {
			$message .= 'Время работы Буслік Cache с кэшем примерно: ' . $data['cache_time_status'] . ' сек. или ' . ($data['cache_time_status'] * 1000) . ' мс.<br>';
		}

		if ($data['cache_timer_status'] >= 0) {
			$message .= 'Время загрузки страницы с кэшем примерно: ' . $data['cache_timer_status'] . ' сек. или ' . ($data['cache_timer_status'] * 1000) . ' мс.<br>';
		}

		if ($data['str_replace']['pattern1']) {
			$time = microtime(true);
			foreach (range(0, $data['limit']) as $res) {
				str_replace($data['str_replace']['pattern1'], $data['str_replace']['pattern2'], $data['output']);
			}
			$time = round(microtime(true) - $time, 3);
			$message .= 'str_replace: ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>';
		}

		if ($data['str_ireplace']['pattern1']) {
			$time = microtime(true);
			foreach (range(0, $data['limit']) as $res) {
				str_ireplace($data['str_ireplace']['pattern1'], $data['str_ireplace']['pattern2'], $data['output']);
			}
			$time = round(microtime(true) - $time, 3);
			$message .= 'str_ireplace: ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>';
		}

		if ($data['preg_replace']['pattern1']) {
			$time = microtime(true);
			foreach (range(0, $data['limit']) as $res) {
				preg_replace($data['preg_replace']['pattern1'], $data['preg_replace']['pattern2'], $data['output']);
			}
			$time = round(microtime(true) - $time, 3);
			$message .= 'preg_replace: ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>';
		}

		if ($data['preg_match_all']['pattern1']) {
			$time = microtime(true);
			foreach (range(0, $data['limit']) as $res) {
				preg_match_all($data['preg_match_all']['pattern1'], $data['output']);
			}
			$time = round(microtime(true) - $time, 3);
			$message .= 'preg_match_all: ' . $time . ' сек. или ' . ($time * 1000) . ' мс.<br>';
		}

		$this->getDebugSpeed .= $message;
	}

	private function examination($output = array(), $data = array(), $exception = array()) {
		$count = 0;

		// валидация
		if (isset($data[$exception[0]])) {
			$count = 1;
			if (isset($exception[1]) && isset($data[$exception[0]][$exception[1]])) {
				$count = 2;
				if (isset($exception[2]) && isset($data[$exception[0]][$exception[1]][$exception[2]])) {
					$count = 3;
					if (isset($exception[3]) && isset($data[$exception[0]][$exception[1]][$exception[2]][$exception[3]])) {
						$count = 4;
						if (isset($exception[4]) && isset($data[$exception[0]][$exception[1]][$exception[2]][$exception[3]][$exception[4]])) {
							$count = 5;
							if (isset($exception[5]) && isset($data[$exception[0]][$exception[1]][$exception[2]][$exception[3]][$exception[4]][$exception[5]])) {
								$count = 6;
							}
						}
					}
				}
			}
		}

		// устанавливаем значение после валидации
		if ($count == 1) {
			$output[$exception[0]] = $data[$exception[0]];
		} elseif ($count == 2) {
			$output[$exception[0]][$exception[1]] = $data[$exception[0]][$exception[1]];
		} elseif ($count == 3) {
			$output[$exception[0]][$exception[1]][$exception[2]] = $data[$exception[0]][$exception[1]][$exception[2]];
		} elseif ($count == 4) {
			$output[$exception[0]][$exception[1]][$exception[2]][$exception[3]] = $data[$exception[0]][$exception[1]][$exception[2]][$exception[3]];
		} elseif ($count == 5) {
			$output[$exception[0]][$exception[1]][$exception[2]][$exception[3]][$exception[4]] = $data[$exception[0]][$exception[1]][$exception[2]][$exception[3]][$exception[4]];
		} elseif ($count == 6) {
			$output[$exception[0]][$exception[1]][$exception[2]][$exception[3]][$exception[4]][$exception[5]] = $data[$exception[0]][$exception[1]][$exception[2]][$exception[3]][$exception[4]][$exception[5]];
		}

		return $output;
	}
}