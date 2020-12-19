<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 13.12.2020
 * Time: 17:35
 */

namespace app\modules\chat\application\services\user;


use app\modules\chat\domain\exceptions\DomainException;
use app\modules\chat\domain\models\User;
use Yii;

class LoginService implements ILoginService
{
    public function execute($user, bool $rememberMe = true): User
    {
        if (is_int($user)) {
            $user = User::getById($user);
        }

        /**
         * @var $user User
         */
        if ($user->isNewRecord) {
            throw new DomainException('Пользователь не является персистентным.');
        }

        Yii::$app->user->login($user, $rememberMe ? 3600*24*30 : 0);

        $user
            ->generateAccessToken()
            ->generateMarker()
            ->update(false);

        return $user;
    }
}