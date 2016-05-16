<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

namespace app\controllers;

use app\models\AccessType;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use app\models\Groups;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class AdminController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'groups' => Groups::getGroups()
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionManageUsers()
    {
        return $this->render('manage-users', [
            'persons' => Groups::getPersons(),
            'accessTypes' => AccessType::find()->orderBy('id')->all()
        ]);
    }

    public function actionManageGroups()
    {
        //Groups::setPlans(1, 1, ['quarterlyPlan' => '120', 'monthlyPlan' => 532]);
        //var_dump(Groups::getPerson(1)); die;
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

    public function actionCreateUser()
    {
        $data = [
            'loginPerson' => Yii::$app->request->post('loginUser'),
            'fioPerson' => Yii::$app->request->post('nameUser'),
            'access_type' => Yii::$app->request->post('accessType'),
            'passwordPerson' => Yii::$app->request->post('password'),
        ];
        $errCode = 0;
        if (!Groups::createPerson($data)) {
            $errCode = -1;
        }
        header('Location: ?r=admin%2Fmanage-users&errcode=' . $errCode, true, 301);
        exit;
    }

    public function actionUpdateUser()
    {
        $data = [
            'loginPerson' => Yii::$app->request->post('loginUser'),
            'fioPerson' => Yii::$app->request->post('nameUser'),
            'access_type' => Yii::$app->request->post('accessType'),
            'passwordPerson' => Yii::$app->request->post('password'),
        ];
        $errCode = 0;
        if (!Groups::updatePerson(Yii::$app->request->post('userId'), $data)) {
            $errCode = -1;
        }
        header('Location: ?r=admin%2Fmanage-users&errcode=' . $errCode, true, 301);
        exit;
    }

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

    public function actionUpdateGroup()
    {
        $data = [
            'nameGroup' => Yii::$app->request->post('nameGroup'),
            'membersGroup' => Yii::$app->request->post('membersGroup')
        ];
        $errCode = 0;
        if (!Groups::updateGroup(Yii::$app->request->post('groupId'), $data)) {
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

    public function actionGetUserJson()
    {
        $id = Yii::$app->request->post('userId');
        $data = Groups::getPerson($id);

        echo json_encode($data);
        exit;
    }
}