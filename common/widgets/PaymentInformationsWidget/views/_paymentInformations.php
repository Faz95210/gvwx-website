<?php

use yii\widgets\ActiveForm;

?>
    <script src="https://js.stripe.com/v3/"></script>
    <!--<form action="index.php?r=stripe/savecard" method="post" id="payment-form">-->
<?php
$form = ActiveForm::begin(['action' => ['stripe/savecard'], 'options' => ['method' => 'post'], 'id' => 'payment-form']);
?>

    <div>
        <label for="card-element">
            Carte de crédit ou de débit
        </label>
        <div id="card-element">
            <!-- A Stripe Element will be inserted here. -->
        </div>

        <!-- Used to display Element errors. -->
        <div id="card-errors" role="alert"></div>
    </div>
    <br>
    <button id="btn-submit" class="btn btn-primary">Valider moyen de paiement</button>

<?php
ActiveForm::end();
?>