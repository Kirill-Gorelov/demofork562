/*
Для полного удаления модуля 
*/

DROP TABLE `shop_settings`;
DROP TABLE `shop_method_pay`;
DROP TABLE `shop_method_delivery`;
DROP TABLE `shop_status_order`;
DROP TABLE `shop_order`;
DROP TABLE `shop_order_history_status`;
DROP TABLE `shop_order_product`;

DELETE FROM backend_navigation WHERE label = 'EnerShop';
DELETE FROM backend_navigation WHERE label = 'PayMethod';
DELETE FROM backend_navigation WHERE label = 'DeliveryMethod';
DELETE FROM backend_navigation WHERE label = 'StatusOrders';
DELETE FROM backend_navigation WHERE label = 'Order';
DELETE FROM `modules` WHERE name = 'EnerShop';

-- даление из таблицы locale
DELETE FROM locale WHERE name = 'EnerShop';
DELETE FROM locale WHERE name = 'IndexSettings';

DELETE FROM locale WHERE name = 'PayMethod';
DELETE FROM locale WHERE name = 'PayIndex';
DELETE FROM locale WHERE name = 'PayEdit';
DELETE FROM locale WHERE name = 'PayAdd';

DELETE FROM locale WHERE name = 'DeliveryMethod';
DELETE FROM locale WHERE name = 'DeliveryIndex';
DELETE FROM locale WHERE name = 'DeliveryAdd';
DELETE FROM locale WHERE name = 'DeliveryEdit';

DELETE FROM locale WHERE name = 'StatusOrders';
DELETE FROM locale WHERE name = 'StatusIndex';
DELETE FROM locale WHERE name = 'StatusEdit';
DELETE FROM locale WHERE name = 'StatusAdd';

DELETE FROM locale WHERE name = 'Order';
DELETE FROM locale WHERE name = 'OrderIndex';
DELETE FROM locale WHERE name = 'OrderEdit';
DELETE FROM locale WHERE name = 'OrderAdd';

