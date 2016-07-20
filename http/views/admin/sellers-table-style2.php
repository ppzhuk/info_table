<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */
use app\models\Person;

$this->title = 'Список групп';
$place = 1;
$step = 40;
$offset = 0;

?>

<script>
    var _csrf = "<?=Yii::$app->request->getCsrfToken(); ?>";
    var groupId = <?=$groupId; ?>;
    var period = "<?=$period; ?>";
    var offset = <?=$offset; ?>;
</script>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row head-line">
                <img src="img/logo-rt.png" height="55px"/>
                <div class="pull-right">
                    <form action="/web/index.php?r=admin%2Flogout" method="post">
                        <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                        <input type="hidden" name="groupId" value="<?=$groupId; ?>" />
                        <input type="hidden" name="period" value="<?=$period; ?>" />
                        <span class="pull-right"><?=Person::$fullName; ?></span>
                        <br>
                        <span class="pull-right">
                            <button type="submit" class="btn-link" style="text-transform: none">Выйти</button>|
                            <a href="?r=admin/index" class="btn-link" style="text-transform: none">Админ. панель</a>
                        </span>
                    </form>
                </div>
            </div>
            <div class="row">
                <h2 align="center">Исполнение планов доходов за <?=$periodText; ?></h2>
                <h4 align="center">"<?=$groupName; ?>"</h4>
                <div class="table table-ratings">
                    <div class="row thead">
                        <div class="col-lg-1 col-md-1 col-sm-1" style="text-align: center">Место</div>
                        <div class="col-lg-5 col-md-5 col-sm-5">Полное имя</div>
                        <div class="col-lg-2 col-md-2 col-sm-2">Доход за месяц</div>
                        <div class="col-lg-2 col-md-2 col-sm-2">План за месяц</div>
                        <div class="col-lg-2 col-md-2 col-sm-2">Доход за год</div>
                    </div>
                    <?php foreach($sells as $sell): ?>
                        <?php
                        if ($place <= 3){
                            $size = 50 - 8 * $place;
                            $placeHtml = '<img src="img/star.png" width="' . $size . 'px"/>';
                        } else {
                            $placeHtml = $place;
                        }
                        ?>
                        <div class="row tbody" data-position="<?=$place; ?>" id="row<?=$sell['personId']; ?>" style="top: <?=$offset + $step*$place; ?>px; z-index: <?=99-$place; ?>">
                            <div class="place col-lg-1 col-md-1 col-sm-1" style="text-align: center"><?=$placeHtml; ?></div>
                            <div class="col-lg-5 col-md-5 col-sm-5"><?=$sell['personName']; ?></div>
                            <div class="sells-value col-lg-2 col-md-2 col-sm-2"><?=number_format($sell['sellsValue'], 2, '.', ' '); ?></div>
                            <div class="sells-plan-monthly col-lg-2 col-md-2 col-sm-2"><?=number_format($sell['monthly'], 2, '.', ' '); ?></div>
                            <div class="sells-year-value col-lg-2 col-md-2 col-sm-2"><?=number_format($sell['yearValue'], 2, '.', ' '); ?></div>
                        </div>
                        <?php $place++; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    /*    $(function(){

     });*/
</script>