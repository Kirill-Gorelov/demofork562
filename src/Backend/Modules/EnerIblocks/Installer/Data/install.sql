CREATE TABLE IF NOT EXISTS category_meta_value
(
  `id`    INT AUTO_INCREMENT,                       -- Идентификатор записи
  `eid`   INT(11),                                  -- ID элемента кому принадлежат мета
  `key`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- ключ
  `value` VARCHAR(1000) COLLATE utf8mb4_unicode_ci, -- значение

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS category_meta_select_value
(
  `id`    INT AUTO_INCREMENT,                       -- Идентификатор записи
  `eid`   INT(11),                                  -- ID элемента кому принадлежат мета
  `key`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- ключ
  `value` VARCHAR(1000) COLLATE utf8mb4_unicode_ci, -- значение

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;


CREATE TABLE IF NOT EXISTS category_element_shop
(
  `s_id`    INT AUTO_INCREMENT,                       -- Идентификатор записи
  `eid`   INT(11),                                  -- ID элемента 
  `weight`   FLOAT,                               -- Вес
  `length` FLOAT,                                 -- длина
  `width` FLOAT,                                  -- ширина
  `height` FLOAT,                                 -- Высота
  `quantity` FLOAT,                               -- Колличество
  `discount` FLOAT,                               -- Скидка
  `coefficient` FLOAT,                            -- Коэффециент вычитания шт. кг. и тд.
  `unit` INT(11),                                   -- Единица измерения
  `price` FLOAT,                                  -- Цена
  `purchase_price` FLOAT,                         -- Закупочная цена

  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS category_element_shop_unit
(
  `s_id`    INT AUTO_INCREMENT,                       -- Идентификатор записи
  `key`   VARCHAR(250),                             -- ключ 
  `value` VARCHAR(250),                             -- значение

  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;