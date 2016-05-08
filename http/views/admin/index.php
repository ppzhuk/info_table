<?php
/**
 * Created by PhpStorm.
 * @author: Igor Brodt
 */

$this->title = 'Список групп';
?>

<div class="container">
    <div class="row">
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
