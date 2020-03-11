<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\VeltrixLoginAsset;
use cinghie\cookieconsent\widgets\CookieWidget;

CookieWidget::widget([
    'message' => \Yii::t('login', 'Pour améliorer votre experience sur ce site web, nous utilisons des cookies.'),
    'dismiss' => \Yii::t('login', Html::encode("J'ai compris")),
    'learnMore' => 'null',
    'link' => '',
    'theme' => 'dark-bottom'
]);

VeltrixLoginAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<!-- Begin page -->
<div id="wrapper">
    <div class="content-page">
        <div class="content">
            <?= $content ?>
        </div>

    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
