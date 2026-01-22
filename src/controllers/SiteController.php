<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\ResetForm;
use app\models\SignupForm;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
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
    public function actions(): array
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
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionResetPassword(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $token = Yii::$app->request->get('token',  null);

        if(!$token) {
            throw new NotFoundHttpException('Invalid token.');
        }

        $model = new ResetForm();

        if ($model->load(Yii::$app->request->post())) {

            if ($model->resetPassword($model, $token)) {
                Yii::$app->session->setFlash('success', 'Password reset successfully.');
                return $this->goHome();
            }

            Yii::$app->session->setFlash('error', 'Invalid or expired password reset token.');
        }

        return $this->render('reset_password', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * @throws \Throwable
     */
    public function actionSignup(): string|Response
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'User created successfully. Password reset email sent.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Create Ticket Page
     */
    public function actionCreate()
    {
        $user = Yii::$app->user->identity;

        return $this->render('create');
    }

    /**
     * Detail Page
     */
    public function actionDetail()
    {
        $user = Yii::$app->user->identity;

        return $this->render('detail');
    }

    /**
     * Edit Page
     */
    public function actionEdit()
    {
        $user = Yii::$app->user->identity;

        return $this->render('edit');
    }
}
