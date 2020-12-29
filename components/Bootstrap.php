<?php
namespace app\components;


use app\services\AuthService;
use app\services\IAuthService;
use app\services\IMessageService;
use app\services\IUserService;
use app\services\MessageService;
use app\services\UserService;
use yii\base\BootstrapInterface;


/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 28.12.2020
 * Time: 18:26
 *
 * Добавлеи сервисы в контейнер.
 */
class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        \Yii::$container->setSingletons([
            IAuthService::class => AuthService::class,
            IUserService::class => UserService::class,
            IMessageService::class => MessageService::class,
        ]);
    }
}