<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;

class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['profile'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // only logged-in users
                    ],
                ],
            ],
        ];
    }

    /**
     * User profile page
     */
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;

        return $this->render('profile', [
            'user' => $user,
        ]);
    }

    /**
     * User List page
     */
    public function actionList()
    {
        $user = Yii::$app->user->identity;

        return $this->render('list');
    }

    /**
     * Create User Page
     */
    public function actionCreate()
    {
        $user = Yii::$app->user->identity;

        return $this->render('create');
    }

    /**
     * Edit User Page
     */
    public function actionEdit()
    {
        $user = Yii::$app->user->identity;

        return $this->render('edit');
    }
}