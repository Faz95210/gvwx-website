<?php
$this->registerCssFile("@web/css/veltrix/chartist/css/chartist.min.css");
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/chartist/js/chartist-plugin-tooltip.min.js", ['depends' => 'app\assets\VeltrixAsset']);
$this->registerJsFile("@web/js/veltrix/pages/dashboard.js", ['depends' => 'app\assets\VeltrixAsset']);

use yii\helpers\Html;
use yii\web\View;

?>

<div class="column">
    <div class="container">
        <h2><?= $this->params['user']->name . ' ' . $this->params['user']->firstname ?>, Bienvenue sur
            AuctionManager!<br></h2>
        Cette interface vous permet de :
        <ul>
            <li> Consulter/Modifier vos objets mis aux enchères.</li>
            <li> Consulter/Modifier vos mandants et télécharger les factures associées.</li>
            <li> Consulter/Modifier vos clients et télécharger les factures associées.</li>
            <li> Consulter/Modifier vos ventes et télécharger les PV associées.</li>
        </ul>
    </div>
</div>

