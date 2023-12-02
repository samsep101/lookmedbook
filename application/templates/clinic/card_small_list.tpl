<?php
/**
 * @var View $this
 * @var ClinicModel[] $clinics
 * @var string $clinicsSearchErrorMessage
 */
?>
<?php if ($clinics): ?>
    <?php $card_counter = 1; ?>
    <?php foreach ($clinics as $clinic): ?>
        <?php if ($card_counter % 2 != 0): ?>
            <div class="item-row flo row js-equal">
            <?php $this->is_closed_card = (isset($is_close_card) && $is_close_card == 1) ? 1 : null; ?>
        <?php endif ?>

        <div class=" col-lg-6 col-md-6 col-sm-12">
            <?php $this->clinic = $clinic; ?>
            <div class="info-card clinic-card flo"
                 id="clinic-card-<?= $clinic->id ?>"
                 data-phone="<?= $clinic->top_phone ?>"
                 data-info="<?= htmlentities($this->renderInString('clinic/blocks/record_popup_info')) ?>"
            >
                <?php $this->block('clinic/card_small'); ?>
            </div>
        </div>

        <?php if (($card_counter == count($clinics)) || ($card_counter % 2 == 0)): ?>
            </div>
        <?php endif ?>
        <?php $card_counter++; ?>
    <?php endforeach ?>
<?php else: ?>
    <?php SeoHideHelper::begin() ?>
    <div class="error-plate"><?= $clinicsSearchErrorMessage ?></div>
    <?php SeoHideHelper::end() ?>
<?php endif; ?>
