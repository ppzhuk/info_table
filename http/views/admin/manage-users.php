<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

$this->title = 'Редактирование пользователей';
?>

<script>
    var _csrf = "<?=Yii::$app->request->getCsrfToken(); ?>";
</script>
<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="jumbotron">
                <h2>Управление пользователями</h2>
                <form data-frame="editUser" class="form-horizontal hide" method="post" action="?r=admin%2Fupdate-user">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                    <input type="hidden" name="userId" value="0" />
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Персональные данные</legend>
                                <div class="form-group">
                                    <label for="loginUser" class="col-md-2 control-label">Логин</label>

                                    <div class="col-md-10">
                                        <input type="text" name="loginUser" class="form-control" placeholder="логин пользователя в системе">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nameUser" class="col-md-2 control-label">ФИО</label>

                                    <div class="col-md-10">
                                        <input type="text" name="nameUser" class="form-control" placeholder="полное имя пользователя">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accessType" class="col-md-2 control-label">Должность</label>
                                    <div class="col-md-10">
                                        <select name="accessType" class="form-control">
                                            <?php foreach($accessTypes as $type): ?>
                                                <option value="<?=$type['id']; ?>"><?=$type['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Смена пароля</legend>
                                <div class="form-group">
                                    <label for="password" class="col-md-2 control-label">Пароль</label>

                                    <div class="col-md-10">
                                        <input type="text" name="password" class="form-control" placeholder="новый пароль">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="repeatPassword" class="col-md-2 control-label"></label>

                                    <div class="col-md-10">
                                        <input type="text" name="repeatPassword" class="form-control" placeholder="повторите новый пароль">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a data-targetframe="frame0" class="btn btn-default btn-sm">Отмена</a>
                            <input type="submit" class="btn btn-primary btn-sm" value="Сохранить"/>
                        </div>
                    </div>
                </form>
                <form data-frame="newUser" class="form-horizontal hide" method="post" action="?r=admin%2Fcreate-user">
                    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                    <div class="row">
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Персональные данные</legend>
                                <div class="form-group">
                                    <label for="loginUser" class="col-md-2 control-label">Логин</label>

                                    <div class="col-md-10">
                                        <input type="text" name="loginUser" class="form-control" placeholder="логин пользователя в системе">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="nameUser" class="col-md-2 control-label">ФИО</label>

                                    <div class="col-md-10">
                                        <input type="text" name="nameUser" class="form-control" placeholder="полное имя пользователя">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="accessType" class="col-md-2 control-label">Должность</label>
                                    <div class="col-md-10">
                                        <select name="accessType" class="form-control">
                                            <?php foreach($accessTypes as $type): ?>
                                                <option value="<?=$type['id']; ?>"><?=$type['name']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset>
                                <legend>Установка пароля</legend>
                                <div class="form-group">
                                    <label for="password" class="col-md-2 control-label">Пароль</label>

                                    <div class="col-md-10">
                                        <input type="text" name="password" class="form-control" placeholder="новый пароль">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="repeatPassword" class="col-md-2 control-label"></label>

                                    <div class="col-md-10">
                                        <input type="text" name="repeatPassword" class="form-control" placeholder="повторите новый пароль">
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <a data-targetframe="frame0" class="btn btn-default btn-sm">Отмена</a>
                            <input type="submit" class="btn btn-primary btn-sm" value="Создать"/>
                        </div>
                    </div>
                </form>
                <div data-frame="frame0" class="form-horizontal">
                    <h3>Выберите пользователя</h3>

                    <div class="form-group">
                        <label for="select-personId" class="col-md-2 control-label">Пользователь</label>

                        <div class="col-md-10">
                            <select id="select-personId" class="form-control">
                                <?php foreach($persons as $person): ?>
                                    <option value="<?=$person['personId']; ?>"><?=$person['personName']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <p>
                        <a data-targetframe="editUser" class="btn btn-primary btn-sm">Выбрать</a>
                        <a data-targetframe="newUser" class="btn btn-default btn-sm">Добавить нового пользователя</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
