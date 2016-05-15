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

INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "KR00ALKOSEVA", "pw123", "Алексеева Екатерина Владимировна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "KR01STOPUDOV", "pw1234", "Кучеренкова Людмила Николаевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "KR00SBUHOI", "pwpw123", "Сёмкин Владимир Юрьевич");
INSERT INTO `info_table`.`person` (access_type, login, password) VALUES(1, "KR00VPOPIK", "pwpw123");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "HD00STRPO", "pws123", "Слепухина Алена Анатольевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "HD00ZHUK", "pwqw123", "Сапрыкина Ольга Николаевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "HD00VVVVV", "pw43123", "Северова Виктория Вячеславовна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "NV00BRODT", "pw34123", "Хамеляйнен Александра Алексеевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "KR00LTNKES", "pwds123", "Амелина Людмила Анатольевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "U40C860", "pawpw123", "Кольцова Наталья Николаевна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "U393CV72", "dspw123", "Меркушева Анастасия Александровна");
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(1, "U82D12", "sdspw123", "Татти Эвелина Семеновна");
INSERT INTO `info_table`.`person` (access_type, login, password) VALUES(1, "UNKNOWN_LOGIN", "pwpw123");

-- другие роли

-- руководитель группы
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(2, "MNGR1", "pwrd123", "Пупкина Александра Алдександровна");
-- начальник отдела
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(3, "DPTMNT_HD1", "pwdrw123", "Иванов Иван Иванович");
-- админ
INSERT INTO `info_table`.`person` (access_type, login, password, fio) VALUES(4, "admin1", "pw123321", "Жуков Игорь Олегович");
