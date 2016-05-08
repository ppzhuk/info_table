<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

$this->title = 'Редактирование групп';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="jumbotron">
                <h2>Управление группами</h2>
                <form data-frame="frame2" class="form-horizontal hide" method="post" action="?r=admin%2Fmanage-groups&action=newGroup">
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
                                    <select name="membersGroup" data-select1="members" multiple="" class="form-control" style="height: 200px">
                                        <?php foreach($members as $person): ?>
                                            <option value="<?=$person['personId']; ?>"><?=$person['personName']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="otherPersons">Все остальные*</label>
                                    <select name="otherPersons" data-select2="members" multiple="" class="form-control" style="height: 200px">
                                        <?php foreach($otherPersons as $person): ?>
                                            <option value="<?=$person['personId']; ?>"><?=$person['personName']; ?></option>
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
                        <a data-targetframe="frame0" class="btn btn-default btn-sm">Отмена</a>
                        <input type="submit" class="btn btn-primary btn-sm" value="Создать"/>
                    </fieldset>
                </form>
                <form data-frame="frame1" class="form-horizontal hide">
                    <fieldset>
                        <legend>Создание новой группы</legend>
                        <div class="form-group">
                            <label for="inputEmail" class="col-md-2 control-label">Email</label>

                            <div class="col-md-10">
                                <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword" class="col-md-2 control-label">Password</label>

                            <div class="col-md-10">
                                <input type="password" class="form-control" id="inputPassword" placeholder="Password">

                                <!--
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox"> Checkbox
                                  </label>
                                  <label>
                                    <input type="checkbox" disabled> Disabled Checkbox
                                  </label>
                                </div>
                                <br>

                                <div class="togglebutton">
                                  <label>
                                    <input type="checkbox" checked> Toggle button
                                  </label>
                                </div>
                                -->
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 0;"> <!-- inline style is just to demo custom css to put checkbox below input above -->
                            <div class="col-md-offset-2 col-md-10">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox"> Checkbox
                                    </label>
                                    <label>
                                        <input type="checkbox" disabled=""> Disabled Checkbox
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-offset-2 col-md-10">
                                <div class="togglebutton">
                                    <label>
                                        <input type="checkbox" checked=""> Toggle button
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputFile" class="col-md-2 control-label">File</label>

                            <div class="col-md-10">
                                <input type="text" readonly="" class="form-control" placeholder="Browse...">
                                <input type="file" id="inputFile" multiple="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="textArea" class="col-md-2 control-label">Textarea</label>

                            <div class="col-md-10">
                                <textarea class="form-control" rows="3" id="textArea"></textarea>
                                <span class="help-block">A longer block of help text that breaks onto a new line and may extend beyond one line.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label">Radios</label>

                            <div class="col-md-10">
                                <div class="radio radio-primary">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked="">
                                        Option one is this
                                    </label>
                                </div>
                                <div class="radio radio-primary">
                                    <label>
                                        <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                        Option two can be something else
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select111" class="col-md-2 control-label">Select</label>

                            <div class="col-md-10">
                                <select id="select111" class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="select222" class="col-md-2 control-label">Select Multiple</label>

                            <div class="col-md-10">
                                <select id="select222" multiple="" class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10 col-md-offset-2">
                                <button type="button" class="btn btn-default">Cancel</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
                <div data-frame="frame0" class="form-horizontal">
                    <h3>Выберите группу</h3>

                    <div class="form-group">
                        <label for="groupId" class="col-md-2 control-label">Группа</label>

                        <div class="col-md-10">
                            <select id="groupId" class="form-control">
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
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h2>Группы</h2>
            <table class="table table-striped table-hover ">
                <thead>
                <tr>
                    <th>Имя группы</th>
                    <th>Количество сотрудников</th>
                    <th>Тип группы</th>
                    <th>Руководитель</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($groups as $group): ?>
                    <tr>
                        <td><?=$group['groupName']; ?></td>
                        <td><?=$group['amountEmployees']; ?></td>
                        <td><?=$group['groupType']; ?></td>
                        <td><?=$group['ownerName']; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
