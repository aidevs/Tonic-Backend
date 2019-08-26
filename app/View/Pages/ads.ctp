<?php if (!empty($shopAddList)) { ?>
        <div class="row tv-add-col-left">
            <div class="tv-add" id="shopAddList">
                <img height="159" src="<?php echo $shopAddList[0]; ?>">
            </div>
        </div>

    <?php if (count($shopAddList) > 1) { ?>
        

        <?php
    }
} else {
    ?>
        <div class="row tv-add-col-left">
            <div class="tv-add">
                <img  height="159" src="<?php echo SITE_URL; ?>img/no-img.jpg">
            </div>
        </div>
    <?php
}
/*
if (!empty($adminAddList)) {
    ?>
    <div class="col-xs-6">
        <div class="row tv-add-col-right">
            <div class="tv-add" id="adminAddList">
                <img width="675" height="159" src="<?php echo $adminAddList[0]; ?>">
            </div>
        </div>
    </div>

    <?php if (count($adminAddList) > 1) { ?>
        

        <?php
    }
} else {
    ?>
    <div class="col-xs-6">
        <div class="row tv-add-col-right">
            <div class="tv-add">
                <img width="675" height="159" src="<?php echo SITE_URL; ?>img/no-img.jpg">
            </div>
        </div>
    </div>
<?php } */
?>

