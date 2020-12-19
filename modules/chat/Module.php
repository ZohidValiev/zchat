<?php

namespace app\modules\chat;

use app\modules\chat\application\services\message\ISetAsCorrectMessageService;
use app\modules\chat\application\services\message\ISetAsIncorrectMessageService;
use app\modules\chat\application\services\message\SetAsCorrectMessageService;
use app\modules\chat\application\services\message\SetAsIncorrectMessageService;
use app\modules\chat\application\services\user\UpdateRoleUserService;
use app\modules\chat\application\services\user\IUpdateRoleUserService;
use app\modules\chat\application\services\user\ILogoutService;
use app\modules\chat\application\services\user\LogoutService;
use app\modules\chat\application\services\message\CreateMessageService;
use app\modules\chat\application\services\message\ICreateMessageService;
use app\modules\chat\application\services\user\ILoginService;
use app\modules\chat\application\services\user\LoginService;

/**
 * chat module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\chat\controllers';

    /**
     * {@inheritdoc}
     */
    public $layout = 'main';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->_initServices();
    }

    private function _initServices()
    {
        \Yii::$container->setSingletons([
            // application services
            // User
            ILoginService::class => LoginService::class,
            ILogoutService::class => LogoutService::class,
            IUpdateRoleUserService::class => UpdateRoleUserService::class,
            // Message
            ICreateMessageService::class => CreateMessageService::class,
            ISetAsIncorrectMessageService::class => SetAsIncorrectMessageService::class,
            ISetAsCorrectMessageService::class => SetAsCorrectMessageService::class,
        ]);
    }
}
