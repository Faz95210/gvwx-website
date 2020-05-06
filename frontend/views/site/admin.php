<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);


use common\widgets\DatePickerWidget\DatePickerWidget;
use frontend\models\PasswordResetForm;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$model = new PasswordResetForm();

?>
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <h4 class="page-title"><?= \Yii::t('frontend', 'Admin'); ?></h4>
                <p></p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Roles d'utilisateur</h2>
        </div>
        <div class="card-body">

            <?php ActiveForm::begin(['action' => ['site/adduserrole']]); ?>
            <table id="companies-table" class="table table-bordered dt-responsive nowrap"
                   style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                <thead>
                <tr>
                    <th>
                        ID
                    </th>
                    <th>
                        Utilisateur
                    </th>
                    <th>
                        Email
                    </th>
                    <th>
                        License
                    </th>
                    <th>
                        Admin
                    </th>
                    <th>
                        Action
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($this->params['all_users'] as $user) {
                    ?>
                    <tr>
                        <td>
                            <?= $user->id ?>
                        </td>
                        <td>
                            <?= $user->name ?>
                        </td>
                        <td>
                            <?= $user->email ?>
                        </td>
                        <td>
                            <div class="col-sm-9">
                                <?= DatePickerWidget::widget([
                                    'id' => 'datepicker-' . $user->id,
                                    'name' => 'license_date',
                                    'value' => $user->license_date,
                                    'template' => '{addon}{input}',
                                    'clientOptions' => [
                                        'autoclose' => true,
                                        'format' => 'dd/mm/yyyy'
                                    ]
                                ]); ?>
                            </div>
                        </td>
                        <td>
                            <input type="checkbox" <?= $user->admin ? 'checked' : '' ?> name="<?= $user->id ?>"
                                   value="1">
                        </td>
                        <td>
                            <div class="row">

                                <?php ActiveForm::begin(['id' => 'deleteUser' . $user->id, 'action' => ['site/deleteuser']]) ?>
                                <input type="hidden" name="userId" value="<?= $user->id ?>">
                                <button <?= $user->id === Yii::$app->user->id ? ' disabled ' : '' ?>
                                        type="button" class="btn btn-danger" name="deleteUser" value="<?= $user->id ?>>"
                                        formtarget="deleteUser<?= $user->id ?>"
                                        onclick="document.getElementById('deleteUser<?= $user->id ?>').submit(); "><i
                                            class="ti-trash"></i></button>
                                <?php ActiveForm::end() ?>

                                <?php ActiveForm::begin(['id' => 'changeLicense' . $user->id, 'action' => ['site/changelicensestatus']]) ?>
                                <input type="hidden" name="userId" value="<?= $user->id ?>">
                                <button class="btn <?= $user->license_paid ? 'btn-danger ' : 'btn-primary ' ?>"
                                        type="button" formtarget="changeLicense<?= $user->id ?>"
                                        onclick="document.getElementById('changeLicense<?= $user->id ?>').submit(); "><i
                                            class="ti-check"></i></button>
                                <?php ActiveForm::end() ?>
                            </div>

                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            <?= Html::submitButton('Valider', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end() ?>
    </div>
    <div class="card">
        <div class="card-header">
            Changement de mot de passe
        </div>
        <div class="card-body">
            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form', 'action' => ['site/changepassword']]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(\Yii::t('login', 'Email')) ?>
            <?= $form->field($model, 'password')->passwordInput()->label(\Yii::t('login', 'Mot de passe')) ?>
            <?= $form->field($model, 'confirmationPassword')->passwordInput()->label(\Yii::t('login', 'Confirmez votre mot de passe')) ?>

            <div class="form-group">
                <?= Html::submitButton(\Yii::t('login', 'Envoyer'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

<?php

$script = <<<JS

    $("input[id^=datepicker-]").on("change", function (event){
        console.log(event.currentTarget.id.replace("datepicker-", ''));
        console.log(event);
         let fd = new FormData(); 
            fd.append('date', event.target.value); 
            fd.append('userId', event.currentTarget.id.replace("datepicker-", '')); 

            $.ajax({ 
                    url: '###URL###', 
                    type: 'post', 
                    data: fd, 
                    contentType: false, 
                    processData: false, 
                    success: function(response){ 
                        console.log(response);
                    }, 
                }); 
    });
JS;

$script = str_replace('###URL###', Yii::$app->urlManager->createAbsoluteUrl(['site/changelicensedate']), $script);

$this->registerJs($script, View::POS_END);
