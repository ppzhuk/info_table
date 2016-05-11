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
            <h2>Рейтинг продавцов</h2>
            <table class="table table-striped table-hover ">
                <thead>
                <tr>
                    <th>Место</th>
                    <th>Полное имя</th>
                    <th>Доход за месяц</th>
                    <th>План за месяц</th>
                    <th>Доход за год</th>
                </tr>
                </thead>
                <tbody>
                <?php $place = 1; ?>
                <?php foreach($persons as $person): ?>
                    <tr>
                        <td><?=$place++; ?></td>
                        <td><?=$person['personName']; ?></td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
