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
            <div class="jumbotron">
                <h2>Управление группами</h2>
                <form data-frame="frame1" class="form-horizontal hide" method="post" action="?r=admin%2Fupdate-group">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                    <input type="hidden" name="groupId" value="0" />
                    <fieldset>
                        <legend>Редактирование группы</legend>
                        <div class="form-group lockable">
                            <label class="col-md-2 control-label">Тип группы</label>

                            <label class="col-md-10 form-label" name="typeGroup">
                                -
                            </label>
                        </div>
                        <div class="form-group lockable">
                            <label for="nameGroup" class="col-md-2 control-label">Имя группы</label>

                            <div class="col-md-10">
                                <input type="text" name="nameGroup" class="form-control" placeholder="навзвание группы">
                            </div>
                        </div>
                        <div class="form-group lockable">
                            <label for="inputEmail" class="col-md-2 control-label">Сотрудники</label>

                            <div class="col-md-10">
                                <div class="col-md-6">
                                    <label for="membersGroup[]">Члены группы*</label>
                                    <select name="membersGroup[]" data-select1="members2" multiple="" class="form-control" style="height: 200px">

                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="otherPersons[]">Все остальные*</label>
                                    <select name="otherPersons[]" data-select2="members2" multiple="" class="form-control" style="height: 200px">

                                    </select>
                                </div>
                            </div>
                            <span class="label">* - Чтобы добавить или удалить человека из группы, кликните два раза по выбранной фамилии в соответствующем списке.</span>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-md-2 control-label">Ежемесячный план</label>

                            <div class="col-md-10">
                                <input type="number" name="monthlyPlan" class="form-control" placeholder="0.00" disabled="disabled">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-md-2 control-label">Квартальный план</label>

                                <div class="col-md-10">
                                    <input type="number" name="quarterlyPlan" class="form-control" placeholder="0.00" disabled="disabled">
                                </div>
                        </div>
                        <input type="button" class="btn btn-warning btn-sm lockable btn-submit" name="remove_group" value="Удалить группу"/>
                        <a data-targetframe="frame0" class="btn btn-default btn-sm lockable">Отмена</a>
                        <input type="button" class="btn btn-primary btn-sm lockable btn-submit" value="Сохранить"/>
                    </fieldset>
                </form>
                <form data-frame="frame2" class="form-horizontal hide" method="post" action="?r=admin%2Fcreate-group">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                    <fieldset>
                        <legend>Создание новой группы</legend>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Тип группы</label>

                            <div class="col-md-10">
                                <div class="radio radio-primary">
                                    <label>
                                        <input type="radio" name="typeGroup" value="seller" checked="">
                                        Продавцы
                                    </label>
                                    <label>
                                        <input type="radio" name="typeGroup" value="KAM">
                                        КАМ
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-md-2 control-label">Имя группы</label>

                            <div class="col-md-10">
                                <input type="text" name="nameGroup" class="form-control" placeholder="навзвание группы">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-md-2 control-label">Сотрудники</label>

                            <div class="col-md-10">
                                <div class="col-md-6">
                                    <label for="membersGroup">Члены группы*</label>
                                    <select name="membersGroup[]" data-select1="members" multiple="" class="form-control" style="height: 200px">
                                        <?php $counter = 0; ?>
                                        <?php foreach($members as $person): ?>
                                            <?php $counter++; ?>
                                            <option value="<?=$person['personId']; ?>"><?=$counter . '. ' . $person['personName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="otherPersons">Все остальные*</label>
                                    <select name="otherPersons" data-select2="members" multiple="" class="form-control" style="height: 200px">
                                        <?php $counter = 0; ?>
                                        <?php foreach($otherPersons as $person): ?>
                                            <?php $counter++; ?>
                                            <option value="<?=$person['personId']; ?>"><?=$counter . '. ' . $person['personName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <span class="label">* - Чтобы добавить или удалить человека из группы, кликните два раза по выбранной фамилии в соответствующем списке.</span>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-md-2 control-label">Ежемесячный план</label>

                            <div class="col-md-10">
                                <input type="text" name="monthlyPlan" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-md-2 control-label">Квартальный план</label>

                            <div class="col-md-10">
                                <input type="text" name="quarterlyPlan" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail" class="col-md-2 control-label">Годовой план</label>

                            <div class="col-md-10">
                                <input type="text" name="annualPlan" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                        <a data-targetframe="frame0" class="btn btn-default btn-sm lockable">Отмена</a>
                        <input type="submit" class="btn btn-primary btn-sm" value="Создать"/>
                    </fieldset>
                </form>
                <div data-frame="frame0" class="form-horizontal">
                    <h3>Выберите группу</h3>

                    <div class="form-group">
                        <label for="select-groupId" class="col-md-2 control-label">Группа</label>

                        <div class="col-md-10">
                            <select id="select-groupId" class="form-control">
                                <?php foreach($groups as $group): ?>
                                    <option value="<?=$group['groupId']; ?>"><?=$group['groupName']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <p>
                        <a data-targetframe="frame1" class="btn btn-primary btn-sm">Выбрать</a>
                        <a data-targetframe="frame2" class="btn btn-default btn-sm">Создать новую группу</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
