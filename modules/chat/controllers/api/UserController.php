<?php
namespace app\modules\chat\controllers\api;

use app\modules\chat\application\services\user\IUpdateRoleUserService;
use app\modules\chat\domain\exceptions\DomainNotFoundException;
use app\modules\chat\forms\api\user\UpdateRoleUserForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Html;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 17.12.2020
 * Time: 15:22
 */
class UserController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        unset($behaviors['rateLimiter']);

        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];

        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'actions' => [
                        'update-role',
                    ],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function() {
                        return Yii::$app->user->identity->isAdmin();
                    },
                ],
            ],
        ];

        return $behaviors;
    }

    protected function verbs()
    {
        return [
            'update-role'  => ['post'],//['put'],
        ];
    }

    public function actionUpdateRole($id, IUpdateRoleUserService $updateRoleUserService)
    {
        $request = Yii::$app->request;

        $form = new UpdateRoleUserForm();
        $form->role = $request->getBodyParam('role');

        if (!$form->validate()) {
            return $form;
        }

        try {
            return $updateRoleUserService->execute($id, $form->role);
        } catch (DomainNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка на сервере.');
        }
    }
}