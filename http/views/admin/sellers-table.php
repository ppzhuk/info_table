<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */
use app\models\Person;

$this->title = 'Список групп';
?>

<script>
    var _csrf = "<?=Yii::$app->request->getCsrfToken(); ?>";
    var groupId = <?=$groupId; ?>;
    var period = "<?=$period; ?>";
</script>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">
                <h2 class="pull-left">Рейтинг продавцов группы "<?=$groupName; ?>"</h2>
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
                <div class="table table-ratings">
                    <div class="row">
                        <div class="col-lg-1 col-md-1 col-sm-1">Место</div>
                        <div class="col-lg-5 col-md-5 col-sm-5">Полное имя</div>
                        <div class="col-lg-3 col-md-3 col-sm-3">Доход за месяц</div>
                        <div class="col-lg-3 col-md-3 col-sm-3">План за месяц</div>
                    </div>
                    <?php
                    $place = 1;
                    $step = 40;
                    $offset = 0;
                    ?>
                    <?php foreach($sells as $sell): ?>
                        <?php
                        if ($place <= 3){
                            $placeHtml = $place . '<img src="img/' . $place . 'Place.png"/>';
                        } else {
                            $placeHtml = $place;
                        }
                        ?>
                        <div class="row" data-position="<?=$place; ?>" id="row<?=$sell['personId']; ?>" style="top: <?=$offset + $step*$place; ?>px; z-index: <?=99-$place; ?>">
                            <div class="place col-lg-1 col-md-1 col-sm-1"><?=$placeHtml; ?></div>
                            <div class="col-lg-5 col-md-5 col-sm-5"><?=$sell['personName']; ?></div>
                            <div class="sells-value col-lg-3 col-md-3 col-sm-3"><?=$sell['sellsValue']; ?></div>
                            <div class="sells-plan col-lg-3 col-md-3 col-sm-3">0</div>
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