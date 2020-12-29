<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 28.12.2020
 * Time: 17:02
 */

namespace app\services;


use app\domain\models\User;

interface IAuthService
{
    /**
     * @param $user User|int
     * @param $rememberMe boolean
     * @return User
     */
    public function login($user, bool $rememberMe = true): User;

    public function logout();
}