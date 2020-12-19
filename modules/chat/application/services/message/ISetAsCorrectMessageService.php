<?php
namespace app\modules\chat\application\services\message;


use app\modules\chat\domain\models\Message;

/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 16.12.2020
 * Time: 18:36
 *
 * Сервис делает сообщение корректным
 */
interface ISetAsCorrectMessageService
{
    /**
     * @param $id int
     * @return Message|null
     */
    public function execute($id): ?Message;
}