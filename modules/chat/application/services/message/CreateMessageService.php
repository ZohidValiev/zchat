<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 14.12.2020
 * Time: 19:56
 */

namespace app\modules\chat\application\services\message;


use app\modules\chat\domain\models\Message;
use app\modules\chat\domain\models\User;
use Yii;

final class CreateMessageService implements ICreateMessageService
{
    public function execute(string $content): Message
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
}