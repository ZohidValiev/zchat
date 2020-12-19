<?php
namespace app\modules\chat\application\services\message;

use app\modules\chat\domain\models\Message;

/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 14.12.2020
 * Time: 19:54
 *
 * Сервис создает новое сообщение
 */
interface ICreateMessageService
{
    /**
     * @param $content string текст сообщения
     * @return Message
     */
    public function execute(string $content): Message;
}