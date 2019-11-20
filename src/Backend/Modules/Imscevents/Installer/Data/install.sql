-- Команды для шлюза ДНПП
CREATE TABLE IF NOT EXISTS imscevents_dnpp_commands
(
  `id`            INT AUTO_INCREMENT,                       -- Идентификатор записи
  `description`   VARCHAR(250) COLLATE utf8mb4_unicode_ci,  -- Описание загружаемой сущности
  `command`       VARCHAR(1000) COLLATE utf8mb4_unicode_ci, -- Команда шлюзу ДНПП
  `language`      VARCHAR(10) COLLATE utf8mb4_unicode_ci,   -- Язык загружаемых мероприятий
  `last_dnpp_id`  INT,                                      -- Идентификатор последнего загруженного из ДНПП мероприятия

  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;

INSERT INTO imscevents_dnpp_commands (description, command, language)
SELECT *
FROM (
  SELECT
    'Загрузка мероприятий из ДНПП',
    'https://iasdnpp.mos.ru/services/api/inno-object-events/?access_token=ej3yNolPszGOzyO5FIJ1pExKktnvtE8N26NnCdua',
    'ru'
) t
WHERE (SELECT COUNT(*) FROM imscevents_dnpp_commands) = 0;

-- Текущее состояние обновления событий из шлюза ДНПП
CREATE TABLE IF NOT EXISTS imscevents_dnpp_update_session
(
  user_id           INT,  -- Код пользователя
  total_step_count  INT,  -- Общее количество шагов
  current_step      INT,  -- Номер текушего шага
  total_news_count  INT,  -- Общее количество новостей в шаге
  current_news      INT,  -- Номер текущей новости

  PRIMARY KEY (user_id)
);

/*
ALTER TABLE imscevents ADD dt_created DATETIME;
ALTER TABLE imscevents ADD dt_updated DATETIME;
ALTER TABLE imscevents ADD dt_activated DATETIME;
ALTER TABLE imscevents ADD inno_object_type_id INT;
ALTER TABLE imscevents ADD oii_name VARCHAR(500) COLLATE utf8mb4_unicode_ci;

ALTER TABLE imscevents ADD hidden SMALLINT NOT NULL DEFAULT 0;
*/

/*
ALTER TABLE imscevents_dnpp_commands ADD gate_id VARCHAR(20);
UPDATE imscevents_dnpp_commands SET gate_id = 'DNPP' WHERE 1 = 1;

ALTER TABLE imscevents_dnpp_commands ADD image_folder VARCHAR(500);
ALTER TABLE imscevents_dnpp_commands ADD password VARCHAR(50);

INSERT INTO imscevents_dnpp_commands (description, command, language, last_dnpp_id, gate_id)
VALUES ('Загрузка мероприятий из ДИТ', 'https://symfony.dev.ict.moscow/api/cfc/events/', 'ru', 0, 'ICT');

ALTER TABLE imscevents ADD ict_id VARCHAR(50);

ALTER TABLE imscevents ADD made_in_moscow TINYINT;
*/