<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 17.12.2020
 * Time: 13:38
 */

namespace app\controllers;


use app\domain\exceptions\DomainNotFoundException;
use app\domain\models\User;
use app\services\IUserService;
use app\forms\UpdateRoleUserForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

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
                        'actions' => [
                            'index',
                            'update-role',
                        ],
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
                    'update-role' => ['post'],
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

    public function actionUpdateRole($id, IUserService $userService)
    {
        $request  = Yii::$app->request;
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $form = new UpdateRoleUserForm();
        $form->role = $request->post('role');

        if (!$form->validate()) {
            $response->setStatusCode(422, 'Data Validation Failed.');
            return $form->getFirstErrors();
        }

        try {
            $user = $userService->updateRole($id, $form->role);

            return $user->toArray();
        } catch (DomainNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка на сервере.');
        }
    }
}