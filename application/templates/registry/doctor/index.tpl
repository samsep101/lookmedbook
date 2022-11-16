<?php $param =  (RegistryAccessHelper::checkManagerAuth()) ? '?clinic_id='.$clinic_id : ''; ?>
<?php $clinic_id = (RegistryAccessHelper::checkManagerAuth()) ? $clinic_id : null; ?>

<script type="text/javascript">
    $(document).ready(function(){
        var form_controller = new DoctorListController(<?php echo $clinic_id; ?>);
        form_controller.init();
    });
</script>

<div class="fields-block flo">
    <p>Врачи клиники</p>
    <div class="doctor-list-form flo">
        <input type="text" class="fio-field" placeholder="ФИО врача">
        <input class="find-doctor" type="button" value="Найти">

        <label>Специализация</label>
            <select class="specialty-pick">
                <option value="">Все</option>
                <?php if ($specialties):?>
                    <?php foreach ($specialties as $specialty):?>
                        <option value="<?php echo $specialty->getId(); ?>"><?php echo $specialty->name; ?></option>
                    <?php endforeach?>
                <?php endif?>
            </select>

        <a class="btn-appoint" href="/registry/doctor/add<?php echo $param; ?>">Добавить врача</a>
    </div>

    <?php if ($doctors):?>
        <div class="doctor-list">

        </div>
    <?php endif?>

</div>