CREATE TABLE IF NOT EXISTS category_meta_value
(
  `id`    INT AUTO_INCREMENT,                       -- Идентификатор записи
  `eid`   INT(11),                                  -- ID элемента кому принадлежат мета
  `key`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- ключ
  `value` VARCHAR(1000) COLLATE utf8mb4_unicode_ci, -- значение

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;