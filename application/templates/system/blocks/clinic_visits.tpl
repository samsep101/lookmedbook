
<?php $number = 1; ?>
<?php if (isset($visit_to_clinic)): ?>
    <?php foreach($visit_to_clinic as $visit): ?>
        <table class="visit-table visits-table-<?php echo $number; ?>" style="float: left; width: 100%; margin-bottom: 30px; display: none;">
            <caption style="text-align: left; font-weight: bold;">
                <span class="clinic-name-<?php echo $number; ?>"></span>
                <a style="text-decoration:none;" class="docx-href-<?php echo $number; ?>"><input type="button"  value="Выгрузить docx"></a>
            </caption>
            <thead class="visits-head-<?php echo $number; ?>">

            </thead>

            <tbody class="visit-information-<?php echo $number; ?>">

            </tbody>

            <?php $number++; ?>
        </table>
    <?php endforeach; ?>
<?php endif; ?>