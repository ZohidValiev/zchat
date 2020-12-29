<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 16.12.2020
 * Time: 17:54
 */

/**
 * @var $model \app\domain\models\Message
 * @var $user \app\domain\models\User
 */

$user = $model->user;
?>

<div class="msg-item <?= $user->isAdmin() ? 'marked' : '' ?>" data-id="<?= $model->id ?>">
    <div class="msg-item__title">
        <div class="msg-item__username">
            <?= $model->user->username ?>
        </div>
        <div class="msg-item__time">
            <?= Yii::$app->formatter->asDatetime($model->createdAt, 'php:d.m.Y H:i') ?>
        </div>
    </div>
    <div class="msg-item__body">
        <div class="msg-item__body-content">
            <?= $model->content ?>
        </div>
        <div class="msg-item__body-action">
            <a href="javascript:void(0)" class="msg-item__action">
                сделать корректным
            </a>
        </div>
    </div>
</div>
