<?php
    /**
     * @var DoctorModel[] $doctors
     * @var string $full_name
     * @var int $clinic_id
     * @var int $to_clinic_id
     */
?>
<?php $param =  (RegistryAccessHelper::checkManagerAuth()) ? '&clinic_id='.$clinic_id : ''; ?>

<?php if ($doctors):?>
    <div class="doc_alr_exist">
            <div class="doc_block">
                <?php foreach ($doctors as $doctor):?>
                <?php if (RegistryAccessHelper::checkSuperManagerAccess()):?>
                    <?php $doctor_href = '/registry/doctor/information?id='.$doctor->getId().$param;?>
                <?php else:?>
                    <?php $doctor_href = 'javascript:void(0)';?>
                <?php endif;?>
                <?php echo DoctorAvatarViewHelper::viewOnCard($doctor, 74, 111, $param, true); ?>
                <div class="doc_info">
                    <p class="name"><a href="<?php echo $doctor_href?>"><?php echo $doctor->moderate_full_name; ?></a></p>
                    <p class="spec"><?php echo $doctor->specialties_names?></p>
                    <input class="btn-appoint add-new-bound" type="button" data-clinic-id="<?php echo $to_clinic_id;?>" data-doctor-id="<?php echo $doctor->getId();?>" name="cancel" value="Добавить врача в клинику"/>
                </div>
                <?php endforeach;?>
            </div>
        <div class="create_block">
            <p class="txt">или</p>
            <input class="btn-1 create-new-doctor" type="button" name="cancel" value="Создать нового врача"/>
            <p class="txt">с именем: <?php echo $doctor->moderate_full_name; ?></p>
        </div>
    </div>
<?php endif;?>