<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<div class="verify-email">
    <p><?php echo \Yii::t('frontend', 'Bonjour'); ?> <?= Html::encode($user->firstname) ?>,</p>

    <p><?php echo \Yii::t('frontend', 'Cliques sur le lien suivant pour valider ton compte:'); ?></p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>
