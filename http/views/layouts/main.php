<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Person;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <div class="row">
            <div class="col-md-12">
                <h2>Alerts</h2>

                <div class="bs-component">
                    <div id="main-alert" class="alert alert-dismissible">
                        <h4 id="alert-title"></h4>
                        <p id="alert-content"></p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        //var_dump(Yii::$app->user->isGuest); die;
        NavBar::begin([
            'brandLabel' => 'Ростелеком',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
        $items = [];
        switch (Person::$accessType) {
            case 4:
            case 3: {
                $items[] = ['label' => 'Пользователи', 'url' => ['/admin/manage-users']];
                $items[] = ['label' => 'Группы', 'url' => ['/admin/manage-groups']];
                $items[] = ['label' => 'Правки', 'url' => ['/admin/calibration']];
                $items[] = ['label' => 'Графики', 'url' => ['/admin/charts']];
            }
            case 2: {
                $items[] = ['label' => 'Рейтинговая таблица', 'url' => ['/admin/sells-table']];
            }
            case 1:
            case 0:
        }
        $items[] = Yii::$app->user->isGuest ? (
        ['label' => 'Войти', 'url' => ['/admin/login']]
        ) : (
            '<li>'
            . Html::beginForm(['/admin/logout'], 'post')
            . Html::submitButton(
                'Выйти (' . Person::$fullName . ')',
                [
                    'class' => 'btn btn-link',
                    'style' => 'text-transform: none'
                ]
            )
            . Html::endForm()
            . '</li>'
        );
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav navbar-right'],
            'items' => $items
        ]);
        NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Ростелеком <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>