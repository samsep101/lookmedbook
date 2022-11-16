<?php
    /**
     * @var ClinicModel[] $clinics
     * @var string $page_url
     * @var string $query
     * @var int $pages_total
     * @var int $current_page
     */
?>

<?php if ($clinics): ?>

    <ul class="updates-list">
        <?php foreach ($clinics as $clinic): ?>
            <li>
                <?php echo ModeratePageLinkViewHelper::getClinicOrDoctorLinkView($clinic, 'clinic'); ?>
                <?php echo RegionStatusViewHelper::getStatusImage($clinic->clinic_status_id); ?>
            </li>
        <?php endforeach?>
    </ul>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.pager a').click(function(){
                var page = $(this).data('page');

                queryString.set('page', page);
                queryString.load();

                return false;
            });
        });
    </script>
    <div class="pager">
        <?php echo PagingViewHelper::paging($page_url.'?query='.$query.'&city_id='.$city_id.'&page=:page:', $pages_total, $current_page); ?>
    </div>
<?php else:?>
    <p class="no-results">По вашему запросу клиник не найдено</p>
<?php endif?>