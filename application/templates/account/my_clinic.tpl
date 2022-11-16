<script type="text/javascript">
    $(document).ready(function(){
        var my_clinic_controller = new PersonalRoomMyClinicSearchFormController("<?php echo $current_account->getId(); ?>");
        my_clinic_controller.init();
    });
</script>

<div class="inner-3">
    <div class="cab-page">

        <?php $this->active_top_menu = 'my_clinics'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <div class="cab-cards">
            <!--<div class="select-options flo" id="clinic_search_form">
                <div class="sel-box">
                    <select data-placeholder="Цель визита" class="chzn-select" id="purpose_of_visit" style="width:407px;">
                        <option value=""></option>
                        <option value="0">Все</option>
                        <?php if (count($purpose_of_visit_names)): ?>
                            <?php foreach ($purpose_of_visit_names as $name): ?>
                                <?php echo '<option value="'.$name->id.'">'.$name->name.'</option>'; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="sel-box">
                    <select data-placeholder="Тип клиники" class="chzn-select" id="type_of_clinic" style="width:407px;">
                        <option value=""></option>
                        <option value="0">Все</option>
                        <?php /*if ($clinic_types): ?>
                            <?php foreach ($clinic_types as $key => $value): ?>
                                <?php echo $value; ?>
                            <?php endforeach; ?>
                        <?php endif;*/ ?>
                    </select>
                </div>
            </div>-->
            <div class="cards-section">
                <h2>Я ходил на прием</h2>

                <div id="visited_clinic_container">
                </div>

                <div id="more_visited_clinics" style="display: none">
                    <a class="view-more" id="view_more_visited_clinics" href="javascript:void(0)"><i></i>Показать ещё 10 клиник</a>
                </div>

            </div>
            <div class="cards-section">
                <h2>Отложено в закладки</h2>

                <div id="my_clinics_container">
                    <div class="no_result">
                    </div>
                </div>
                <div id="more_my_clinics" style="display: none">
                    <a class="view-more" id="view_more_my_clinics" href="javascript:void(0)"><i></i>Показать ещё 10 клиник</a>
                </div>
            </div>
        </div>
    </div>
</div>