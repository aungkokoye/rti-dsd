<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\ResetForm;
use app\models\SignupForm;
use app\models\User;
use app\models\UserSignupForm;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
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
    public function actionIndex()
    {
        return $this->redirect(['/ticket/index']);
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
     * @throws NotFoundHttpException
     * @throws \Throwable
     */
    public function actionDomainLogin(): Response|string
    {
        $token = Yii::$app->request->get('token',  null);
        $data = User::tokenValidation($token);
        $user = User::findForLinkLogin(
            $data[USER::SITE_KEY] ?? null,
            $data[USER::SITE_USER_ID] ?? null,
            $data[USER::SITE_ID] ?? null
        );

        if ($user) {
            Yii::$app->user->login($user);
            return $this->redirect('/site/login');
        }

        $form = new UserSignupForm();
        $form->site_key = $data[USER::SITE_KEY] ?? null;
        $form->site_user_id = $data[USER::SITE_USER_ID] ?? null;
        $form->domain_id = $data[USER::SITE_ID] ?? null;

        if ($form->load(Yii::$app->request->post()) && $form->signup()) {
            return $this->render(
                'info',
                ['message' => 'Please check your email for further instructions.']
                );
        }

        return $this->render('domain_login', [
            'model' => $form,
        ]);
    }

    public function actionTest(): Response
    {
        $data = '{"site_key":"qyerp", "site_id": 2, "user_id": 1}';
        $encryptedToken = Yii::$app->security->encryptByPassword($data, Yii::$app->params['encryptionKey']);
        $encryptedToken = urlencode(base64_encode($encryptedToken));

        return $this->redirect('/site/domain-login?token=' . $encryptedToken);
    }
}
