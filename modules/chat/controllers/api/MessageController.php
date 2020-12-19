<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 14.12.2020
 * Time: 19:50
 */

namespace app\modules\chat\controllers\api;


use app\modules\chat\application\services\message\ISetAsCorrectMessageService;
use app\modules\chat\application\services\message\ISetAsIncorrectMessageService;
use app\modules\chat\application\services\message\ICreateMessageService;
use app\modules\chat\domain\exceptions\DomainNotFoundException;
use app\modules\chat\domain\models\Message;
use app\modules\chat\domain\models\User;
use app\modules\chat\forms\api\message\CreateMessageForm;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Html;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class MessageController extends Controller
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
                        'index',
                        'load-incoming',
                        'load-incorrect-ids',
                    ],
                    'allow' => true,
                    'roles' => ['@'],
                ],
                [
                    'actions' => [
                        'create',
                    ],
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function() {
                        return !Yii::$app->user->identity->isGuest();
                    },
                ],
                [
                    'actions' => [
                        'do-incorrect',
                        'do-correct',
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
            'index'  => ['get'],
            'load-incoming' => ['get'],
            'load-incorrect-ids' => ['get'],
            'create' => ['post'],
            'do-incorrect' => ['post'], //['put'],
            'do-correct' => ['post']//['put'],
        ];
    }

    public function actionIndex()
    {
        /**
         * @var $identity User
         */
        $identity = Yii::$app->user->identity;

        if ($identity->isAdmin()) {
            return Message::getAll();
        }

        return Message::getAllByIsCorrect(true);
    }

    public function actionLoadIncoming($id = 0)
    {
        /**
         * @var $identity User
         */
        $identity  = Yii::$app->user->identity;
        $isCorrect = null;

        if (!$identity->isAdmin()) {
            $isCorrect = true;
        }

        return Message::getAllGreaterThenId($id, $identity->marker, $isCorrect);
    }

    public function actionLoadIncorrectIds()
    {
        return Message::getAllIncorrectIdsArray();
    }

    public function actionCreate(ICreateMessageService $createMessageService)
    {
        $request  = Yii::$app->request;
        $response = Yii::$app->response;

        $form = new CreateMessageForm();
        $form->content = Html::encode($request->getBodyParam('content', ''));

        if (!$form->validate()) {
            return $form;
        }

        try {
            $message = $createMessageService->execute($form->content);

            $response->setStatusCode(201);

            return $message;
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка на сервере.');
        }
    }

    public function actionDoIncorrect($id, ISetAsIncorrectMessageService $setAsIncorrectMessageService)
    {
        try {
            return $setAsIncorrectMessageService->execute($id);
        } catch (DomainNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка на сервере.');
        }
    }

    public function actionDoCorrect($id, ISetAsCorrectMessageService $setAsCoorectMessageService)
    {
        try {
            return $setAsCoorectMessageService->execute($id);
        } catch (DomainNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка на сервере.');
        }
    }
}