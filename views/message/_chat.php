<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 14.12.2020
 * Time: 13:48
 */

/**
 * @var $this \yii\web\View
 */
?>

<div class="wrapper">
    <div id="chat" class="chat">
        <div class="chat__title">
            ЧАТ ТЕСТОВОГО ЗАДАНИЯ
        </div>
        <div id="chat__content" class="chat__content">
            <div class="chat__msg-box">
            </div>
        </div>
        <div class="chat__footer">
            <?php if (!Yii::$app->user->identity->isGuest()): ?>
                <textarea id="chat-msg"
                          class="chat__msg-input"
                          maxlength="255"
                          cols="30"
                          rows="5"
                          placeholder="Введите текст сообщения"
                          disabled></textarea>
                <a id="chat" class="chat__msg-send" href="javascript:void(0)"></a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script id="msg-template" type="template">
    <div class="chat-msg">
        <div class="chat-msg__title"></div>
        <div class="chat-msg__content"></div>
        <div class="chat-msg__footer">
            <?php if (Yii::$app->user->identity->isAdmin()): ?>
                <a class="chat-msg__action" href="javascript:void(0)">
                   сделать некорректным
                </a>
            <?php endif; ?>
            <span class="chat-msg__time"></span>
        </div>
    </div>
</script>
