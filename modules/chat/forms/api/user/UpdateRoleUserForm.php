<?php
namespace app\modules\chat\forms\api\user;

use app\modules\chat\domain\models\User;
use yii\base\Model;

/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 17.12.2020
 * Time: 21:42
 */
class UpdateRoleUserForm extends Model
{
    public $role;

    public function rules()
    {
        return [
            [['role'], 'required'],
            [['role'], 'integer'],
            [['role'], 'in', 'range' => User::getRoles()],
        ];
    }
}