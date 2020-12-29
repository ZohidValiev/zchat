<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 28.12.2020
 * Time: 17:01
 */

namespace app\services;


use app\domain\models\User;

class UserService implements IUserService
{
    public function updateRole(int $id, int $role): User
    {
        $user = User::getById($id);

        $user->doUpdateRole($role);

        return $user;
    }
}