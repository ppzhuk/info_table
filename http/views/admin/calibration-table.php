<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

$this->title = 'Таблица правок';
?>

<script>
    var _csrf = "<?=Yii::$app->request->getCsrfToken(); ?>";

</script>
<div class="container">
    <label class="label-black">
        <strong>Внимание!</strong> Внесённые правки нельзя будет удалить, поэтому проверяйте данные перед внесением тщательно.
        <br>Если требуется отнять некоторую сумму, то введите её со знаком минус(-).
    </label>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h3>Новая правка</h3>
            <form action="?r=admin%2Fcalibration" method="POST" data-presubmit="Вы дейстивтельно хотите внести данную правку?">
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <table class="table table-calibration table-striped table-hover ">
                    <colgroup width="10%">
                    <colgroup width="35%">
                    <colgroup width="10%">
                    <colgroup width="10%">
                    <colgroup width="35%">
                    <thead>
                    <tr>
                        <th>ID Продавца</th>
                        <th>Имя продавца</th>
                        <th>Сумма</th>
                        <th>Период</th>
                        <th>Комментарий</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr class="trow">
                            <td><input type="text" class="form-control" name="personId" readonly=""/></td>
                            <td><input type="text" class="form-control person-autocompleter" placeholder="Начните вводить имя продавца"/></td>
                            <td><input type="number" class="form-control price-control" name="sumValue" placeholder="Сумма"/></td>
                            <td><input type="text" class="form-control month-picker" name="period" placeholder="Дата"/></td>
                            <td><input type="text" class="form-control" name="comment" placeholder="Ваш комментарий"/></td>
                        </tr>
                    </tbody>
                </table>
                <div style="text-align: right">
                    <input type="submit" class="btn btn-raised btn-primary btn-sm" name="addCalibration" value="Добавить правку"/>
                </div>
            </form>
            <h3>Архив правок</h3>
            <table class="table table-striped table-hover">
                <colgroup width="4%">
                <colgroup width="28%">
                <colgroup width="28%">
                <colgroup width="10%">
                <colgroup width="10%">
                <colgroup width="20%">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя менеджера</th>
                    <th>Имя продавца</th>
                    <th>Сумма</th>
                    <th>Период</th>
                    <th>Комментарий</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($correctionsTable AS $correction): ?>
                    <tr class="<?=($correction['value'] > 0 ? 'success' : 'danger'); ?>">
                        <td><?=$correction['correctionId']; ?></td>
                        <td><?=$correction['managerName']; ?></td>
                        <td><?=$correction['sellerName']; ?></td>
                        <td><?=$correction['value']; ?></td>
                        <td><?=$correction['period']; ?></td>
                        <td><?=$correction['comment']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
