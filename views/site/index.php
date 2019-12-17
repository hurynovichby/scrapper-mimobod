<?php

/* @var $this yii\web\View */
/* @var $main*/
/* @var $listFlatAll*/
$this->title = 'My Yii Application';
?>
<div class="site-index">
    <?php foreach ($listFlatAll as $listFlatAllItem): ?>
        <div>
            <code>Всего кв. <?= count( $listFlatAllItem['data'] ); ?></code>
        </div>
        <div>
            <code>Сумма: <?= $listFlatAllItem['sum']; ?></code>
        </div>
        <div>
            <code>Максимальное сумма в массиве: <?= max($listFlatAllItem['data']); ?>$</code>
        </div>
        <progress style="width: 100%;
    height: 35px;" max="<?= max($listFlatAllItem['data']); ?>" value="<?= $listFlatAllItem['sum'] / count( $listFlatAllItem['data'] );  ?>">
            Загружено на <span id="value">25</span>%
        </progress>
    <?php endforeach; ?>

    <table class="table">
        <thead>
        <tr>
            <th>№</th>
            <th>Название хаты</th>
            <th>Цена</th>
            <th>Категория</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $main as $mainItem ): ?>
            <tr>
                <td><?= $mainItem['id'] ?></td>
                <td><?= $mainItem['name'] ?></td>
                <td><?= $mainItem['price'] ?>$</td>
                <td><?= $mainItem['title'] ?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

</div>
