<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use app\models\Groups;

class AdminController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index', [
            'groups' => Groups::getGroups()
        ]);
    }

    public function actionManageGroups()
    {
        return $this->render('manage-groups', [
            'groups' => Groups::getGroups(),
            'otherPersons' => Groups::getPersons(null, Groups::PERSON_SELLER),
            'members' => []
        ]);
    }

    public function actionSellsTable()
    {
        $this->layout = 'tables';
        $group = Groups::getGroups(Yii::$app->request->get('groupId'));
        //var_dump($group);
        if (count($group) != 1) {
            return $this->render('choice-group',[
                'groups' => Groups::getGroups()
            ]);
        }
        $persons = Groups::getPersons($group[0]['groupId'], Groups::PERSON_SELLER);
        //var_dump($persons);
        if ($group[0]['groupType'] == 'seller') {
            return $this->render('sellers-table', [
                'groupId' =>  $group[0]['groupId'],
                'persons' => $persons
            ]);
        }
        if ($group[0]['groupType'] == 'KAM') {
            return $this->render('kam-table', [
                'groupId' =>  $group[0]['groupId']
            ]);
        }
        throw new Exception('Что-то пошло не так.');
    }

    // Служебные функции

    public function actionCreateGroup()
    {
        $data = [
            'nameGroup' => Yii::$app->request->post('nameGroup'),
            'typeGroup' => Yii::$app->request->post('typeGroup'),
            'membersGroup' => Yii::$app->request->post('membersGroup')
        ];
        $errCode = 0;
        if (!Groups::createGroup(16, $data)) {
            $errCode = -1;
        }
        header('Location: ?r=admin%2Fmanage-groups&errcode=' . $errCode, true, 301);
        exit;
    }

    // Далее идут ajax функции
    public function actionGetGroupJson()
    {
        $id = Yii::$app->request->post('groupId');
        $data = Groups::getGroups($id);
        $data['members'] = Groups::getPersons($id, Groups::PERSON_SELLER);
        $data['otherPersons'] = array_udiff(Groups::getPersons(null, Groups::PERSON_SELLER), $data['members'], function($a, $b) {
            if ($a == $b) {
                return 0;
            }
            return -1;
        });

        echo json_encode($data);
        exit;
    }
}