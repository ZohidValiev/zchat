<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 13.12.2020
 * Time: 17:20
 */

namespace app\modules\chat\controllers;


use app\modules\chat\application\services\user\ILoginService;
use app\modules\chat\application\services\user\ILogoutService;
use app\modules\chat\forms\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ServerErrorHttpException;

class AuthController extends Controller
{   public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionLogin(ILoginService $loginService)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $loginService->execute($form->getUser());
            } catch (\Exception $e) {
                throw new ServerErrorHttpException('Ошибка на сервере');
            }

            return $this->redirect(['/chat']);
        }

        $form->password = '';
        return $this->render('login', [
            'model' => $form,
        ]);
    }

    public function actionLogout(ILogoutService $logoutService)
    {
        $logoutService->execute();
        
        return $this->goHome();
    }
}