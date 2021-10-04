<?php
// *   Аўтар: "БуслікДрэў" ( https://buslikdrev.by/ )
// *   © 2016-2021; BuslikDrev - Усе правы захаваныя.
// *   Спецыяльна для сайта: "OpenCart.pro" ( https://opencart.pro/ )

// Heading
$_['heading_title']                              = '<b>Буслік Кэш <span style="color:blue">Lite</span></b> v' . (isset($this->data['bus_cache_version']) ? $this->data['bus_cache_version'] : '1.0');
$_['heading_description']                        = '';

// Tab
$_['tab_setting']                                = 'Налаштування';
$_['tab_cache']                                  = 'Кешування';
$_['tab_pagespeed']                              = 'PageSpeed';
$_['tab_support']                                = 'Технічна підтримка';

// Text
$_['text_extension']                             = 'Розширення';
$_['text_module']                                = 'Модулі';
$_['text_edit']                                  = 'Редагування';
$_['text_min']                                   = 'Від';
$_['text_max']                                   = 'До';
$_['text_yes']                                   = 'Так';
$_['text_no']                                    = 'Ні';
$_['text_enabled']                               = 'Включено';
$_['text_disabled']                              = 'Відключено';
$_['text_all']                                   = ' --- Все --- ';
$_['text_none']                                  = ' --- Чи не вибрано --- ';
$_['text_select']                                = ' --- Виберіть --- ';
$_['text_select_all']                            = 'Виділити все';
$_['text_unselect_all']                          = 'Зняти виділення';
$_['text_width']                                 = 'Ширина';
$_['text_height']                                = 'Висота';
$_['text_install']                               = 'Установка модуля...';
$_['text_uninstall']                             = 'Видалення модуля...';
$_['text_uninstall_files']                       = 'Видалення файлів модуля...';
$_['text_uninstall_files_log']                   = 'Звіт про видалення файлів модуля';
$_['text_ocmod_clear']                           = 'Чистка модифікаторів...';
$_['text_ocmod_clearlog']                        = 'Чистка балки модифікаторів...';
$_['text_ocmod_refresh']                         = 'Оновлення модифікаторів...';
$_['text_cache_clear']                           = 'Чистка кешу...';
$_['text_processing']                            = 'Обробка';
$_['text_loading']                               = 'Завантаження';
$_['text_start']                                 = 'Старт';
$_['text_continue']                              = 'Продовжіті';
$_['text_pause']                                 = 'Пауза';
$_['text_restart']                               = 'Рестарт';
$_['text_link']                                  = 'Посилання';
$_['text_default']                               = 'За замовчуванням';
$_['text_guest']                                 = 'Гість';
$_['text_dir_1']                                 = 'Справа на ліво';
$_['text_dir_2']                                 = 'Зліва на право';
$_['text_time_php']                              = 'Затрачено времени PHP: %s секунд';
$_['text_time_js']                               = 'Затрачено времени JavaScript: %s секунд';
$_['text_total']                                 = 'Всего файлов обработано:';
$_['text_path']                                  = 'Шлях';
$_['text_no_results']                            = 'Немає даних';
$_['text_confirm']                               = 'Ви впевнені?';
$_['text_author']                                = 'Автор: <a href="https://buslikdrev.by/" title="Ремесленние ізделія" rel="noreferrer noopener" target="_blank">БуслікДрев</a>. Тех. підтримка: <a href="https://liveopencart.ru/buslikdrev" title="Технічна допомога по вирішенню проблем, повязаних з модулем" rel="noreferrer noopener" target="_blank">ТУТ</a>. Тема підтримки: <a href="https://forum.opencart.pro/topic/6191-буслік-кэш-buslik-cache/" title="Технічна допомога по вирішенню проблем, повязаних з модулем" rel="noreferrer noopener" target="_blank">ТУТ</a>.';
$_['text_corp']                                  = '© 2016-' . date('d.m.Y') . '; <a href="https://buslikdrev.by/" title="BuslikDrev" rel="noreferrer noopener" target="_blank">BuslikDrev</a> - Всі права збережені.';

// Entry
$_['entry_status']                               = 'Статус';
$_['entry_store']                                = 'Магазин';
$_['entry_cache_status']                         = 'Включити кешування';
$_['entry_cache_customer']                       = 'Кешувати для авторизованих користувачів?';
$_['entry_cache_oc']                             = 'Контролювати кеш OpenCart';
$_['entry_cache_engine']                         = 'Тип кешу';
$_['entry_cache_expire']                         = 'Час життя кешу';
$_['entry_cache_ses']                            = 'Параметри сесій';
$_['entry_cache_onrot']                          = 'Параметри роботи';
$_['entry_cache_rot']                            = 'Параметри виключення';
$_['entry_cache_device']                         = 'Включити визначення мобільних пристроїв';
$_['entry_pagespeed_status']                     = 'Включити оптимізацію (PageSpeed)';
$_['entry_pagespeed_preload_logo']               = 'Предзагружать Лого сайту';
$_['entry_pagespeed_attribute_w_h']              = 'Включити атрибути ширини і висоти';
$_['entry_pagespeed_lazy_load']                  = 'Включити Lazy load';
$_['entry_pagespeed_html_min']                   = 'Сжимать html код';
$_['entry_pagespeed_css_min']                    = 'Сжимать css код';
$_['entry_pagespeed_css_min_links']              = 'Посилання на css файли';
$_['entry_pagespeed_css_min_download']           = 'Посилання на css файли для скачування';
$_['entry_pagespeed_css_min_exception']          = 'Виняток з стиснення css';
$_['entry_pagespeed_css_min_font']               = 'Предзагрузкі шрифтів або зображень';
$_['entry_pagespeed_css_min_display']            = 'Font-Display';
$_['entry_pagespeed_js_min']                     = 'Сжимать js код';
$_['entry_pagespeed_js_min_links']               = 'Посилання на js файли';
$_['entry_pagespeed_js_min_exception']           = 'Виняток з стиснення js';
$_['entry_pagespeed_rot']                        = 'Параметри виключення';
$_['entry_debug']                                = 'Включити налагодження';

// Help
$_['help_status']                                = 'Якщо відключено, то весь функціонал нижче буде відключений.';
$_['help_store']                                 = 'Виберіть магазини в яких буде працювати модуль.';
$_['help_cache_status']                          = 'Якщо так, то будуть кешуватися сторінки цілком.';
$_['help_cache_oc']                              = 'Якщо так, то кеш інших модулів буде контролюватися настройками Буслік Кэш.';
$_['help_cache_engine']                          = 'За умолчапнію File.<br />APC - це кеш кодів операцій і сховище даних за допомогою оперативної пам\'яті, підходить для тих у кого немає розширення OPCache;<br />APCu - це нова версія APC преднозначено тільки для зберігання даних за допомогою оперативної пам\'яті. Рекомендується включати OPCache на сервері;<br />Buslik - те ж саме, що і File, але дозволяє зберігати файли кешу розділяючи по папках, наданих різними магазину, мови, групи покупців, пристрої, категорії і виробника, тим самим прискорює пошук потрібного кеш файлу і дозволяє зберігати більшу кількість; <br /> File - стандартне зберігання кеш файлів в system/storage/cache/;<br />Memcache - кешування даних в оперативній пам\'яті на основі хеш-таблиці;<br />Memcached - нова версія Memcache для кешування даних в оперативній пам\'яті на основі хеш-таблиці;<br />Redis - те ж саме, що Memcached, але з великими можливостями;';
$_['help_cache_expire']                          = 'За умолчапнію 3600 секунд. Час життя кешу задається в секундах.';
$_['help_cache_ses']                             = 'Вкажіть параметри сесій для різноманітності кеша.';
$_['help_cache_onrot']                           = 'Вкажіть роут модулів або їх keyword (seo_url) для точної роботи модуля (При заповненні параметри виключення діяти не будуть).';
$_['help_cache_rot']                             = 'Вкажіть роут модулів або їх keyword (seo_url) для виключення з кешу.';
$_['help_cache_device']                          = 'Якщо так, то кеш буде створюватися окремо під різні пристрої.';
$_['help_pagespeed_status']                      = 'Включення оптимізації має прискорити віддачу контенту тим самим полюбити ваш сайт з пошуковими роботами, а значить позиції в пошуковій видачі повинні стати вище.';
$_['help_pagespeed_preload_logo']                = 'Включення цієї функції повинно допомогти прибрати зрушення дизайну через завантаження лого під час завантаження дизайну.';
$_['help_pagespeed_attribute_w_h']               = 'Включення цієї функції буде додавати атрибути ширини і висоти до всіх зображень у яких в назвах є їх розміри, наприклад, image-100х100.jpg';
$_['help_pagespeed_lazy_load']                   = 'Виберіть варіант подгрузки зображень.';
$_['help_pagespeed_html_min']                    = 'Виберіть варіант стиснення html коду. 0 - без стиснення, 4 максимальне стиснення.';
$_['help_pagespeed_css_min']                     = 'Виберіть варіант стиснення css коду. 0 - без складання, 1 - збірка без стиснення, 5 максимальне стиснення. Якщо включено, то всі файли стилів, які обробляє OpenCart за стандартом, будуть з\'єднані в один файл і максимально стиснуті.';
$_['help_pagespeed_css_min_links']               = 'Додайте посилання на css стиль який не потрапив до загального файл стилів (для роботи з інших сайтів, вкажіть посилання також в поле для скачування). Значення задається починаючи з нового рядка. Якщо не хочете видаляти, то перед посиланням встановили \' ; \'.';
//$_['help_pagespeed_css_min_links']               = 'Додайте посилання на css стиль який не потрапив до загального файл стилів (для роботи з інших сайтів, вкажіть посилання також в поле для скачування). Значення задається починаючи з нового рядка. Якщо не хочете видаляти, то перед посиланням встановили \' ; \'. Якщо ви хочете вказати свої атрибути, то після посилання встановіть \' | \' і впишіть атрибути, приклад, catalog/style.css|type=&quot;text/css&quot; rel=&quot;preload&quot;<br />Також можна вказати на початку посилання наступні параметри (атрибути мають гору над параметрами):<br />[footer] - відправити посилання в низ сайту;<br />[prelod] - просить браузер попередньо завантажити;<br />[prefetch] - просить браузер відкласти завантаження в кінець;<br />[dns-prefetch] - просить браузер заздалегідь виконати резолвінг DNS для домену;<br />[preconnect] - просить браузер заздалегідь підключитися до домену;';
$_['help_pagespeed_css_min_download']            = 'Додайте посилання на css стиль який потрібно завантажити на свій сайт (завантажить при збереженні налаштувань, рекомендується спочатку зберегти поточні настройки). Якщо не хочете видаляти, то перед посиланням встановили \' ; \'.';
$_['help_pagespeed_css_min_exception']           = 'Додайте посилання на css стиль який хочете виключити і відправити своїм шляхом. Значення задається починаючи з нового рядка. Якщо не хочете видаляти, то перед посиланням встановили \' ; \'.';
$_['help_pagespeed_css_min_font']                = 'Додайте посилання на шрифт або картинку на яку лається Pagespeed - просить, щоб предзагрузілі. Значення задається починаючи з нового рядка. Якщо не хочете видаляти, то перед посиланням встановили \' ; \'. Також можете вказати доменне ім\'я сторонніх шрифтів разом із посиланням стилю в якому йде завантаживши шрифтів розділивши \' | \', Тоді це доменне ім\'я буде зумовлюватися (dns-prefetch, preconnect), наприклад, https://fonts.gstatic.com/|https://fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700&display=swap';
$_['help_pagespeed_css_min_display']             = 'Увімкніть цю функцію, щоб 100% для шрифтів було встановлено властивість font-display щоб не показувати невидимий текст.';
$_['help_pagespeed_js_min']                      = 'Виберіть варіант стиснення js коду. 0 - без складання, 1 - збірка без стиснення, 4 максимальне стиснення. Якщо включено, то всі файли скриптів, які обробляє OpenCart за стандартом, будуть з\'єднані в один файл і максимально стиснуті.';
$_['help_pagespeed_js_min_links']                = 'Додайте посилання на js скрипт який не потрапив до загального файл скриптів. Значення задається починаючи з нового рядка. Якщо не хочете видаляти, то перед посиланням встановили \' ; \'.';
//$_['help_pagespeed_js_min_links']                = 'Додайте посилання на js скрипт який не потрапив до загального файл скриптів. Значення задається починаючи з нового рядка. Якщо не хочете видаляти, то перед посиланням встановили \' ; \'. Якщо ви хочете вказати свої атрибути, то після посилання встановіть \' | \' і впишіть атрибути, приклад, catalog/script.js|type=&quot;text/javascript&quot; rel=&quot;preload&quot;<br />Також можна вказати на початку посилання наступні параметри (атрибути мають гору над параметрами):<br />[footer] - відправити посилання в низ сайту;<br />[prelod] - просить браузер попередньо завантажити;<br />[prefetch] - просить браузер відкласти завантаження в кінець;<br />[dns-prefetch] - просить браузер заздалегідь виконати резолвінг DNS для домену;<br />[preconnect] - просить браузер заздалегідь підключитися до домену;';
$_['help_pagespeed_js_min_exception']            = 'Додайте посилання на js скрипт який хочете виключити і відправити своїм шляхом. Наприклад, бувають скрипти які повністю незалежні і не блокують рендер сторінки, тому не варто їх стискати з іншими тому це може погіршити оптимізацію. Значення задається починаючи з нового рядка. Якщо не хочете видаляти, то перед посиланням встановили \' ; \'.';
$_['help_pagespeed_rot']                         = 'Вкажіть роут модулів або їх keyword (seo_url) для виключення з оптимізації.';
$_['help_debug']                                 = 'Якщо так, то для адміністратора над шапкою сайту буде виводитися інформація про час завантаження сторінки, також кеш почне діяти.';

//Button
$_['button_files_clear']                         = 'Видалити також файли модуля? - якщо немає, просто оновіть сторінку від гріха. Звіт про видалення файлів дивіться в логах модифікаторів.';
$_['button_add']                                 = 'Додати';
$_['button_delete']                              = 'Видалити';
$_['button_clear']                               = 'Очистити кеш';
$_['button_save']                                = 'Зберегти';
$_['button_apply']                               = 'Застосувати';
$_['button_apply_piecemeal']                     = 'Застосувати частинами';
$_['button_export']                              = 'Експорт';
$_['button_import']                              = 'Імпорт';
$_['button_start']                               = 'Запуск завантаження';
$_['button_continue']                            = 'Продовження завантаження з місця зупинки';
$_['button_pause']                               = 'Призупинення завантаження';
$_['button_restart']                             = 'Перезапуск завантаження - почати спочатку';
$_['button_search']                              = 'Знайти';
$_['button_edit']                                = 'Редагувати';

// Error
$_['error_permission']                           = 'У вас не досить прав для внесення змін!';
$_['error_warning']                              = 'Уважно перевірте форму на помилки!';
$_['error_install']                              = 'Нешта пайшло не так!';
$_['error_uninstall']                            = 'Нешта пайшло не так!';
$_['error_name']                                 = 'Название должно содержать от 3 до 64 символов!';
$_['error_width']                                = 'Укажите Ширину!';
$_['error_height']                               = 'Укажите Высоту!';
$_['error_max_input_vars']                       = '<b>Увага! Буде перевищений ліміт параметра %s</b>, якщо перевищити, дані можуть не зберегтися. Збільште значення на сервері або зверніться з цим проханням до хостера для збільшення ліміту. Або скористайтеся кнопкою застосування частинами.<br>Ліміт на сервері: %s<br>Ліміт від модуля:%s - відсічення %s, щоб уникнути втрати даних<br>Поточне значення: ';
$_['error_setting_import']                       = 'Файл не містить настройки модуля, імпорту відмовлено!';
$_['error_setting_import_format']                = 'Модуль не знає про такий формат, імпорту відмовлено! - модуль м\'яко вас послав.';
$_['error_not_required']                         = 'Не вимагається!';

// Success
$_['success_install']                            = ' - успішно встановлений!';
$_['success_uninstall']                          = ' - успішно вилучено!';
$_['success_uninstall_data_base']                = '◄ База даних видалена ►: ';
$_['success_uninstall_modification']             = '◄ Модифікатор видалений ►: ';
$_['success_uninstall_folder']                   = '◄ Папка видалена тому файлів немає ►: ';
$_['success_uninstall_file']                     = '◄ Файл видалений ►: ';
$_['success_update']                             = ' - успішно змінено!';
$_['success_setting']                            = 'Налаштування успешно змінено!';
$_['success_setting_apply']                      = 'Налаштування успішно застосовані!';
$_['success_setting_save']                       = 'Налаштування успішно збережені!';
$_['success_setting_new']                        = 'Новий модуль успішно доданий!';
$_['success_setting_redirect']                   = 'Ви були перенаправлені на потрібну сторінку налаштувань!';
$_['success_setting_import']                     = 'Налаштування модуля "%s" успішно імпортовані в модуль, вам залишилося їх застосувати!';
$_['success_add']                                = 'Успішно додано!';
$_['success_delete']                             = 'Успішно видалено!';
$_['success_clear']                              = 'Успішно очищено!';