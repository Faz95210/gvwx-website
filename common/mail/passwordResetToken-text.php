<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<?php echo \Yii::t('frontend', 'Bonjour'); ?> <?= $user->givenname ?>,

<?php echo \Yii::t('frontend', 'Cliques sur le lien suivant pour réinitialiser ton mot de passe:'); ?>

<?= $resetLink ?>
