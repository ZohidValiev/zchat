<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 28.12.2020
 * Time: 16:53
 */

namespace app\services;


use app\domain\models\Message;

interface IMessageService
{
    public function create(string $content): Message;

    public function setAsCorrect(int $id): Message;

    public function setAsIncorrect(int $id): Message;
}