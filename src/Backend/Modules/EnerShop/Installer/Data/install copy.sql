/*
Для полного удаления модуля 
*/

DROP TABLE `shop_settings`;
DROP TABLE `shop_method_pay`;
DROP TABLE `shop_method_pay`;

DELETE FROM backend_navigation WHERE label = 'EnerShop';
DELETE FROM backend_navigation WHERE label = 'PayMethod';
DELETE FROM backend_navigation WHERE label = 'DeliveryMethod';
DELETE FROM `modules` WHERE name = 'EnerShop';
