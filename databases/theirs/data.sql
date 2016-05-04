-- -----------------------------------------------------
-- В их реальной таблице иногда отсутсвует логин. 
-- В смоделированной таблице `rt`.`kmn_sl_nach` поле LOGIN - NOT NULL.
-- Поэтому таких записей нет. Имей это в виду.
-- 
-- ВАЖНО:
-- 1. Некоторые логины имеются не во всех периодах
-- 2. Некоторые логины дублируются внутри одного периода, но в разных филиалах
-- 3. Имеются различного рода тестовые записи
-- 4. Иногда отсутсвуют имена
-- -----------------------------------------------------

-- ЗА ФЕФРАЛЬ 2016

-- Карельский

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, INV_CHARGES) VALUES("2016.02.01", "Карельский", "KR00ALKOSEVA", "Алексеева Екатерина Владимировна", 0, 6000.00);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, INV_CHARGES) VALUES("2016.02.01", "Карельский", "KR01STOPUDOV", "Кучеренкова Людмила Николаевна", 0, 4300.25);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.02.01", "Карельский", "KR00SBUHOI", "Сёмкин Владимир Юрьевич", 2, 2000, 2000);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, ACTIVATED_SUBS, TARIFF) VALUES("2016.02.01", "Карельский", "KR00VPOPIK", 1, 0.01);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, ACTIVATED_SUBS, TARIFF) VALUES("2016.02.01", "Карельский", "SZT_MIGRATE", 1, 567);

-- Новгородский

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.02.01", "Новгородский", "KR01STOPUDOV", "Кучеренкова Людмила Николаевна", 0, 1500, 2000);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.02.01", "Новгородский", "HD00STRPO", "Слепухина Алена Анатольевна", 3, 3515, 10150);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, INV_CHARGES) VALUES("2016.02.01", "Новгородский", "HD00ZHUK", "Сапрыкина Ольга Николаевна", 0, 58200);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF) VALUES("2016.02.01", "Новгородский", "HD00VVVVV", "Северова Виктория Вячеславовна", 4, 7715.50);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF) VALUES("2016.02.01", "Новгородский", "NV00BRODT", "Хамеляйнен Александра Алексеевна", 1, 0.01);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, ACTIVATED_SUBS, TARIFF) VALUES("2016.02.01", "Новгородский", "SZT_MIGRATE", 4, 7584);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF) VALUES("2016.02.01", "Новгородский", "TEST_VIP_BASE1", "SBMS TEST TEST", 2, 0.02);


-- ЗА МАРТ 2016

-- Карельский

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.03.01", "Карельский", "KR00ALKOSEVA", "Алексеева Екатерина Владимировна", 3, 13991, 1000.10);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF) VALUES("2016.03.01", "Карельский", "KR01STOPUDOV", "Кучеренкова Людмила Николаевна", 2, 7500);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.03.01", "Карельский", "KR00LTNKES", "Амелина Людмила Анатольевна", 7, 35684.50, 1200);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, ACTIVATED_SUBS, TARIFF) VALUES("2016.03.01", "Карельский", "KR00VPOPIK", 10, 27130.75);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN,  ACTIVATED_SUBS, TARIFF) VALUES("2016.03.01", "Карельский", "SZT_MIGRATE", 1, 101);

-- Новгородский

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.03.01", "Новгородский", "HD00STRPO", "Слепухина Алена Анатольевна", 2, 4115, 150.15);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.03.01", "Новгородский", "HD00ZHUK", "Сапрыкина Ольга Николаевна", 2, 4350, 200);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF) VALUES("2016.03.01", "Новгородский", "HD00VVVVV", "Северова Виктория Вячеславовна", 4, 7900);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.03.01", "Новгородский", "NV00BRODT", "Хамеляйнен Александра Алексеевна", 3, 3100, 100);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, ACTIVATED_SUBS, TARIFF) VALUES("2016.03.01", "Новгородский", "SZT_MIGRATE", 1, 105.50);

-- Петербургский

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.03.01", "Петербургский", "U40C860", "Кольцова Наталья Николаевна", 10, 14131.55, 1500);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF, INV_CHARGES) VALUES("2016.03.01", "Петербургский", "U393CV72", "Меркушева Анастасия Александровна", 2, 4350, 200);

INSERT INTO `rt`.`kmn_sl_nach` (PERIOD, FILIAL, LOGIN, FIO, ACTIVATED_SUBS, TARIFF) VALUES("2016.03.01", "Петербургский", "U82D12", "Татти Эвелина Семеновна", 4, 9700);
