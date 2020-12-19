<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 17.12.2020
 * Time: 13:47
 */

use app\modules\chat\domain\models\User;

/**
 * @var $this \yii\web\View
 */
//echo $this->
?>

<div id="chat-user-index" class="chat-user-index" data-roles='<?= json_encode(User::getRoleOptions()) ?>'>
    <h1>Список пользователей</h1>

    <div class="user-list">
        <div class="user-list__header">
            <div class="user-list__header-ix">
                №
            </div>
            <div class="user-list__header-username">
                Логин
            </div>
            <div class="user-list__header-role">
                Роль
            </div>
            <div class="user-list__header-action">
                Действие
            </div>
        </div>
        <div class="user-list__body">
            <?php foreach ($users as $ix => $user): ?>
                <?= $this->render('_user-item', [
                    'ix' => $ix,
                    'user' => $user,
                ]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>
