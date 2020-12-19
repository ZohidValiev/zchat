<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 16.12.2020
 * Time: 15:45
 */
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this \yii\web\View
 */
?>

<div id="chat-incorrect-message-index" class="chat-incorrect-message-index">
    <h1>Список не корректных сообщений</h1>
    <?php
    Pjax::begin([
        'timeout' => 3000,
        'linkSelector' => '.pagination a',
    ])
    ?>
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => '_msg-item',
            'options' => [
                'class' => 'msg-list',
            ],
            'itemOptions' => [
                'class' => 'msg-list__item',
            ],
        ]) ?>
    <?php Pjax::end() ?>
</div>
