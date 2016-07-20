<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

$this->title = 'Таблица правок';
?>

<script>
    var _csrf = "<?=Yii::$app->request->getCsrfToken(); ?>";

    var personSource = <?=json_encode($personsAutocomplite); ?>

</script>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h2>Таблица правок</h2>
            <table class="table table-calibration table-striped table-hover ">
                <thead>
                <tr>
                    <th>ID Группы</th>
                    <th>ID Продавца</th>
                    <th>Имя продавца</th>
                    <th>Сумма</th>
                    <th>Период</th>
                    <th>Комментарий</th>
                </tr>
                </thead>
                <tbody>
<!--                <?php /*foreach($groups as $group): */?>
                    <tr>
                        <td><?/*=$group['groupName']; */?></td>
                        <td><?/*=$group['amountEmployees']; */?></td>
                        <td><?/*=$group['groupType']; */?></td>
                        <td><?/*=$group['ownerName']; */?></td>
                    </tr>
                --><?php /*endforeach; */?>
                    <tr class="trow">
                        <td><input class="form-control" name="personId" disabled/></td>
                        <td><input class="form-control" name="groupId" disabled/></td>
                        <td><input class="form-control person-autocompleter" placeholder="Начните вводить имя продавца или группу"/></td>
                        <td><input class="form-control" placeholder="Сумма"/></td>
                        <td><input class="form-control" placeholder="Дата"/></td>
                        <td><input class="form-control" placeholder="Ваш комментарий"/></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
