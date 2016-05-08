<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Groups;

class AdminController extends Controller
{
    public function actionIndex()
    {
/*        $listGroups;
        var_dump($listGroups); die;*/

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
}