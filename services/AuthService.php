<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 28.12.2020
 * Time: 17:04
 */

namespace app\services;


use app\domain\exceptions\DomainException;
use Yii;
use app\domain\models\User;

class AuthService implements IAuthService
{
    public function login($user, bool $rememberMe = true): User
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
            ->generateMarker()
            ->update(false);

        return $user;
    }

    public function logout()
    {
        /**
         * @var $user \yii\web\User
         * @var $identity User
         */
        $user = Yii::$app->user;

        $identity = $user->identity;
        $identity
            ->resetMarker()
            ->update(false);

        $user->logout();
    }

}