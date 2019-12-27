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

-- вставляем значения по умолчанию для способов оплаты

INSERT INTO shop_method_pay (`title`, `code`, `description`, `active`, `image`, `sort`, `processor`) SELECT 'Наличные', 'cash', 'Оплата при получении', 1, '', 500, '' WHERE NOT EXISTS(SELECT * FROM shop_method_pay WHERE `code` = 'cash');
INSERT INTO shop_method_pay (`title`, `code`, `description`, `active`, `image`, `sort`, `processor`) SELECT 'Курьеру', 'courier', 'Оплата при курьеру', 1, '', 600, '' WHERE NOT EXISTS(SELECT * FROM shop_method_pay WHERE `code` = 'courier');

-- вставляем значения по умолчанию для способов доставки

INSERT INTO shop_method_delivery (`title`, `code`, `description`, `active`, `image`, `sort`, `processor`) SELECT 'Самовывоз', 'pickup', 'Забрать самостоятельно', 1, '', 500, '' WHERE NOT EXISTS(SELECT * FROM shop_method_delivery WHERE `code` = 'pickup');

-- вставляем значения по умолчанию для таблицы статусы

INSERT INTO shop_status_order (`title`, `code`, `description`) SELECT 'Не оплачен', 'no_payid', 'Заказ не оплачен' WHERE NOT EXISTS(SELECT * FROM shop_status_order WHERE `code` = 'no_payid');
INSERT INTO shop_status_order (`title`, `code`, `description`) SELECT 'Оплачен, ожидает доставки', 'paid_awaiting_delivery', 'Оплачен, ожидает доставки' WHERE NOT EXISTS(SELECT * FROM shop_status_order WHERE `code` = 'paid_awaiting_delivery');
INSERT INTO shop_status_order (`title`, `code`, `description`) SELECT 'Отгружен', 'delivery', 'Заказ в пути' WHERE NOT EXISTS(SELECT * FROM shop_status_order WHERE `code` = 'delivery');
INSERT INTO shop_status_order (`title`, `code`, `description`) SELECT 'Выполнен', 'success', 'Заказ получен покупателем' WHERE NOT EXISTS(SELECT * FROM shop_status_order WHERE `code` = 'success');
INSERT INTO shop_status_order (`title`, `code`, `description`) SELECT 'Возврат', 'error', 'Заказ ожидает возврат' WHERE NOT EXISTS(SELECT * FROM shop_status_order WHERE `code` = 'error');