<?php

use app\models\AuditLog;
use yii\web\User;
use yii\base\Event;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'rti',
    'name' => 'Domain Service Desk',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        function () {
            Event::on(
                User::class,
                User::EVENT_AFTER_LOGIN,
                function ($event) {
                    AuditLog::log(AuditLog::ACTION_LOGIN);
                }
            );

            Event::on(
                User::class,
                User::EVENT_BEFORE_LOGOUT,
                function ($event) {
                    AuditLog::log(AuditLog::ACTION_LOGOUT);
                }
            );
        },
    ],
    'container' => [
        'definitions' => [
            \yii\bootstrap5\LinkPager::class => [
                'options' => ['class' => 'pagination justify-content-center'],
                'firstPageLabel' => '«',
                'lastPageLabel' => '»',
                'prevPageLabel' => 'Previous',
                'nextPageLabel' => 'Next',
            ],
            \yii\grid\GridView::class => [
                'pager' => [
                    'class' => \yii\bootstrap5\LinkPager::class,
                ],
            ],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'rqQj7AUPbtz4owtGDxLcKOkZ7fB-4KDd',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
            'useFileTransport' => false,
            'transport' => [
                'scheme' => 'smtp',
                'host' => 'dsd_mailer',
                'port' => 1025,
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                'ticket/<ticketId:\d+>/comment/create' => 'comment/create',
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
