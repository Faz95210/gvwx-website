<?php
?>
<a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
   aria-haspopup="false" aria-expanded="false">
    <i class="mdi mdi-bell-outline noti-icon"></i>
    <span class="badge badge-pill badge-danger noti-icon-badge"><?php use yii\helpers\Html;

        echo count($this->params['notifications']); ?></span>
</a>
<div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
    <!-- item-->
    <h6 class="dropdown-item-text">
        Notifications (<?php echo count($this->params['notifications']); ?>)
    </h6>
    <div class="slimscroll notification-item-list">
        <?php
        foreach ($this->params['notifications'] as $key => $value) {
            switch ($key) {
                case "incomplete_trips" :
                    ?>
                    <a href="/index.php?r=site/trips&incompletes=1" class="dropdown-item notify-item active">
                        <div class="notify-icon bg-success"><i class="mdi mdi-map-outline"></i></div>
                        <p class="notify-details"><?php echo $value ?> déplacements incomplets
                            <span class="text-muted">
                                    Cliquez ici, pour fixer les addresses.
                                </span>
                        </p>
                    </a>
                    <?php
                    break;
            }
        }
        ?>
    </div>
    <!-- All-->
    <a href="javascript:void(0);" class="dropdown-item text-center text-primary">
        View all <i class="fi-arrow-right"></i>
    </a>
</div>
