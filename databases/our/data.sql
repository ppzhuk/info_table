-- ----------------------------------
-- Заполняем access_type
-- ----------------------------------

INSERT INTO `info_table`.`access_type` (name) VALUES("seller");
INSERT INTO `info_table`.`access_type` (name) VALUES("manager");
INSERT INTO `info_table`.`access_type` (name) VALUES("head of department");
INSERT INTO `info_table`.`access_type` (name) VALUES("administrator");

-- ----------------------------------
-- Заполняем person
-- ----------------------------------

-- продавцы

INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "KR00ALKOSEVA", "c6fcf556fd8c9130408c93f91c593eb4", "Алексеева Екатерина Владимировна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "KR01STOPUDOV", "c6fcf556fd8c9130408c93f91c593eb4", "Кучеренкова Людмила Николаевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "KR00SBUHOI", "c6fcf556fd8c9130408c93f91c593eb4", "Сёмкин Владимир Юрьевич");
INSERT INTO `info_table`.`person` (access_type, login, password) VALUES(1, "KR00VPOPIK", "c6fcf556fd8c9130408c93f91c593eb4");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "HD00STRPO", "c6fcf556fd8c9130408c93f91c593eb4", "Слепухина Алена Анатольевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "HD00ZHUK", "c6fcf556fd8c9130408c93f91c593eb4", "Сапрыкина Ольга Николаевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "HD00VVVVV", "c6fcf556fd8c9130408c93f91c593eb4", "Северова Виктория Вячеславовна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "NV00BRODT", "c6fcf556fd8c9130408c93f91c593eb4", "Хамеляйнен Александра Алексеевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "KR00LTNKES", "c6fcf556fd8c9130408c93f91c593eb4", "Амелина Людмила Анатольевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "U40C860", "c6fcf556fd8c9130408c93f91c593eb4", "Кольцова Наталья Николаевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "U393CV72", "c6fcf556fd8c9130408c93f91c593eb4", "Меркушева Анастасия Александровна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "U82D12", "c6fcf556fd8c9130408c93f91c593eb4", "Татти Эвелина Семеновна");
INSERT INTO `info_table`.`person` (access_type, login, password) VALUES(1, "UNKNOWN_LOGIN", "c6fcf556fd8c9130408c93f91c593eb4");

-- другие роли

-- руководитель группы
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(2, "MNGR1", "c6fcf556fd8c9130408c93f91c593eb4", "Пупкина Александра Алдександровна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(2, "MNGR2", "c6fcf556fd8c9130408c93f91c593eb4", "Сергеев Сергей Сергеевич");
-- начальник отдела
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(3, "DPTMNT_HD1", "c6fcf556fd8c9130408c93f91c593eb4", "Иванов Иван Иванович");
-- админ
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(4, "admin1", "c6fcf556fd8c9130408c93f91c593eb4", "Жуков Игорь Олегович");

-- ----------------------------------
-- Заполняем groups
-- ----------------------------------

INSERT INTO `info_table`.`groups` (group_name, owner, group_type) VALUES("TEST_GROUP", 14, "KAM");
INSERT INTO `info_table`.`groups` (group_name, owner, group_type) VALUES("SPB_SALES01", 14, "seller");
INSERT INTO `info_table`.`groups` (group_name, owner, group_type) VALUES("SPB_SALES_ALL", 15, "seller");

-- ----------------------------------
-- Заполняем relation
-- ----------------------------------

INSERT INTO `info_table`.`relation` VALUES(1, 1);
INSERT INTO `info_table`.`relation` VALUES(2, 1);
INSERT INTO `info_table`.`relation` VALUES(3, 1);
INSERT INTO `info_table`.`relation` VALUES(4, 1);
INSERT INTO `info_table`.`relation` VALUES(5, 1);
INSERT INTO `info_table`.`relation` VALUES(6, 1);

INSERT INTO `info_table`.`relation` VALUES(7, 2);
INSERT INTO `info_table`.`relation` VALUES(8, 2);
INSERT INTO `info_table`.`relation` VALUES(9, 2);
INSERT INTO `info_table`.`relation` VALUES(10, 2);
INSERT INTO `info_table`.`relation` VALUES(11, 2);
INSERT INTO `info_table`.`relation` VALUES(12, 2);

INSERT INTO `info_table`.`relation` VALUES(1, 3);
INSERT INTO `info_table`.`relation` VALUES(2, 3);
INSERT INTO `info_table`.`relation` VALUES(3, 3);
INSERT INTO `info_table`.`relation` VALUES(4, 3);
INSERT INTO `info_table`.`relation` VALUES(5, 3);
INSERT INTO `info_table`.`relation` VALUES(6, 3);
INSERT INTO `info_table`.`relation` VALUES(7, 3);
INSERT INTO `info_table`.`relation` VALUES(8, 3);
INSERT INTO `info_table`.`relation` VALUES(9, 3);
INSERT INTO `info_table`.`relation` VALUES(10, 3);
INSERT INTO `info_table`.`relation` VALUES(11, 3);
INSERT INTO `info_table`.`relation` VALUES(12, 3);
