<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 16.12.2020
 * Time: 14:26
 */

namespace app\modules\chat\controllers;


use Yii;
use app\modules\chat\domain\models\IncorrectMessage;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class IncorrectMessageController extends Controller
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
        $request = Yii::$app->request;

        if ($request->isPjax) {
            $this->layout = false;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => IncorrectMessage::getAllQuery(),
            'key' => 'messageId',
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
}