CREATE TABLE IF NOT EXISTS shop_settings
(
  `id`    INT AUTO_INCREMENT,   
  `key`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- ключ
  `value` VARCHAR(1000) COLLATE utf8mb4_unicode_ci, -- значение

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

-- вставляем значения по умолчанию 

INSERT INTO shop_settings (`key`, `value`) SELECT 'nds',20 WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'nds');
INSERT INTO shop_settings (`key`, `value`) SELECT 'prefix','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'prefix');
INSERT INTO shop_settings (`key`, `value`) SELECT 'time_save_basket','30' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'time_save_basket');
INSERT INTO shop_settings (`key`, `value`) SELECT 'change_status_for_pay','1' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'change_status_for_pay');
INSERT INTO shop_settings (`key`, `value`) SELECT 'page_payment_success','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'page_payment_success');
INSERT INTO shop_settings (`key`, `value`) SELECT 'page_payment_error','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'page_payment_error');
INSERT INTO shop_settings (`key`, `value`) SELECT 'redirect_page_payment_success','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'redirect_page_payment_success');
INSERT INTO shop_settings (`key`, `value`) SELECT 'redirect_page_payment_error','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'redirect_page_payment_error');
INSERT INTO shop_settings (`key`, `value`) SELECT 'log_order','0' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'log_order');

-- вставляем значения по умолчанию для способов оплаты

INSERT INTO shop_method_pay (`title`, `code`, `description`, `active`, `image`, `sort`, `processor`) SELECT 'Наличные', 'cash', 'Оплата при получении', 1, '', 500, '' WHERE NOT EXISTS(SELECT * FROM shop_method_pay WHERE `code` = 'cash');
INSERT INTO shop_method_pay (`title`, `code`, `description`, `active`, `image`, `sort`, `processor`) SELECT 'Курьеру', 'courier', 'Оплата при курьеру', 1, '', 600, '' WHERE NOT EXISTS(SELECT * FROM shop_method_pay WHERE `code` = 'courier');

-- вставляем значения по умолчанию для способов доставки

INSERT INTO shop_method_delivery (`title`, `code`, `description`, `active`, `image`, `sort`, `processor`) SELECT 'Самовывоз', 'pickup', 'Забрать самостоятельно', 1, '', 500, '' WHERE NOT EXISTS(SELECT * FROM shop_method_delivery WHERE `code` = 'pickup');

-- вставляем значения по умолчанию для таблицы статусы

INSERT INTO shop_order_status (`title`, `code`, `description`) SELECT 'Не оплачен', 'no_payid', 'Заказ не оплачен' WHERE NOT EXISTS(SELECT * FROM shop_order_status WHERE `code` = 'no_payid');
INSERT INTO shop_order_status (`title`, `code`, `description`) SELECT 'Оплачен, ожидает доставки', 'paid_awaiting_delivery', 'Оплачен, ожидает доставки' WHERE NOT EXISTS(SELECT * FROM shop_order_status WHERE `code` = 'paid_awaiting_delivery');
INSERT INTO shop_order_status (`title`, `code`, `description`) SELECT 'Отгружен', 'delivery', 'Заказ в пути' WHERE NOT EXISTS(SELECT * FROM shop_order_status WHERE `code` = 'delivery');
INSERT INTO shop_order_status (`title`, `code`, `description`) SELECT 'Выполнен', 'success', 'Заказ получен покупателем' WHERE NOT EXISTS(SELECT * FROM shop_order_status WHERE `code` = 'success');
INSERT INTO shop_order_status (`title`, `code`, `description`) SELECT 'Возврат', 'error', 'Заказ ожидает возврат' WHERE NOT EXISTS(SELECT * FROM shop_order_status WHERE `code` = 'error');

-- таблица с историей изменения статусов заказа

CREATE TABLE IF NOT EXISTS shop_order_history_status
(
  `id`    INT AUTO_INCREMENT,   -- id 
  `id_order`   INT(11),         -- из заказа
  `id_status` INT(11),          -- id статуса
  `date` DATETIME,              -- Время и статус смена статуса

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

-- таблица с товарами в заказе

CREATE TABLE IF NOT EXISTS shop_order_product
(
  `id`    INT AUTO_INCREMENT,   -- id 
  `id_order`   INT(11),         -- из заказа
  `id_product`   INT(11),       -- из товара
  `price`   FLOAT,            -- цена за один элемент
  `item_price`   FLOAT,            -- цена общая
  `quantity`   FLOAT,         -- колличество, или вес
  `discount`   FLOAT,         -- процент скидки
  `discount_price`   FLOAT,   -- процент скидки в рублях
  `title`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- название
  `property`   TEXT COLLATE utf8mb4_unicode_ci,  -- свойства заказа

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

-- Таблица с контактами для заказа

CREATE TABLE IF NOT EXISTS shop_order_user_property
(
  `id`    INT AUTO_INCREMENT,   -- id 
  `id_order`   INT(11),         -- из заказа
  `user_first_name`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- Имя пользователя
  `user_second_name`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- Фамилия
  `user_patronymic_name`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- отчество
  `user_address`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- Адрес доставки
  `user_phone`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- Телефон
  `user_email`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- почта

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;
