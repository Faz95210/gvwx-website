<?php

use yii\helpers\Html;

?>

<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <a href="index.php" class="waves-effect " style="top:10px;">
            <i class="ti-home" style="font-size: 50px; padding-top: 10px"></i>
        </a>
    </div>

    <nav class="navbar-custom">
        <ul class="navbar-right list-inline float-right mb-0">

            <!-- notification -->
            <li class="dropdown notification-list list-inline-item">
            </li>
            <li class="dropdown notification-list list-inline-item">
                <div class="dropdown notification-list nav-pro-img">
                    <a class="dropdown-toggle nav-link arrow-none waves-effect nav-user" data-toggle="dropdown" href="#"
                       role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-user rounded-circle"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <?= Html::a('<span class="dropdown-item"><i class="mdi mdi-account-circle m-r-5"></i>' . \Yii::t("navbar", "Profile") . '</span>', ['site/profile', []]) ?>

                        <!--                    <a class="dropdown-item" href="#"><i class="mdi mdi-account-circle m-r-5"></i> -->
                        <?php //echo \Yii::t('navbar', 'Profile'); ?><!--</a>-->
                        <!--                        <a class="dropdown-item" href="#"><i-->
                        <!--                                    class="mdi mdi-wallet m-r-5"></i> -->
                        <?php //echo \Yii::t('navbar', 'Mon compte'); ?><!--</a>-->
                        <!--                        <a class="dropdown-item d-block" href="#"><span-->
                        <!--                                    class="badge badge-success float-right">11</span><i-->
                        <!--                                    class="mdi mdi-settings m-r-5"></i> Settings</a>-->
                        <!--                        <a class="dropdown-item" href="#"><i class="mdi mdi-lock-open-outline m-r-5"></i> Lock-->
                        <!--                            screen</a>-->
                        <div class="dropdown-divider"></div>
                        <?= Html::beginForm(['/site/logout'], 'post')
                        . Html::submitButton(
                            '<i class="mdi mdi-power text-danger"></i> ' . \Yii::t('navbar', 'Déconnection'),
                            ['class' => 'dropdown-item text-danger']
                        )
                        . Html::endForm()
                        ?>

                    </div>
                </div>
            </li>

        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left waves-effect">
                    <i class="mdi mdi-menu"></i>
                </button>
            </li>
        </ul>
    </nav>
</div>

<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu" id="side-menu">
                <!--<li class="menu-title">Main</li> -->
                <li>
                    <?= Html::a('<svg color="green" fill="green" width="20px" height="20px"><image  style="width: 100%" href="images/article.svg"/></svg><span> ' . \Yii::t('menu', 'Items') . '</span>', ['item/get'], ['class' => 'waves-effect']) ?>
                    <!--
                                <a href="index.php" class="waves-effect">
                                    <i class="ti-home"></i><span> <?php echo \Yii::t('menu', 'Items'); ?> </span>
                                </a> -->
                </li>
                <?php
                if (Yii::$app->user->can('admin')) {
                    ?>
                    <li>
                        <?= Html::a('<i class="ti-user"></i><span> ' . \Yii::t('menu', 'Admin') . '</span>', ['site/admin'], ['class' => 'waves-effect ']) ?>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <?= Html::a('<i class="ti-user"></i><span> ' . \Yii::t('menu', 'Clients') . '</span>', ['client/get'], ['class' => 'waves-effect ']) ?>
                </li>
                <li>
                    <?= Html::a('<i class="ti-briefcase"></i><span> ' . \Yii::t('menu', 'Mandants') . '</span>', ['mandant/get'], ['class' => 'waves-effect']) ?>
                    <!--<a href="calendar.php" class="waves-effect"><i class="ti-map-alt"></i><span> <?php echo \Yii::t('menu', 'Déplacements'); ?> </span></a> -->
                </li>
                <li>
                    <?= Html::a('<i class="ti-money"></i><span> ' . \Yii::t('menu', 'Ventes') . '</span>', ['sale/get'], ['class' => 'waves-effect']) ?>
                    <!--<a href="calendar.php" class="waves-effect"><i class="ti-car"></i><span> <?php echo \Yii::t('menu', 'Voitures'); ?> </span></a>-->
                </li>


            </ul>

        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>