<?php

$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

$user = $this->params['user'];

use yii\widgets\ActiveForm; ?>

<div class="card">
    <div class="card-body">
        <?php ActiveForm::begin(['action' => ['site/editprofile']]) ?>
        <div class="form-group row">
            <div class="col-sm-2 col-form-label">
                <label>Votre Nom : <span style="margin-left: 10px"><?= $user->name ?></span></label>
            </div>
            <div class="col-sm-2 col-form-label">
                <input name="name" required class="input" type="text" placeholder="">
            </div>
            <div class="col-sm-2 col-form-label">
                <button type='submit' class="btn btn-success" name="field" value="name"><i class="ti ti-check"></i>
                </button>
            </div>
        </div>
        <?php ActiveForm::end() ?>
        <?php ActiveForm::begin(['action' => ['site/editprofile']]) ?>
        <div class="form-group row">
            <div class="col-sm-2 col-form-label">
                <label>Votre Prenom : <span style="margin-left: 10px"><?= $user->firstname ?></span></label>
            </div>
            <div class="col-sm-2 col-form-label">
                <input name="firstname" required class="input" type="text" placeholder="">
            </div>
            <div class="col-sm-2 col-form-label">
                <button type='submit' class="btn btn-success" name="field" value="firstname"><i class="ti ti-check"></i>
                </button>
            </div>
        </div>
        <?php ActiveForm::end() ?>

        <div class="row">
            <div class="col-lg-5">
                <div class="text-center">
                    <label class="text-center">Votre Logo :</label><br>
                    <img id='preview-logo' class="text-center" width="50" height="50" alt="preview"
                         src="<?= $user->logo ?>"/><br>
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

                    <img id='preview-marianne' class=" text-center" alt="preview" width="50" height="50"
                         src="<?= $user->marianne ?>"/><br>
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
                <?php if ($user->licenseDate !== null) { ?>
                    License valable jusqu'au <strong><?= gmdate("d/m/y", $user->licenseDate) ?></strong>.
                <?php } else { ?>
                    Pas de license pour le moment.
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
        <button class="btn btn-block btn-primary">Modifier mot de passe</button>
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