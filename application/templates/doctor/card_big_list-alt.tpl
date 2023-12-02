<?php  if (isset($doctorAlt) && $doctorAlt): ?>
    <?php $counter = 1; ?>
    <?php $virtual_doctors = array(); ?>
    <?php foreach ($doctorAlt as $doctor): ?>
        <?php if ($doctor->is_virtual): ?>
            <?php array_push($virtual_doctors, $doctor); ?>
            <?php continue; ?>
        <?php endif; ?>


        <?php if ($counter % 2 == 1): ?>
            <div class="item-row flo row">

            <?php endif; ?>

            <?php $this->counter = $counter; ?>
            <?php $this->doctor = $doctor; ?>
            <?php $this->is_virtual = false; ?>
            <?php $this->is_closed_card = (isset($is_close_card) && $is_close_card == 1) ? 1 : null; ?>
            <?php
            if (isset($clinic)) {
                $this->clinic = $clinic;
                $this->clinic_id = $clinic->getId();
            }
            ?>

            <?php $this->specialtyIDForDoctorCard = (isset($specialtyIDForDoctorCard)) ? $specialtyIDForDoctorCard : null; ?>
            <?php $this->specialty_id = (isset($specialty_id)) ? $specialty_id : null; ?>
            <?php $this->purpose_of_visit_id = (isset($purpose_of_visit_id)) ? $purpose_of_visit_id : null; ?>
            <?php $this->columned_list = 1; ?>
            <?php $this->page_type = empty($page_type) ? '' : $page_type; ?>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?php $this->block('doctor/card_big'); ?>
            </div>
            <?php if ($counter % 2 == 0): ?>

            </div>
        <?php endif; ?>
        <?php $counter++; ?>
    <?php endforeach; ?>

    <?php foreach ($virtual_doctors as $doctor): ?>
        <?php if ($counter % 2 == 1): ?>
            <div class="item-row flo row">

            <?php endif; ?>

            <?php $this->specialtyIDForDoctorCard = (isset($specialtyIDForDoctorCard)) ? $specialtyIDForDoctorCard : null; ?>
            <?php $this->counter = $counter; ?>
            <?php $this->doctor = $doctor; ?>
            <?php $this->is_virtual = true; ?>
            <?php $this->is_closed_card = (isset($is_close_card) && $is_close_card == 1) ? 1 : null; ?>
            <?php
            if (isset($clinic)) {
                $this->clinic = $clinic;
                $this->clinic_id = $clinic->getId();
            }
            ?>

            <?php $this->specialty_id = (isset($specialty_id)) ? $specialty_id : null; ?>
            <?php $this->purpose_of_visit_id = (isset($purpose_of_visit_id)) ? $purpose_of_visit_id : null; ?>
            <?php $this->columned_list = 1; ?>
            <div class="col-lg-6 col-md-6 col-sm-12">
                <?php $this->block('doctor/card_big'); ?>
            </div>
            <?php if ($counter % 2 == 0): ?>

            </div>
        <?php endif; ?>
        <?php $counter++; ?>
    <?php endforeach; ?>

    <?php if ($counter % 2 == 0): ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php
if (!empty($diseases_specialization['diseases_group']) && count($diseases_specialization['diseases_group']) > 0) {
    $this->block('disease/blocks/diseases-groups');
}
?>

