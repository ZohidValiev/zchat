<?php
use yii\web\View;
use app\modules\chat\assets\ChatAssetBundle;

/**
 * @var $this View
 * @var $content string
 * @var
 */

ChatAssetBundle::register($this);

$user = Yii::$app->user;

if (!$user->isGuest) {
    $encoded = json_encode($user->identity->getUserTokenArray());
    $this->registerJs("window.userToken.init({$encoded})");
}
?>
<?php $this->beginContent('@app/views/layouts/main.php', $this->params); ?>
<?= $content ?>
<?php $this->endContent(); ?>
