<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<?php echo \Yii::t('frontend', 'Bonjour'); ?> <?= $user->firstname ?>,

<?php echo \Yii::t('frontend', 'Cliques sur le lien suivant pour valider ton compte:'); ?>

<?= $verifyLink ?>
