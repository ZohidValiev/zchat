<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 16.12.2020
 * Time: 15:00
 */

namespace app\modules\chat\application\services\message;


use app\modules\chat\application\services\TransactionTrait;
use app\modules\chat\domain\models\Message;

class SetAsIncorrectMessageService implements ISetAsIncorrectMessageService
{
    use TransactionTrait;

    public function execute($id): ?Message
    {
        return $this->transaction(function() use ($id) {
            $message = Message::getById($id);

            if ($message->doIncorrect()) {
                return $message;
            }

            return null;
        });
    }
}