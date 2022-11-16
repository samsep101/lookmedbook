<?php
/**
 * @var View $this
 * @var ClinicModel[] $clinics
 * @var int $selected_clinic_id
 */
?>

<div class="fields-block-inner light-blue-inner" style="margin-bottom: 40px; padding-bottom: 10px;">
    <div class="row-record" style="margin-top: 10px;">
        <label style="margin-top: 2px;">Клиника</label>
        <div class="row-record-data doctor-to-clinic-wrapper" data-holder-for="doctor_to_clinic" style="margin-top: 0;">
            <div data-name="doctor_to_clinic">
                <select class="clinic_id" name="clinic_id" size="1">
                    <option value=""></option>
                    <?php if ($clinics): ?>
                        <?php foreach($clinics as $clinic): ?>
							<?php $cid = (is_object($clinic)) ? $clinic->getId() : $clinic['id']; ?>
							<?php $cname = (is_object($clinic)) ? $clinic->name : $clinic['name']; ?>
                            <option value="<?php echo $cid; ?>"
                                <?php echo ($cid== $selected_clinic_id) ? 'selected="selected"' : ''; ?>
                                ><?php echo $cname; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>
        <span style="float: right; margin-right: 10px;"><input class="delete-clinic-button" type="submit" value="Удалить"></span>
    </div>
</div>