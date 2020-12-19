<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 17.12.2020
 * Time: 15:35
 */

namespace app\modules\chat\application\services\user;


use app\modules\chat\application\services\TransactionTrait;
use app\modules\chat\domain\models\User;

class UpdateRoleUserService implements IUpdateRoleUserService
{
    use TransactionTrait;

    public function execute(int $id, int $role): User
    {
        return $this->transaction(function() use ($id, $role) {
            $user = User::getById($id);

            $user->doUpdateRole($role);

            return $user;
        });
    }
}