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

    static public function getPlans($personId, $groupId)
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
              LEFT JOIN `plans` AS `pl` ON `pl`.`seller_id` = `p`.`id`
            WHERE
              `p`.`id` = :personId AND
              `pl`.`groups_id` = :groupId
        ", ['personId' => $personId, 'groupId' => $groupId])->queryAll();
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
                foreach ($data['membersGroup'] as $personId) {
                    $insertValues[] = [$groupId, $personId];
                }
                $db->createCommand()->delete('relation', ['group' => $groupId])->execute();
                $db->createCommand()->batchInsert('relation', ['group', 'person'], $insertValues)->execute();
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
        ", $paramsArr)->queryAll();
    }
}