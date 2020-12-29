<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 28.12.2020
 * Time: 16:56
 */

namespace app\services;


use app\domain\models\User;
use Yii;
use app\domain\models\Message;


class MessageService implements IMessageService
{
    public function create(string $content): Message
    {
        /**
         * @var $identity User
         */
        $identity = Yii::$app->user->identity;

        $message = new Message();
        $message->content = $content;
        $message->isCorrect = true;
        $message->userId = $identity->id;
        $message->userRole = $identity->role;
        $message->createdAt = (new \DateTime())->format('Y-m-d H:i:s');
        $message->marker = $identity->marker;
        $message->insert(false);
        $message->populateRelation('user', $identity);

        return $message;
    }

    public function setAsCorrect(int $id): Message
    {
        $message = Message::getById($id);

        $message->doCorrect();

        return $message;
    }

    public function setAsIncorrect(int $id): Message
    {
        $message = Message::getById($id);

        $message->doIncorrect();

        return $message;
    }
}