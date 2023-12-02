<?php
    /**
     * @var ClinicModel[] $user_clinics
     * @var int $pages_total
     * @var int $current_page
     * @var string $page_url
     * @var int $city_id
     * @var UserModel $user
     */
?>

<?php $clinics_count = 0; ?>

<?php foreach($user_clinics as $clinic): ?>
    <?php if ($clinics_count < 15): ?>
        <tr class="clinic-row-<?php echo $clinic->getId(); ?>">
            <td>
                <?php echo $clinic->name; ?>
            </td>
            <td>
                <a href="javascript:void(0);" class="delete-clinic" data-id="<?php echo $clinic->getId(); ?>" >удалить</a>
            </td>
        </tr>
    <?php endif; ?>
    <?php $clinics_count++; ?>
<?php endforeach; ?>

<tr>
    <td colspan="2">
        <?php echo PagingViewHelper::paging($page_url.'?user_id='.$user->getId().'&city_id='.$city_id.'&page=:page:', $pages_total, $current_page); ?>
    </td>
</tr>