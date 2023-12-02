<?php if ($purposes): ?>
<div class="purpose-row">
    <div class="purpose">
        Цели визита
    </div>
    <div class="price">
        Цена приема
    </div>
    <div class="price">
        Цена услуги по клинике
    </div>
</div>
<div data-holder-for="purpose_of_visit_to_doctor">

    <?php foreach($purposes as $purpose): ?>
        <div class="purpose-row flo" data-name="purpose_of_visit_to_doctor">
            <input type="hidden" name="purpose_of_visit_id" value="<?php echo $purpose->getId(); ?>" />
            <input type="hidden" name="specialty_id" value="<?php echo $purpose->specialty_id; ?>" />
            <div class="purpose">
                <?php $class = ($purpose->is_selected) ? 'act' : ''; ?>
                <div class="chekBox purpose-select <?php echo $class; ?>
                    <?php if ($purpose->is_main): ?>
                        bold
                    <?php endif; ?>
                "
                     <?php if ($purpose->is_main): ?>
                        data-disabled="true"
                     <?php endif; ?>
                        >
                    <span></span>
                    <?php echo $purpose->name; ?>
                    <input type="hidden" name="is_selected" value="<?php echo $purpose->is_selected; ?>">
                    <input type="hidden" name="clinic_id" value="<?php echo $clinic_id ?>">
                </div>
            </div>
            <div class="price">
                <input type="text" name="visit_price" value="<?php echo $purpose->visit_price; ?>"><label>руб.</label>
            </div>
            <div class="price">

                <?php if ($purpose->clinic_price_to_purpose != ''): ?>
                    <label class="clinic_price">
                        <?php if ($purpose->clinic_price_to_purpose == 0): ?>
                            бесплатно
                            <input type="hidden" name="clinic_price_value" value="0"/>
                        <?php else: ?>
                            <?php echo $purpose->clinic_price_to_purpose; ?>
                            <input type="hidden" name="clinic_price_value" value="<?php echo $purpose->clinic_price_to_purpose; ?>"/>
                        <?php endif; ?>
                    </label>
                <?php endif; ?>

                <?php if ($purpose->clinic_price_to_purpose != '' && $purpose->clinic_price_to_purpose != 0): ?>
                    <label class="clinic_price_r">руб.</label>
                <?php endif; ?>

                <?php if ($purpose->clinic_price_to_purpose != $purpose->visit_price): ?>
                    <?php if ($purpose->clinic_price_to_purpose != ""): ?>
                        <input style="font-size: 10px; float: right" class="set_price_button" type="button" value="Сделать ценой клиники"/>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php else: ?>
    По данной специальности не указаны цели визита.
<?php endif; ?>
