<?php
namespace app\modules\chat\application\services\user;


use app\modules\chat\domain\models\User;

/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 17.12.2020
 * Time: 15:31
 *
 * Сервис обновдение роли пользователя
 */
interface IUpdateRoleUserService
{
    /**
     * @param int $id
     * @param int $role
     * @return User
     */
    public function execute(int $id, int $role): User;
}