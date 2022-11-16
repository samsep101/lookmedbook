<div class="container-fluid">
    <div class="row">
        <ul class="col-lg-12 resposive-body__doctors js-doctor-slider">
        <?php $this->columned_list = 0; ?>
        <?php foreach($doctors AS $dValue) { ?>
            <li>
                <?php $this->doctor = $dValue; ?>
                <?php $this->block('doctor/card_big'); ?>
            </li>
        <?php } ?>
        </ul>
    </div>
</div>

