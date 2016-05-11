<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

namespace app\models;

use yii\base\Exception;
use yii\db\ActiveRecord;

class Groups extends ActiveRecord
{
    const PERSON_ALL = 0;
    const PERSON_SELLER = 1;
    const PERSON_MANAGER = 2;
    const PERSON_HEAD_OF_DEPARTMENT = 3;
    const PERSON_ADMIN = 4;

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
            foreach ($data['membersGroup'] as $personId) {
                $insertValues[] = [$groupId, $personId];
            }
            $db->createCommand()->batchInsert('relation', ['group', 'person'], $insertValues)->execute();
            $transaction->commit();
            return $groupId;
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