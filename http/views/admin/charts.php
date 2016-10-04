<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

$this->title = 'Графики';
?>

<script>
    var _csrf = "<?=Yii::$app->request->getCsrfToken(); ?>";
    var page = 'charts';
    var startDate = "<?=$startDate; ?>";
    var endDate = "<?=$endDate; ?>";
    var groupId = "<?=$groupId; ?>";
    var unionMode = "<?=$unionMode; ?>";
</script>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h2>Графики</h2>
            <form class="form-horizontal" action="" method="get">
                <input type="hidden" name="r" value="admin/charts"/>
                <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                <table class="form-width-full">
                    <tr>
                        <td>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Групировать</label>

                                <div class="col-md-10">
                                    <select  name="unionMode" class="form-control">
                                        <option value="0" <?=$unionMode == 0 ? 'selected' : ''; ?>>по группам</option>
                                        <option value="1" <?=$unionMode == 1 ? 'selected' : ''; ?>>по продавцам</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Группа</label>

                                <div class="col-md-10">
                                    <select name="groupId" class="form-control">
                                        <option value="0">не выбрано</option>
                                        <?php foreach ($groups as $group): ?>
                                            <option value="<?=$group['groupId']; ?>" <?=$groupId == $group['groupId'] ? 'selected' : ''; ?>><?=$group['groupName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr><tr>
                        <td>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Дата начала</label>

                                <div class="col-md-10">
                                    <input type="text" class="form-control month-picker" name="startDate" placeholder="Дата начала" value="<?=$startDate; ?>"/>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Дата окончания</label>

                                <div class="col-md-10">
                                    <input type="text" class="form-control month-picker" name="endDate" placeholder="Дата окончания" value="<?=$endDate; ?>"/>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <div class="form-group pull-right">
                                <button type="submit" class="btn btn-default" name="export">Выгрузить в excel</button>
                                <button type="submit" class="btn btn-primary">Вывести график<div class="ripple-container"></div></button>
                            </div>
                        </td>
                    </tr>
                </table>
                <div id="chart" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
            </form>
        </div>
    </div>
</div>
