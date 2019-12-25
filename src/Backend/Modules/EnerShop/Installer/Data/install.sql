CREATE TABLE IF NOT EXISTS shop_settings
(
  `key`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- ключ
  `value` VARCHAR(1000) COLLATE utf8mb4_unicode_ci -- значение

) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

-- вставляем значения по умолчанию DROP TABLE `shop_settings`

INSERT INTO shop_settings (`key`, `value`) SELECT 'nds',20 WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'nds');
INSERT INTO shop_settings (`key`, `value`) SELECT 'prefix','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'prefix');
INSERT INTO shop_settings (`key`, `value`) SELECT 'time_save_basket','30' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'time_save_basket');
INSERT INTO shop_settings (`key`, `value`) SELECT 'change_status_for_pay','1' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'change_status_for_pay');
INSERT INTO shop_settings (`key`, `value`) SELECT 'page_payment_success','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'page_payment_success');
INSERT INTO shop_settings (`key`, `value`) SELECT 'page_payment_error','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'page_payment_error');
INSERT INTO shop_settings (`key`, `value`) SELECT 'redirect_page_payment_success','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'redirect_page_payment_success');
INSERT INTO shop_settings (`key`, `value`) SELECT 'redirect_page_payment_error','' WHERE NOT EXISTS(SELECT * FROM shop_settings WHERE `key` = 'redirect_page_payment_error');