/*
Для полного удаления модуля 
*/

DROP TABLE `shop_settings`;
DROP TABLE `shop_method_pay`;
DROP TABLE `shop_method_delivery`;

DELETE FROM backend_navigation WHERE label = 'EnerShop';
DELETE FROM backend_navigation WHERE label = 'PayMethod';
DELETE FROM backend_navigation WHERE label = 'DeliveryMethod';
DELETE FROM `modules` WHERE name = 'EnerShop';

-- даление из таблицы locale
DELETE FROM locale WHERE name = 'EnerShop';
DELETE FROM locale WHERE name = 'PayMethod';
DELETE FROM locale WHERE name = 'PayIndex';
DELETE FROM locale WHERE name = 'DeliveryMethod';
DELETE FROM locale WHERE name = 'DeliveryIndex';
DELETE FROM locale WHERE name = 'IndexSettings';
