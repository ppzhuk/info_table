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

INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "KR00ALKOSEVA", "Алексеева Екатерина Владимировна");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "KR01STOPUDOV", "Кучеренкова Людмила Николаевна");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "KR00SBUHOI", "Сёмкин Владимир Юрьевич");
INSERT INTO `info_table`.`person` (access_type, login) VALUES(1, "KR00VPOPIK");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "HD00STRPO", "Слепухина Алена Анатольевна");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "HD00ZHUK", "Сапрыкина Ольга Николаевна");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "HD00VVVVV", "Северова Виктория Вячеславовна");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "NV00BRODT", "Хамеляйнен Александра Алексеевна");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "KR00LTNKES", "Амелина Людмила Анатольевна");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "U40C860", "Кольцова Наталья Николаевна");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "U393CV72", "Меркушева Анастасия Александровна");
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(1, "U82D12", "Татти Эвелина Семеновна");
INSERT INTO `info_table`.`person` (access_type, login) VALUES(1, "UNKNOWN_LOGIN");

-- другие роли

-- руководитель группы
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(2, "MNGR1", "Пупкина Александра Алдександровна");
-- начальник отдела
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(3, "DPTMNT_HD1", "Иванов Иван Иванович");
-- админ
INSERT INTO `info_table`.`person` (access_type, login, fio) VALUES(4, "admin1", "Жуков Игорь Олегович");