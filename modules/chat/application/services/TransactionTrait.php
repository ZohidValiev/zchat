<?php
namespace app\modules\chat\application\services;

/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 16.12.2020
 * Time: 15:01
 */

use Yii;

trait TransactionTrait
{
    protected function transaction(callable $callback, string $isolationLevel = null)
    {
        return Yii::$app->db->transaction($callback, $isolationLevel);
    }
}