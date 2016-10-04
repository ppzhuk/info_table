<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

class Groups extends ActiveRecord
{
    const PERSON_ALL = 0;
    const PERSON_SELLER = 1;
    const PERSON_MANAGER = 2;
    const PERSON_HEAD_OF_DEPARTMENT = 3;
    const PERSON_ADMIN = 4;

    static public $monthNames = [
        'Январь',
        'Февраль',
        'Март',
        'Апрель',
        'Май',
        'Июнь',
        'Июль',
        'Август',
        'Сентябрь',
        'Октябрь',
        'Ноябрь',
        'Декабрь',
    ];

    static public function randomFill()
    {
        $buff = self::getDb()->createCommand("
            SELECT
                `seller`
            FROM
                `sells`
            WHERE
                `date` = '2016-03-01'
        ")->queryAll();
        $ids = [];
        $values = [100, 350, 500, 280, 700, 1203];
        foreach ($buff as $item) {
            $ids[] = $item['seller'];
        }
        self::getDb()->createCommand("
            INSERT INTO
                `sells`
                (
                  `value`,
                  `date`,
                  `seller`
                )
            VALUES
                (
                  '" . $values[array_rand($values, 1)] . "',
                  '2016-03-01',
                  " . $ids[array_rand($ids, 1)] . "
                )
            ON DUPLICATE KEY UPDATE
                `value` = `value` + VALUES(`value`)
        ")->execute();
    }

    static public function getSells($groupId = null, $period = null)
    {
        $where = [];
        $cond = [];
        if ($groupId) {
            $where[] = "`relation`.`group` = :groupId";
            $cond['groupId'] = $groupId;
        }
        if (!$period) {
            $period = date('Y-m-01');
        }
        $where[] = "`sells`.`date` = :period";
        $cond['period'] = $period;
        $startMonth = intval(substr($period, 6, 2));
        $startMonth -= ($startMonth - 1) % 3;
        for ($i = 0; $i < 3; $i++) {
            $cond['month' . $i] = $startMonth + $i;
            if (strlen($cond['month' . $i] ) == 1) {
                $cond['month' . $i]  = '0' . $cond['month' . $i] ;
            }
        }
        $forRet = self::getDb()->createCommand("
            SELECT
                `person`.`id` AS `personId`,
                `person`.`fio` AS `personName`,
                `person`.`access_type` AS `personAccessType`,
                `relation`.`group` AS `groupId`,
                `sells`.`date` AS `sellsPeriod`,
                `sells`.`value` + `ai`.`correctionValue` AS `sellsValue`,
                `groups`.`group_type` AS `groupType`,
                `ai`.`yearValue` + `ai`.`correctionValue` AS `yearValue`,
                `ai`.`monthValue1` + `ai`.`correctionMonthValue1` AS `monthValue1`,
                `ai`.`monthValue2` + `ai`.`correctionMonthValue2` AS `monthValue2`,
                `ai`.`monthValue3` + `ai`.`correctionMonthValue3` AS `monthValue3`,
                `plans`.`monthly` AS `monthly`,
                `plans`.`quarterly` AS `quarterly`
            FROM
                `relation`
                LEFT JOIN `sells` ON `sells`.`seller` = `relation`.`person`
                LEFT JOIN `person` ON `person`.`id` = `relation`.`person`
                LEFT JOIN `groups` ON `groups`.`id` = `relation`.`group`
                LEFT JOIN `plans` ON `plans`.`seller_id` = `relation`.`person` AND `plans`.`groups_id` = `relation`.`group`
                LEFT JOIN (
                    SELECT
                        SUM(IF(SUBSTRING(`sells`.`date`, 6, 2) = :month0, `value`, 0)) AS `monthValue1`,
                        SUM(IF(SUBSTRING(`sells`.`date`, 6, 2) = :month1, `value`, 0)) AS `monthValue2`,
                        SUM(IF(SUBSTRING(`sells`.`date`, 6, 2) = :month2, `value`, 0)) AS `monthValue3`,
                        SUM(`value`) AS `yearValue`,
                        COALESCE(`ms`.`correctionMonthValue1`, 0) AS `correctionMonthValue1`,
                        COALESCE(`ms`.`correctionMonthValue2`, 0) AS `correctionMonthValue2`,
                        COALESCE(`ms`.`correctionMonthValue3`, 0) AS `correctionMonthValue3`,
                        COALESCE(`ms`.`correctionValue`, 0) AS `correctionValue`,
                        `sells`.`seller` AS `seller`
                    FROM
                        `sells`
                        LEFT JOIN (
                            SELECT
                                `manual_sells`.`seller` AS `seller`,
                                SUM(IF(SUBSTRING(`manual_sells`.`date`, 6, 2) = :month0, `value`, 0)) AS `correctionMonthValue1`,
                                SUM(IF(SUBSTRING(`manual_sells`.`date`, 6, 2) = :month1, `value`, 0)) AS `correctionMonthValue2`,
                                SUM(IF(SUBSTRING(`manual_sells`.`date`, 6, 2) = :month2, `value`, 0)) AS `correctionMonthValue3`,
                                SUM(`manual_sells`.`value`) AS `correctionValue`
                            FROM
                                `manual_sells`
                            WHERE
                                SUBSTRING(`manual_sells`.`date`, 1, 4) = SUBSTRING(:period, 1, 4)
                            GROUP BY
                                `manual_sells`.`seller`
                        ) AS `ms` ON `ms`.`seller` = `sells`.`seller`
                    WHERE
                        SUBSTRING(`sells`.`date`, 1, 4) = SUBSTRING(:period, 1, 4)
                    GROUP BY
                        `sells`.`seller`
                ) AS `ai` ON `ai`.`seller` = `relation`.`person`
            WHERE
                " . implode(' AND ', $where) . "
            ORDER BY
                `sells`.`value` DESC,
                `person`.`fio` ASC
        ", $cond);
        return $forRet->queryAll();
    }

    static public function getPlans($personId, $groupId)
    {
        $forRet = self::getDb()->createCommand("
            SELECT
                `p`.`id` AS `idPerson`,
                `p`.`fio` AS `fioPerson`,
                `p`.`login` AS `loginPerson`,
                `p`.`access_type` AS `accessType`,
                `at`.`name` AS `accessTypeName`,
                `pl`.`monthly` AS `monthly`,
                `pl`.`quarterly` AS `quarterly`
            FROM
              `person` AS `p`
              LEFT JOIN `access_type` AS `at` ON `at`.`id` = `p`.`access_type`
              LEFT JOIN `plans` AS `pl` ON `pl`.`seller_id` = `p`.`id`
            WHERE
              `p`.`id` = :personId AND
              `pl`.`groups_id` = :groupId
        ", ['personId' => $personId, 'groupId' => $groupId]);

        //var_dump($forRet->getRawSql()); die;
        return $forRet->queryAll();
    }

    static public function setPlans($personId, $groupId, $data)
    {
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            $cmd = $db->createCommand()->insert('plans', [
                'seller_id' => $personId,
                'groups_id' => $groupId,
                'monthly' => $data['monthlyPlan'],
                'quarterly' => $data['quarterlyPlan']
            ]);
            $q = $cmd->getRawSql();
            $cmd->cancel();
            $q .= " ON DUPLICATE KEY UPDATE `monthly` = VALUES(`monthly`), `quarterly` = VALUES(`quarterly`)";
            $db->createCommand($q)->execute();
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * Записать новую правку в таблицу
     *
     * @param $personId ид продавца
     * @param $managerId ид авторизированного пользователя
     * @param $value величина правки
     * @param $period период, за который вносятся правки
     * @param $comment комментарий к правкам
     * @return bool true, если успешно
     * @throws \yii\db\Exception
     */
    static public function addCorrection($personId, $managerId, $value, $period, $comment)
    {
        $db = self::getDb();
        $cmd = $db->createCommand()->insert('manual_sells', [
            'seller' => $personId,
            'manager' => $managerId,
            'value' => $value,
            'date' => $period,
            'comment' => $comment
        ]);
        if (!$cmd->execute()) {
            return false;
        }
        return true;
    }

    static public function getCorrections($sellerId = 0, $managerId = 0, $limit = 30)
    {
        $condArr = [];
        $paramsArr = [];
        if ($sellerId > 0) {
            $condArr[] = "`seller`.`id` = :sellerId";
            $paramsArr['sellerId'] = $sellerId;
        }
        if ($managerId > 0) {
            $condArr[] = "`manager`.`id` = :managerId";
            $paramsArr['managerId'] = $managerId;
        }
        $condArr[] = "`seller`.`access_type` = :accessType";
        $paramsArr['accessType'] = 1;
        return self::getDb()->createCommand("
            SELECT
              `seller`.`fio` AS `sellerName`,
              `manager`.`fio` AS `managerName`,
              `ms`.`seller` AS `sellerId`,
              `ms`.`id` AS `correctionId`,
              `ms`.`manager` AS `managerId`,
              `ms`.`value` AS `value`,
              `ms`.`date` AS `period`,
              `ms`.`comment` AS `comment`
            FROM
                `manual_sells` AS `ms`
                JOIN `person` AS `seller` ON `seller`.`id` = `ms`.`seller`
                JOIN `person` AS `manager` ON `manager`.`id` = `ms`.`manager`
            " . (count($condArr) ? "WHERE " . implode(" AND ", $condArr) : "") . "
            ORDER BY
                `ms`.`id` DESC
            LIMIT " . $limit . "
        ", $paramsArr)->queryAll();
    }

    /**
     * Осуществяет поиск пользователя по куску имени $term
     *
     * @param $term подстрока поиска
     * @param int $accessLevel уровень доступа, если 0, то любые
     * @return array массив найденных совпадений
     */
    static public function findUsersByName($term, $accessLevel = 0)
    {
        $condArr = [];
        $paramsArr = [];
        if ($term) {
            $condArr[] = "`person`.`fio` LIKE :term";
            $paramsArr['term'] = '%' . $term . '%';
        }
        if ($accessLevel) {
            $condArr[] = "`person`.`access_type` = :accessType";
            $paramsArr['accessType'] = $accessLevel;
        }
        return self::getDb()->createCommand("
            SELECT
              `person`.`fio` AS `personName`,
              `person`.`access_type` AS `accessType`,
              `person`.`id` AS `personId`
            FROM
                `person`
            " . (count($condArr) ? "WHERE " . implode(" AND ", $condArr) : "") . "
            ORDER BY
                `person`.`fio`
        ", $paramsArr)->queryAll();
    }

    static public function getPerson($id)
    {
        return self::getDb()->createCommand("
            SELECT
                `p`.`id` AS `idPerson`,
                `p`.`fio` AS `fioPerson`,
                `p`.`login` AS `loginPerson`,
                `p`.`access_type` AS `accessType`,
                `at`.`name` AS `accessTypeName`
            FROM
              `person` AS `p`
              LEFT JOIN `access_type` AS `at` ON `at`.`id` = `p`.`access_type`
            WHERE
              `p`.`id` = :id
        ", ['id' => $id])->queryAll();
    }

    static public function createPerson($data)
    {
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->insert('person', [
                'fio' => $data['fioPerson'],
                'login' => $data['loginPerson'],
                'password' => hash_hmac('md5', $data['passwordPerson'], Yii::$app->request->passwordSalt),
                'access_type' => $data['access_type']
            ])->execute();
            $transaction->commit();
            return $db->lastInsertID;
        } catch(Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    static public function updatePerson($id, $data)
    {
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            $forUpdateData = [
                'fio' => $data['fioPerson'],
                'login' => $data['loginPerson'],
                'access_type' => $data['access_type']
            ];
            if (!empty($data['passwordPerson'])) {
                $forUpdateData['password'] = hash_hmac('md5', $data['passwordPerson'], Yii::$app->request->passwordSalt);
            }
            $db->createCommand()->update('person', $forUpdateData, 'id=:id', ['id' => $id])->execute();
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    static public function createGroup($owner, $data) {
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            $db->createCommand()->insert('groups', [
                'group_name' => $data['nameGroup'],
                'group_type' => $data['typeGroup'],
                'owner' => $owner
            ])->execute();
            $groupId = $db->lastInsertID;
            $insertValues = [];
            if (isset($data['membersGroup']) && is_array($data['membersGroup'])) {
                foreach ($data['membersGroup'] as $personId) {
                    $insertValues[] = [$groupId, $personId];
                }
                $db->createCommand()->batchInsert('relation', ['group', 'person'], $insertValues)->execute();
            }
            $transaction->commit();
            return $groupId;
        } catch(Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    static public function updateGroup($groupId, $data)
    {
        if (!$groupId) {
            return false;
        }
        $db = self::getDb();
        $transaction = $db->beginTransaction();
        try {
            $affectedRows = $db->createCommand()->update('groups', ['group_name' => $data['nameGroup']], 'id=:id', ['id' => $groupId])->execute();
            if ($affectedRows > 1) {
                throw new Exception('Was changed ' . $affectedRows . ' groups.');
            }
            $insertValues = [];
            if (isset($data['membersGroup']) && is_array($data['membersGroup'])) {
                $db->createCommand()->delete('relation', ['group' => $groupId])->execute();
                if (!empty($data['membersGroup'])) {
                    foreach ($data['membersGroup'] as $personId) {
                        $insertValues[] = [$groupId, $personId];
                    }
                    $db->createCommand()->batchInsert('relation', ['group', 'person'], $insertValues)->execute();
                }
            }
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    static public function getPersons($groupId = null, $accessType = self::PERSON_ALL)
    {
        $condArr = [];
        $paramsArr = [];
        if ($groupId != null) {
            $condArr[] = "
                `p`.`id` IN (
                    SELECT
                      `person`
                    FROM
                      `relation`
                    WHERE
                      `group` = :groupId
                )
            ";
            $paramsArr['groupId'] = $groupId;
        }
        if ($accessType) {
            $condArr[] = "`p`.`access_type` = :accessType";
            $paramsArr['accessType'] = $accessType;
        }
        return self::getDb()->createCommand("
            SELECT
                `p`.`fio` AS `personName`,
                `p`.`id` AS `personId`,
                `p`.`access_type` AS `accessType`
            FROM
              `person` AS `p`
            " . (count($condArr) ? "WHERE " . implode(" AND ", $condArr) : "") . "
            ORDER BY
              `p`.`fio`
        ", $paramsArr)->queryAll();
    }

    static public function getFreePersons($accessType = self::PERSON_ALL)
    {
        $condArr = [];
        $paramsArr = [];
        if ($accessType) {
            $condArr[] = "`p`.`access_type` = :accessType";
            $paramsArr['accessType'] = $accessType;
        }
        $condArr[] = "`relation`.`person` IS NULL";
        return self::getDb()->createCommand("
            SELECT
                `p`.`fio` AS `personName`,
                `p`.`id` AS `personId`,
                `p`.`access_type` AS `accessType`
            FROM
              `person` AS `p`
              LEFT JOIN `relation` ON `relation`.`person` = `p`.`id`
            " . (count($condArr) ? "WHERE " . implode(" AND ", $condArr) : "") . "
            ORDER BY
              `p`.`fio`
        ", $paramsArr)->queryAll();
    }

    static public function getGroups($groupId = null, $accessLevel = self::PERSON_ADMIN) {
        $condArr = [];
        $paramsArr = [];
        if ($groupId) {
            $condArr[] = "`g`.`id` = :groupId";
            $paramsArr['groupId'] = intval($groupId);
        }
        return self::getDb()->createCommand("
            SELECT
              `g`.`id` AS `groupId`,
              `g`.`group_name` AS `groupName`,
              `g`.`group_type` AS `groupType`,
              if(`e`.`amountEmployees` IS NULL, 0, `e`.`amountEmployees`) AS `amountEmployees`,
              `p`.`fio` AS `ownerName`,
              `p`.`id` AS `ownerId`
            FROM
              `groups` AS `g`
              LEFT JOIN (
                SELECT
                  `r`.`group` AS `groupId`,
                  COUNT(*) AS `amountEmployees`
                FROM
                  `relation` AS `r`
                GROUP BY
                  `r`.`group`
              ) AS `e` ON `e`.`groupId` = `g`.`id`
              LEFT JOIN `person` AS `p` ON `p`.`id` = `g`.`owner`
            " . (count($condArr) ? "WHERE " . implode(" AND ", $condArr) : "") . "
            ORDER BY
              `g`.`group_name`
        ", $paramsArr)->queryAll();
    }

    static public function getSellsByInterval($startDate, $endDate, $groupId = null)
    {
        $where = [];
        $cond = [];
        if (!empty($groupId)) {
            $where[] = "`relation`.`group` = :groupId";
            $cond['groupId'] = $groupId;
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate)) {
            $where[] = "`sells`.`date` >= :startDate";
            $cond['startDate'] = $startDate;
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            $where[] = "`sells`.`date` <= :endDate";
            $cond['endDate'] = $endDate;
        }
        $forRet = self::getDb()->createCommand("
            SELECT
                `person`.`id` AS `personId`,
                `person`.`fio` AS `personName`,
                `person`.`access_type` AS `personAccessType`,
                `relation`.`group` AS `groupId`,
                `sells`.`date` AS `sellsPeriod`,
                `sells`.`value` AS `sellsValue`,
                `groups`.`group_type` AS `groupType`,
                `plans`.`monthly` AS `monthly`,
                `plans`.`quarterly` AS `quarterly`
            FROM
                `relation`
                LEFT JOIN `sells` ON `sells`.`seller` = `relation`.`person`
                LEFT JOIN `person` ON `person`.`id` = `relation`.`person`
                LEFT JOIN `groups` ON `groups`.`id` = `relation`.`group`
                LEFT JOIN `plans` ON `plans`.`seller_id` = `relation`.`person` AND `plans`.`groups_id` = `relation`.`group`
            WHERE
                " . implode(' AND ', $where) . "
            GROUP BY
                `person`.`id`,
                `sells`.`date`
            ORDER BY
                `person`.`fio` ASC,
                `sells`.`date` DESC
        ", $cond);
        return $forRet->queryAll();
    }
}