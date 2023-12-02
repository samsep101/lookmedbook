<script type="text/javascript">
    $(document).ready(function () {
        var my_doctor_controller = new PersonalRoomMyDoctorSearchFormController("<?php echo $current_account->getId(); ?>");
        my_doctor_controller.init();
    });
</script>

<div class="inner-3">
    <div class="cab-page">
        <?php $this->active_top_menu = 'my_doctors'; ?>
        <?php $this->block('blocks/personal-room-top-menu'); ?>

        <div class="cab-cards">

            <!--<div class="select-options flo" id="doctor_search_form">

                <div class="sel-box">
                    <select data-placeholder="Специальность" name="specialty_id" class="chzn-select" style="width:407px;">
                        <option value=""></option>
                        <?php $this->specialties = $specialties; ?>
                        <?php $this->block('blocks/specialties_options'); ?>
                    </select>
                </div>

                <div class="sel-box">
                    <select data-placeholder="Клиника" name="clinic_id" class="chzn-select" style="width:407px;">
                        <option value=""></option>
                        <option value="0">Все</option>
                        <?php $this->clinics = $clinics; ?>
                        <?php $this->block('blocks/clinics_options'); ?>
                    </select>
                </div>

                <div class="sel-box" id="purpose_of_visit_block">
                    <select data-placeholder="Цель визита" name="purpose_of_visit_id" class="chzn-select" style="width:407px;">
                        <option value=""></option>
                    </select>
                </div>

                <div class="sel-box">
                    <select data-placeholder="Время" name="time_of_visit" class="chzn-select" style="width:407px;">
                        <option value=""></option>
                        <option value="any">в любое время</option>
                        <option value="weekend">в выходные дни</option>
                        <option value="evening">вечером</option>
                        <option value="leave_house">выезд на дом</option>
                        <option value="morning">утром</option>
                    </select>
                </div>

            </div>-->

            <div class="cards-section" id="visited-doctors-block">
                <h2>Я ходил на прием</h2>

                <div class="item-row flo" id="visited_doctors_container">
                </div>

                <div class="view-more-block" style="display: none">
                    <a class="view-more" id="more_visited_doctors" href="javascript:void(0)"><i></i>Показать ещё</a>
                </div>
            </div>

            <div class="cards-section" id="bookmarks-block">
                <h2>Отложено в закладки</h2>

                <div class="item-row flo">
                    <div id="my_doctors_container">
                        <div class="no_result">
                        </div>
                    </div>
                </div>

                <div class="view-more-block" style="display: none">
                    <a class="view-more" id="more_my_doctors" href="javascript:void(0)"><i></i>Показать ещё</a>
                </div>

            </div>
        </div>
    </div>
</div>