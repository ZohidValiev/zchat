<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 16.12.2020
 * Time: 17:54
 */

use yii\helpers\Html;

/**
 * @var $model \app\modules\chat\domain\models\IncorrectMessage
 * @var $message \app\modules\chat\domain\models\Message
 * @var $user \app\modules\chat\domain\models\User
 */
$message = $model->message;
$user = $message->user;
?>

<div class="msg-item <?= $user->isAdmin() ? 'marked' : '' ?>" data-id="<?= $message->id ?>">
    <div class="msg-item__title">
        <div class="msg-item__username">
            <?= $message->user->username ?>
        </div>
        <div class="msg-item__time">
            <?= Yii::$app->formatter->asDatetime($message->createdAt, 'php:d.m.Y H:i') ?>
        </div>
    </div>
    <div class="msg-item__body">
        <div class="msg-item__body-content">
            <?= Html::encode($message->content)  ?>
        </div>
        <div class="msg-item__body-action">
            <a href="javascript:void(0)" class="msg-item__action">
                сделать корректным
            </a>
        </div>
    </div>
</div>
