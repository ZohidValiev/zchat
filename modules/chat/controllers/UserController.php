<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 17.12.2020
 * Time: 13:38
 */

namespace app\modules\chat\controllers;


use app\modules\chat\domain\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function() {
                            return Yii::$app->user->identity->isAdmin();
                        },
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $users = User::getAll(Yii::$app->user->id);

        return $this->render('index', [
            'users' => $users,
        ]);
    }
}