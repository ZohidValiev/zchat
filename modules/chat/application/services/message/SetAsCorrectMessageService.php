<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 16.12.2020
 * Time: 18:37
 */

namespace app\modules\chat\application\services\message;


use app\modules\chat\application\services\TransactionTrait;
use app\modules\chat\domain\models\Message;

class SetAsCorrectMessageService implements ISetAsCorrectMessageService
{
    use TransactionTrait;

    public function execute(int $id): ?Message
    {
        return $this->transaction(function() use ($id) {
            $message = Message::getById($id);

            if ($message->doCorrect()) {
                return $message;
            }

            return null;
        });
    }
}