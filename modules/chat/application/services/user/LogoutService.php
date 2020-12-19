<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 15.12.2020
 * Time: 13:03
 */

namespace app\modules\chat\application\services\user;


use app\modules\chat\domain\models\User;
use Yii;

class LogoutService implements ILogoutService
{
    public function execute()
    {
        /**
         * @var $identity User
         */
        $identity = Yii::$app->user->identity;

        $identity
            ->resetAccessToken()
            ->resetMarker()
            ->update(false);

        Yii::$app->user->logout();
    }
}