<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p><?php echo \Yii::t('frontend', 'Bonjour'); ?> <?= Html::encode($user->givenname) ?>,</p>

    <p><?php echo \Yii::t('frontend', 'Cliques sur le lien suivant pour réinitialiser ton mot de passe:'); ?></p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
</div>
