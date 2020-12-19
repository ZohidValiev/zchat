<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 17.12.2020
 * Time: 14:51
 */

use yii\helpers\Html;
use app\modules\chat\assets\ChatAssetBundle;
use app\modules\chat\domain\models\User;

ChatAssetBundle::register($this);

/**
 * @var $user User
 * @var $ix int
 */
$roleOptions = User::getRoleOptions();
?>
<div class="user-item " data-id="<?= $user->id ?>">
    <div class="user-item__ix">
        <?= $ix + 1 ?>
    </div>
    <div class="user-item__username">
        <?= $user->username ?>
    </div>
    <div class="user-item__role">
        <?= $roleOptions[$user->role] ?>
    </div>
    <div class="user-item__action">
        <?= Html::dropDownList('role', $user->role, $roleOptions, [
            'class' => 'user-item__action-role'
        ]) ?>
        <button class="user-item__action-button">Измнеть</button>
        <i class="user-item__indicator"></i>
    </div>
</div>
