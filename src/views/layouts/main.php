<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;


AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <?php 
    $this->registerJsFile(
        'https://code.jquery.com/jquery-4.0.0.min.js',
        ['position' => \yii\web\View::POS_HEAD] 
    );
    ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header id="header">
        <?php

        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md fixed-top'
            ],
        ]);

        // CENTER MENU
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav mx-auto'],
            'items' => [
                [
                    'label' => 'Ticket List', 
                    'url' => ['/'],
                    'active' => Yii::$app->controller->id === 'site' && Yii::$app->controller->action->id === 'index',
                ],
                [
                    'label' => 'User List', 
                    'url' => ['/user/list'],
                    'active' => Yii::$app->controller->id === 'user' && Yii::$app->controller->action->id === 'list',
                ],
            ],
        ]);

        // RIGHT SIDE (USER)
        echo Html::beginTag('div', ['class' => 'navbar-nav ms-auto flex-row align-items-center']);

        if (Yii::$app->user->isGuest) {
            echo Html::a('Login', ['/site/login'], ['class' => 'nav-link']);
        } else {
            echo Html::a(
                Html::img('@web/images/icons/avatar.svg', ['alt' => 'Avatar', 'class' => 'user-avatar me-1']) 
                . Yii::$app->user->identity->username,
                ['/user/profile'],
                ['class' => 'nav-link me-2 username']
            );


            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-inline'])
                . Html::submitButton(
                    'Logout',
                    ['class' => 'btn btn-outline-danger btn-sm']
                )
                . Html::endForm();
        }

        echo Html::endTag('div');

        NavBar::end();
        ?>
    </header>


    <main id="main" class="flex-shrink-0" role="main">
        <?= Alert::widget() ?>
        <?= $content ?>
    </main>

    <footer id="footer" class="mt-auto py-3 bg-light">
        <div class="container">
            <div class="row text-muted">
                <div class="col-md-6 text-center text-md-start">&copy; RTI-Solution <?= date('Y') ?></div>
            </div>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>