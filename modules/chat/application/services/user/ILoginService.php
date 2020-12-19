<?php
namespace app\modules\chat\application\services\user;


use app\modules\chat\domain\models\User;

/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 13.12.2020
 * Time: 17:34
 *
 * Сервис для входа в систему
 */
interface ILoginService
{
    /**
     * @param $user User|int
     * @param $rememberMe boolean
     * @return User
     */
    public function execute($user, bool $rememberMe = true): User;
}