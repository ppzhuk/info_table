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
use app\models\Person;

class AdminController extends Controller
{
    public $accessMap = [
        'app\controllers\AdminController::actionLogin' => [0],
        'app\controllers\AdminController::actionLogout' => [1, 2, 3, 4],
        'app\controllers\AdminController::actionSellsTable' => [1, 2, 3, 4],
        'app\controllers\AdminController::actionIndex' => [2, 3, 4],
        'app\controllers\AdminController::actionManageUsers' => [4],
        'app\controllers\AdminController::actionManageGroups' => [2, 4],
        'app\controllers\AdminController::actionCalibration' => [2, 3, 4],
        'app\controllers\AdminController::actionSellsTable' => [1, 2, 3, 4],

        'app\controllers\AdminController::actionGetUserJson' => [1, 2, 3, 4],
        'app\controllers\AdminController::actionGetGroupJson' => [1, 2, 3, 4],
        'app\controllers\AdminController::actionGetSellsJson' => [1, 2, 3, 4],
        'app\controllers\AdminController::actionUpdateGroup' => [2, 4],
        'app\controllers\AdminController::actionCreateGroup' => [2, 4],
        'app\controllers\AdminController::actionUpdateUser' => [4],
        'app\controllers\AdminController::actionCreateUser' => [4],
    ];

    private $monthList = [
        ['январь'],
        ['февраль'],
        ['март'],
        ['апрель'],
        ['май'],
        ['июнь'],
        ['июль'],
        ['август'],
        ['сентябрь'],
        ['октябрь'],
        ['ноябрь'],
        ['декабрь'],
    ];

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

    private function checkAccess($_func, $redirect = true)
    {
        if (Yii::$app->user->isGuest && isset($this->accessMap[$_func]) && in_array(0, $this->accessMap[$_func])) {
            return true;
        }
        if (isset($this->accessMap[$_func]) && in_array(Person::$accessType, $this->accessMap[$_func])) {
            return true;
        }
        if (Person::$accessType == 1) {
            if ($redirect) {
                Yii::$app->getResponse()->redirect('?r=admin/sells-table');
            }
            return false;
        }
        if (Person::$accessType >= 2) {
            if ($redirect) {
                $this->goHome();
            }
            return false;
        }
        return Yii::$app->getResponse()->redirect('?r=admin/login');
    }

    public function actionIndex()
    {
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
        return $this->render('index', [
            'groups' => Groups::getGroups()
        ]);
    }

    public function actionLogin()
    {
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
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
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
        Yii::$app->user->logout();
        return $this->goHome();
    }

    public function actionManageUsers()
    {
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
        return $this->render('manage-users', [
            'persons' => Groups::getPersons(),
            'accessTypes' => AccessType::find()->orderBy('id')->all()
        ]);
    }

    public function actionManageGroups()
    {
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
        return $this->render('manage-groups', [
            'groups' => Groups::getGroups(),
            'otherPersons' => Groups::getPersons(null, Groups::PERSON_SELLER),
            'members' => []
        ]);
    }

    public function actionCalibration()
    {
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
        $groups = Groups::getGroups();
        $autocomplitePersons = [];
        foreach ($groups as $group) {
            $persons = Groups::getPersons($group['groupId'], Groups::PERSON_SELLER);
            foreach ($persons as $person) {
                $autocomplitePersons[] = [
                    'label' => $person['personName'] . ' из группы: ' . $group['groupName'] . '(' . $group['groupId'] . ')',
                    'personId' => $person['personId'],
                    'groupId' => $group['groupId'],
                ];
            }
        }
        return $this->render('calibration-table', [
            'personsAutocomplite' => $autocomplitePersons
        ]);
    }

    public function actionSellsTable()
    {
        Yii::$app->cache->flush();
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }

        $this->layout = 'tables';
        $group = Groups::getGroups(Yii::$app->request->get('groupId'));
        if (count($group) != 1) {
            return $this->render('choice-group',[
                'groups' => Groups::getGroups()
            ]);
        }
        $period = '2016-03-01';
        $startMonth = intval(substr($period, 6, 2));
        $startMonth -= ($startMonth - 1) % 3;
        $monthNames = [];
        for ($i = 0; $i < 3; $i++) {
            $monthNames[$i] = $this->monthList[$startMonth + $i - 1][0];
        }
        if ($group[0]['groupType'] == 'seller') {
            return $this->render('sellers-table-style2', [
                'groupId' =>  $group[0]['groupId'],
                'groupName' => $group[0]['groupName'],
                'period' => $period,
                'periodText' => $this->monthList[intval(date('m'))][0] . date(' Y'),
                'sells' => Groups::getSells($group[0]['groupId'], $period)
            ]);
        }
        if ($group[0]['groupType'] == 'KAM') {
            return $this->render('kam-table', [
                'groupId' =>  $group[0]['groupId'],
                'groupName' => $group[0]['groupName'],
                'period' => $period,
                'periodText' => $this->monthList[intval(date('m'))][0] . date(' Y'),
                'sells' => Groups::getSells($group[0]['groupId'], $period),
                'monthNames' => $monthNames,
            ]);
        }
        throw new Exception('Что-то пошло не так.');
    }

    // Служебные функции

    public function actionCreateUser()
    {
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
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
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
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
        if ($this->checkAccess(__METHOD__, false) !== true) {
            return;
        }
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
        if ($this->checkAccess(__METHOD__, false) !== true) {
            return;
        }
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
    public function actionSavePlanJson()
    {
        //$id = Yii::$app->request->post('groupId');
        $data = ['code' => -1];
        echo json_encode($data);
        exit;
    }

    public function actionGetGroupJson()
    {
        if ($this->checkAccess(__METHOD__, false) !== true) {
            return;
        }
        $id = Yii::$app->request->post('groupId');
        $data = Groups::getGroups($id);
        $data['members'] = Groups::getPersons($id, Groups::PERSON_SELLER);
        $data['otherPersons'] = array_udiff(Groups::getPersons(null, Groups::PERSON_SELLER), $data['members'], function($a, $b) {
            if ($a['personId'] == $b['personId']) {
                return 0;
            } elseif ($a['personId'] < $b['personId']) {
                return -1;
            } elseif ($a['personId'] > $b['personId']) {
                return 1;
            }
        });

        echo json_encode($data);
        exit;
    }

    public function actionGetUserJson()
    {
        if ($this->checkAccess(__METHOD__, false) !== true) {
            return;
        }
        $id = Yii::$app->request->post('userId');
        $data = Groups::getPerson($id);

        echo json_encode($data);
        exit;
    }

    public function actionGetSellerPlanJson()
    {
        $groupId = Yii::$app->request->post('groupId');
        $personId = Yii::$app->request->post('personId');
        $data = Groups::getPlans($personId, $groupId);
/*        foreach ($data as $key => $rec) {
            $data[$key]['sellsValue'] = number_format($rec['sellsValue'], 2, '.', ' ');
        }*/
        echo json_encode($data);
        exit;
    }

    public function actionGetSellsJson()
    {
        Groups::randomFill();
        if ($this->checkAccess(__METHOD__, false) !== true) {
            return;
        }
        $id = Yii::$app->request->post('groupId');
        $period = Yii::$app->request->post('period');
        $data = Groups::getSells($id, $period);
        foreach ($data as $key => $rec) {
            $data[$key]['sellsValue'] = number_format($rec['sellsValue'], 0, '.', ' ');
            $data[$key]['monthValue1'] = number_format($rec['monthValue1'], 0, '.', ' ');
            $data[$key]['monthValue2'] = number_format($rec['monthValue2'], 0, '.', ' ');
            $data[$key]['monthValue3'] = number_format($rec['monthValue3'], 0, '.', ' ');
            $data[$key]['yearValue'] = number_format($rec['yearValue'], 0, '.', ' ');
            $data[$key]['quarterly'] = number_format($rec['quarterly'], 0, '.', ' ');
            $data[$key]['monthly'] = number_format($rec['monthly'], 0, '.', ' ');
        }
        echo json_encode($data);
        exit;
    }
}