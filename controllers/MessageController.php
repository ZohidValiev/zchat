<?php
/**
 * Created by PhpStorm.
 * User: Zohid
 * Date: 28.12.2020
 * Time: 17:15
 */

namespace app\controllers;


use app\domain\exceptions\DomainNotFoundException;
use app\domain\models\Message;
use app\domain\models\User;
use app\services\IMessageService;
use app\forms\CreateMessageForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class MessageController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'load-all',
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
                            'incorrect',
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
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['get'],
                    'incorrect' => ['get'],
                    'load-all'  => ['get'],
                    'load-incoming' => ['get'],
                    'load-incorrect-ids' => ['get'],
                    'create' => ['post'],
                    'do-incorrect' => ['post'],
                    'do-correct' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionIncorrect()
    {
        $request = Yii::$app->request;

        if ($request->isPjax) {
            $this->layout = false;
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Message::getAllIncorrectQuery(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('incorrect', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLoadAll()
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $messages = Message::getAll();

        return array_map(function (Message $message) {
            return $message->toArray();
        }, $messages);
    }

    public function actionLoadIncoming($id = 0)
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        /**
         * @var $identity User
         */
        $identity = Yii::$app->user->identity;
        $messages = Message::getAllGreaterThenId($id, $identity->marker);

        return array_map(function(Message $message) {
            return $message->toArray();
        }, $messages);
    }

    public function actionLoadIncorrectIds()
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        return Message::getAllIncorrectIdsArray();
    }

    public function actionCreate(IMessageService $messageService)
    {
        $request  = Yii::$app->request;
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $form = new CreateMessageForm();
        $form->content = $request->post('content', '');

        if (!$form->validate()) {
            $response->setStatusCode(422, 'Data Validation Failed.');
            return $form->getFirstErrors();
        }

        try {
            $message = $messageService->create($form->content);

            return $message->toArray();
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка на сервере.');
        }
    }

    public function actionDoIncorrect($id, IMessageService $messageService)
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        try {
            $messageService->setAsIncorrect($id);

            return true;
        } catch (DomainNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка на сервере.');
        }
    }

    public function actionDoCorrect($id, IMessageService $messageService)
    {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        try {
            $messageService->setAsCorrect($id);

            return true;
        } catch (DomainNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new ServerErrorHttpException('Произошла ошибка на сервере.');
        }
    }
}