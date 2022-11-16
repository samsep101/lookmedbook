<?php if ($clinics):?>
    <?php $clinics_count = 0;?>
    <ul class="updates-list">
        <?php foreach ($clinics as $clinic):?>
            <?php if (($clinics_count != 10)):?>
                <li><?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($clinic, 'clinic'); ?></li>
            <?php endif?>
            <?php $clinics_count++;?>
        <?php endforeach?>
    </ul>
    <?php //Test::dump($pages_total); ?>
    <?php echo PagingViewHelper::paging($page_url.'?query='.$query.'&page=:page:', $pages_total, $current_page); ?>
<?php endif?>