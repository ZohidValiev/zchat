<?php
namespace app\assets;

use yii\web\AssetBundle;

/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 14.12.2020
 * Time: 13:26
 */
class ChatAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/user-token.js',
        'js/api.js',
        'js/msg.js',
        'js/incorrect-message.js',
        'js/user-item.js'
    ];

    public $css = [
        'css/msg.css',
        'css/msg-item.css',
        'css/user-item.css'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\AppAsset',
    ];
}