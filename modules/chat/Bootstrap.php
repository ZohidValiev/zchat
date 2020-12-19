<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 14.12.2020
 * Time: 17:15
 */

namespace app\modules\chat;


use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\rest\UrlRule;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $app->urlManager->addRules([
            'login' => 'chat/auth/login',
            'logout' => 'chat/auth/logout',
            'msg/<controller:[\w-]+>/<action:[\w-]+>' => 'chat/<controller>/<action>',
            'msg/<controller:[\w-]+>' => 'chat/<controller>',
            'msg' => 'chat',
            'create-users' => 'chat/default/create-users',
            // REST api
            [
                'class' => UrlRule::class,
                'pluralize' => false,
                'prefix' => 'api',
                'extraPatterns' => [
                    'GET incoming/{id}' => 'load-incoming',
                    'GET incorrect-ids' => 'load-incorrect-ids',
                    //'PUT {id}/incorrect' => 'do-incorrect',
                    'POST {id}/incorrect' => 'do-incorrect',
                    //'PUT {id}/correct' => 'do-correct',
                    'POST {id}/correct' => 'do-correct',
                ],
                'controller' => [
                    'msg/message' => 'chat/api/message',
                ],
                'only' => [
                    'index',
                    'load-incoming',
                    'load-incorrect-ids',
                    'create',
                    'do-incorrect',
                    'do-correct',
                ],
            ],
            [
                'class' => UrlRule::class,
                'pluralize' => false,
                'prefix' => 'api',
                'extraPatterns' => [
                    //'PUT {id}/role' => 'update-role',
                    'POST {id}/role' => 'update-role',
                ],
                'controller' => [
                    'msg/user' => 'chat/api/user'
                ],
                'only' => [
                    'update-role',
                ],
            ],
        ]);
    }
}