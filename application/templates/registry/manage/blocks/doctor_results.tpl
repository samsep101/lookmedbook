<?php if ($doctors):?>
        <?php $doctors_count = 0;?>
        <ul class="updates-list">
            <?php foreach ($doctors as $doctor):?>
                <?php if (($doctors_count != 10)):?>
                    <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($doctor, 'doctor');?></li>
                <?php endif?>
                <?php $doctors_count++;?>
            <?php endforeach?>
        </ul>
    <?php
     echo PagingViewHelper::paging($page_url.'?query='.$query.'&page=:page:', $pages_total, $current_page);
    ?>
<?php endif?>