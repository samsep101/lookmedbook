<?php
/**
 * @var View $this
 * @var ClinicModel $clinic
 */
?>
<div class="row flo m-b-10 info-card record-popup-info-card">
    <div class="avatar">
        <?= ClinicAvatarViewHelper::viewOnCard($clinic, 74, 31) ?>
    </div>
    <div class="record-popup-info-details">
        <p class="name">
            <a href="<?= ClinicPageLinkViewHelper::getLink($clinic) ?>">
                <span class="post"><?= $clinic->name ?></span>
            </a>
        </p>
        <div class="aata-address">
            <div class="aata-street">
                <img src="/media/images/small_placemark_for_street.png"><?= $clinic->address ?>
            </div>
            <div class="aata-metro">
                <?php if ($clinic->metro_stations) { ?>
                    <?php foreach ($clinic->metro_stations as $metro_station) { ?>
                        <?php if ($metro_station->metro_branch) { ?>
                            <?= MetroBranchIconViewHelper::getImage($metro_station->metro_branch) ?>
                        <?php } ?>
                        <?= $metro_station->name ?><br>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
