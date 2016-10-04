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
        'app\controllers\AdminController::actionCharts' => [2, 3, 4],
        'app\controllers\AdminController::actionManageUsers' => [4],
        'app\controllers\AdminController::actionManageGroups' => [2, 4],
        'app\controllers\AdminController::actionCalibration' => [2, 3, 4],
        'app\controllers\AdminController::actionSellsTable' => [1, 2, 3, 4],

        'app\controllers\AdminController::actionGetUserJson' => [1, 2, 3, 4],
        'app\controllers\AdminController::actionGetGroupJson' => [1, 2, 3, 4],
        'app\controllers\AdminController::actionGetSellsJson' => [1, 2, 3, 4],
        'app\controllers\AdminController::actionGetChartsJson' => [2, 3, 4],
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
            'otherPersons' => Groups::getFreePersons(null, Groups::PERSON_SELLER),
            'members' => []
        ]);
    }

    public function actionCalibration()
    {
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
        if (Yii::$app->request->post('addCalibration')) {
            $personID = intval(Yii::$app->request->post('personId'));
            $value = floatval(Yii::$app->request->post('sumValue'));
            $period = Yii::$app->request->post('period');
            $comment = Yii::$app->request->post('comment');
            if ($personID > 0 && $value != 0 && preg_match('/^\d{4}-\d{2}-01$/i',$period)) {
                Groups::addCorrection(
                    $personID,
                    Person::$id,
                    $value,
                    $period,
                    $comment
                );
            }
        }
        return $this->render('calibration-table', [
            'correctionsTable' => Groups::getCorrections(0)
        ]);
    }

    public function actionCharts()
    {
        if ($this->checkAccess(__METHOD__) !== true) {
            return;
        }
        $startDate = isset($_GET['startDate']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['startDate']) ? $_GET['startDate'] : date('Y-m-01');
        $endDate = isset($_GET['endDate']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['endDate']) ? $_GET['endDate'] : date('Y-m-01');
        $groupId = isset($_GET['groupId']) ? intval($_GET['groupId']) : 0;
        $unionMode = isset($_GET['unionMode']) ? intval($_GET['unionMode']) : 0;
        if (isset($_GET['export'])) {
            return $this->actionGetExcel($unionMode, $groupId, $startDate, $endDate);
        } else {
            return $this->render('charts',
                [
                    'startDate' => $startDate,
                    'endDate' => $endDate,
                    'groupId' => $groupId,
                    'unionMode' => $unionMode,
                    'groups' => Groups::getGroups(),
                ]
            );
        }
    }

    private function actionGetExcel($unionMode, $groupId, $startDate, $endDate)
    {
        $objPhpExcel = new \PHPExcel();
        $objPhpExcel->getProperties()
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objWorkSheet = $objPhpExcel->getActiveSheet();

        $data = Groups::getSellsByInterval($startDate, $endDate, $groupId);
        $group = $groupId ? Groups::getGroups($groupId)[0] : null;
        $groups = Groups::getGroups();
        $prepareData = [];
        $processedData = [];

        switch ($unionMode) {
            case 0: {
                $groupNames = [];
                foreach ($groups as $group) {
                    $groupNames[$group['groupId']] = $group['groupName'];
                }
                foreach ($data as $sellsData) {
                    if (!isset($prepareData[$sellsData['sellsPeriod']])) {
                        $prepareData[$sellsData['sellsPeriod']] = [];
                    }
                    if (!isset($prepareData[$sellsData['sellsPeriod']][$sellsData['groupId']])) {
                        $prepareData[$sellsData['sellsPeriod']][$sellsData['groupId']] = 0;
                    }
                    $prepareData[$sellsData['sellsPeriod']][$sellsData['groupId']] += $sellsData['sellsValue'];
                }
                $row = 1;
                foreach ($prepareData as $period => $sellsData) {
                    if ($row == 1) {
                        $col = 1;
                        foreach ($sellsData as $groupId => $value) {
                            $objPhpExcel->getActiveSheet()->getColumnDimensionByColumn($col)->setWidth(20);
                            $objWorkSheet->setCellValueByColumnAndRow($col, $row, $groupNames[$groupId]);
                            $col++;
                        }
                        $objPhpExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth(20);
                        $objWorkSheet->setCellValueByColumnAndRow(0, 1, 'Период/Группа');
                        $row++;
                    }
                    $col = 0;
                    $objWorkSheet->setCellValueByColumnAndRow($col, $row, $period);
                    $row++;
                }

                $row = 2;
                foreach ($prepareData as $period => $sellsData) {
                    $col = 1;
                    foreach ($sellsData as $groupId => $value) {
                        $objWorkSheet->setCellValueByColumnAndRow($col, $row, $value);
                        $col++;
                    }
                    $row++;
                }
                //$objWorkSheet->setCellValue('A1', '1 этаж');
            }
            case 1: {

            }
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="export.xlsx"');
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPhpExcel, 'Excel2007');
        $objWriter->save('php://output');
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
        $period = Yii::$app->request->get('period');
        if (!preg_match('/^\d{4}-\d{2}-01$/i', $period)) {
            $period = date('Y-m-d') ;
        }
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
                'periodText' => $this->monthList[intval(date('m', strtotime($period))) - 1][0] . date(' Y', strtotime($period)),
                'sells' => Groups::getSells($group[0]['groupId'], $period)
            ]);
        }
        if ($group[0]['groupType'] == 'KAM') {
            return $this->render('kam-table', [
                'groupId' =>  $group[0]['groupId'],
                'groupName' => $group[0]['groupName'],
                'period' => $period,
                'periodText' => $this->monthList[intval(date('m', strtotime($period))) - 1][0] . date(' Y', strtotime($period)),
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
        $data['membersGroup'] = ($data['membersGroup'] == null ? [] : $data['membersGroup']);
        $errCode = 0;
        if (!Groups::updateGroup(Yii::$app->request->post('groupId'), $data)) {
            $errCode = -1;
        }
        header('Location: ?r=admin%2Fmanage-groups&errcode=' . $errCode, true, 301);
        exit;
    }

    // Далее идут ajax функции
    public function actionFindSellers($term)
    {
        $autocomplitePersons = [];
        $sellers = Groups::findUsersByName($term, Groups::PERSON_SELLER);
        foreach ($sellers as $seller) {
            $autocomplitePersons[] = [
                'label' => $seller['personName'],
                'personId' => $seller['personId']
            ];
        }
        echo json_encode($autocomplitePersons);
        exit;
    }

    public function actionSavePlanJson()
    {
        $groupId = Yii::$app->request->post('groupId');
        $personId = Yii::$app->request->post('personId');
        $data = [
            'monthlyPlan' => Yii::$app->request->post('monthlyValue'),
            'quarterlyPlan' => Yii::$app->request->post('quarterlyValue')
        ];
        if (Groups::setPlans($personId, $groupId, $data)) {
            $data = ['code' => 0];
        } else {
            $data = ['code' => -1];
        }
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
        $data['otherPersons'] = Groups::getFreePersons(Groups::PERSON_SELLER);

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
        //Groups::randomFill();
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

    public function actionGetChartsJson()
    {
        if ($this->checkAccess(__METHOD__, false) !== true) {
            return;
        }
/*        $id = Yii::$app->request->post('userId');
        $data = Groups::getPerson($id);*/
        $startDate = isset($_GET['startDate']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['startDate']) ? $_GET['startDate'] : date('Y-m-01');
        $endDate = isset($_GET['endDate']) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $_GET['endDate']) ? $_GET['endDate'] : date('Y-m-01');
        $groupId = isset($_GET['groupId']) ? intval($_GET['groupId']) : 0;
        $unionMode = isset($_GET['unionMode']) ? intval($_GET['unionMode']) : 0;
        $data = Groups::getSellsByInterval($startDate, $endDate, $groupId);
        $group = $groupId ? Groups::getGroups($groupId)[0] : null;
        $groups = Groups::getGroups();
        $prepareData = [];
        $processedData = [];
        // Если группируем по группам
        if ($unionMode == 0) {
            $groupNames = [];
            foreach ($groups as $group) {
                $groupNames[$group['groupId']] = $group['groupName'];
            }
            foreach ($data as $item) {
                if (!isset($prepareData[$item['groupId']])) {
                    $prepareData[$item['groupId']] = [];
                }
                $date = strtotime($item['sellsPeriod']) . '000';
                if (!isset($prepareData[$item['groupId']][$date])) {
                    $prepareData[$item['groupId']][$date] = 0;
                }
                $prepareData[$item['groupId']][$date] += floatval($item['sellsValue']);
            }
            foreach ($prepareData as $id => $data) {
                $tmpData = [];
                foreach ($data as $date => $value) {
                    $tmpData[] = [$date, $value];
                }
                $processedData[] = [
                    'name' => $groupNames[$id],
                    'data' => $tmpData,
                ];
            }
            $title = $groupId != 0 ? 'График с группировкой по группам(' . $groupNames[$groupId] . ')' : 'График с группировкой по всем группам';
            $subtitle = 'За период с  ' . $startDate . ' по ' . $endDate;
        }
        // Если группируем по продавцам
        if ($unionMode == 1) {
            foreach ($data as $item) {
                if (!isset($prepareData[$item['personName']])) {
                    $prepareData[$item['personName']] = [];
                }
                $prepareData[$item['personName']][] = [intval(strtotime($item['sellsPeriod']) . '000'), floatval($item['sellsValue'])];
            }
            foreach ($prepareData as $sellerName => $sellsData) {
                $processedData[] = [
                    'name' => $sellerName,
                    'data' => $sellsData,
                ];
            }
            $title = $group ? 'График с группировкой по продавцам группы ' . $group['groupName'] : 'График с группировкой по продавцам всех групп';
            $subtitle = 'За период с  ' . $startDate . ' по ' . $endDate;
        }

        echo json_encode([
            'series' => $processedData,
            'title' => $title,
            'subtitle' => $subtitle,
        ]);
        exit;
    }
}