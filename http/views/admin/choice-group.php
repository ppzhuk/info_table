<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

$this->title = 'Редактирование групп';
?>

<script>
    var _csrf = "<?=Yii::$app->request->getCsrfToken(); ?>";
</script>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <form class="jumbotron">
                <h2>Таблица доходов</h2>
                <form class="form-horizontal" method="get" action="">
                    <input type="hidden" name="r" value="admin/sells-table"/>
                    <h3>Выберите группу</h3>

                    <div class="form-group">
                        <label for="groupId" class="col-md-2 control-label">Группа</label>

                        <div class="col-md-10">
                            <select name="groupId" class="form-control">
                                <?php foreach($groups as $group): ?>
                                    <option value="<?=$group['groupId']; ?>"><?=$group['groupName']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <p>
                        <input type="submit" class="btn btn-primary btn-sm" value="Выбрать"/>
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>
