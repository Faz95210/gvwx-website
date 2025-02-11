<?php

$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

$user = $this->params['user'];

use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>

<div class="card">
    <div class="card-body">
        <?php ActiveForm::begin(['action' => ['site/editprofile']]) ?>
        <div class="form-group row">
            <div class="col-sm-2 col-form-label">
                <label>Nom de votre étude : </label>
            </div>
            <div class="col-sm-2 col-form-label">
                <input name="name" required class="input" type="text" placeholder="" value="<?= $user->name ?>">
            </div>
            <div class="col-sm-2 col-form-label">
                <button type='submit' class="btn btn-primary" name="field" value="name">Modifier</button>
            </div>
        </div>
        <?php ActiveForm::end() ?>
        <div class="row">
            <div class="col-lg-5">
                <div class="text-center">
                    <label class="text-center">Votre Logo :</label><br>
                    <?= Html::img(Yii::getAlias('@web') . '/images/' . $user->logo, ['id' => 'preview-logo', 'alt' => 'logo', 'class' => 'text-center', 'width' => '50px', 'height' => '50px']); ?>
                    <div class="form-group">
                        <?php ActiveForm::begin(['id' => 'editLogo', 'action' => ['site/editprofile']]) ?>
                        <input type="hidden" id="logo" name="logo">
                        <input type="hidden" name="field" value="logo">
                        <?php ActiveForm::end() ?>

                        <input onchange="encodeImageFileAsURL(this, 'logo', 'preview-logo', 'editLogo')" type="file"
                               class="filestyle" data-input="false"
                               data-buttonname="btn-secondary" id="filestyle-1"
                               tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);">
                        <div class="">
                            <span class="group-span-filestyle " tabindex="0">
                                <label for="filestyle-1" class="btn btn-secondary ">
                                    <span class="icon-span-filestyle fas fa-folder-open"></span>
                                    <span class="buttonText">
                                        Choisir
                                    </span>
                                </label>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="text-center">
                    <label class="text-center">Votre Marianne :</label><br>
                    <?php ActiveForm::begin(['id' => 'editMarianne', 'action' => ['site/editprofile']]) ?>
                    <input type="hidden" id="marianne" name="marianne">
                    <input type="hidden" name="field" value="marianne">
                    <?php ActiveForm::end() ?>

                    <?= Html::img(Yii::getAlias('@web') . '/images/' . $user->marianne,
                        ['id' => 'preview-marianne', 'alt' => 'marianne', 'class' => 'text-center', 'width' => '50px', 'height' => '50px']); ?>
                    <input onchange="encodeImageFileAsURL(this, 'marianne', 'preview-marianne', 'editMarianne')"
                           type="file" class="filestyle" data-input="false"
                           data-buttonname="btn-secondary" id="filestyle-2"
                           tabindex="-1" style="position: absolute; clip: rect(0px, 0px, 0px, 0px);">
                    <div class="">
                        <span class="group-span-filestyle " tabindex="0">
                            <label for="filestyle-2" class="btn btn-secondary ">
                                <span class="icon-span-filestyle fas fa-folder-open"></span>
                                <span class="buttonText">
                                    Choisir
                                </span>
                            </label>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <br>

        <div class="row">
            <span class="col-lg-12 text-center">
                <?php if ($user->license_date !== null) { ?>
                    Licence valable jusqu'au <strong><?= $user->license_date ?></strong>.
                <?php } else { ?>
                    Pas de licence pour le moment.
                <?php } ?>
            </span>
        </div>
        <br>
        <button onclick='window.location.href = "mailto:​gvxconseil@gmail.com?subject=Renouvellement de license&body=Bonjour je souhaiterai renouveller ma license";'
                class="btn btn-block btn-primary" type="submit">Renouveler votre license
        </button>
        <button onclick='window.location.href = "mailto:​gvxconseil@gmail.com?subject=Subject&body=message%20goes%20here";'
                class="btn btn-block btn-primary">Contacter notre support
        </button>

        <?= Html::a('Modifier mot de passe', ['site/request-password-reset'], ['class' => 'btn btn-block btn-primary']) ?>

        <?php ActiveForm::begin(['action' => ['/site/logout']]); ?>
        <button type="submit" class="btn btn-block btn-danger">Déconnexion</button>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>

    function encodeImageFileAsURL(element, targetId, targetPreviewId, formId) {
        const file = element.files[0];
        const reader = new FileReader();
        reader.onloadend = function () {
            console.log('RESULT', reader.result);
            document.getElementById(targetId).value = reader.result;
            document.getElementById(targetPreviewId).src = reader.result;
            document.getElementById(formId).submit();
        };
        reader.readAsDataURL(file);
    }

</script>