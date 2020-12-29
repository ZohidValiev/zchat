<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 28.12.2020
 * Time: 17:00
 */

namespace app\services;


use app\domain\models\User;

interface IUserService
{
    public function updateRole(int $id, int $role): User;
}