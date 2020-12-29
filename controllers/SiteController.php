<?php

namespace app\controllers;

use app\services\IAuthService;
use app\forms\LoginForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\ServerErrorHttpException;

class SiteController extends Controller
{
    public function behaviors()
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

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionLogin(IAuthService $authService)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $request = Yii::$app->request;
        $form = new LoginForm();

        if ($form->load($request->post()) && $form->validate()) {
            try {
                $authService->login($form->getUser());
            } catch (\Exception $e) {
                throw new ServerErrorHttpException('Ошибка на сервере');
            }

            return $this->redirect(['/message']);
        }

        $form->password = '';
        return $this->render('login', [
            'model' => $form,
        ]);
    }

    public function actionLogout(IAuthService $authService)
    {
        $authService->logout();

        return $this->goHome();
    }
}
