<script type="text/javascript">
    $(document).ready(function () {
        var page_controller = new AccountMainPageController();
        page_controller.init();
    });
</script>
<div class="inner flo">
    <div class="main-column">
        <div class="main-page-tooltip">
            <ul>
                <li><i><img src="/media/images/tooltip_icon1.png" alt=""/></i> <span>Найди врача</span></li>
                <li class="menu2"><i><img src="/media/images/tooltip_icon2.png" alt=""/></i> <span>Выбери время</span>
                </li>
                <li class="menu3"><i><img src="/media/images/tooltip_icon3.png" alt=""/></i> <span>Запишись <br>
              на прием</span></li>
            </ul>
        </div>
        <div class="search-form">
            <ul class="tabs flo">
                <li class="active">
                    <span id="find_doctor_tab"><i></i>Найти врача</span>
                </li>
                <li>
                    <span id="find_clinic_tab"><i></i>Найти клинику</span>
                </li>
            </ul>

                <div class="section visible account-search-form-doctor">
                    <?php echo $this->show_title = false; ?>
                    <?php $this->account_page = 1; ?>
                    <?php $this->block('doctor/blocks/search-form'); ?>
                </div>
                <div class="section find-clinic account-search-form-clinic">
                    <?php $this->block('clinic/blocks/search-form'); ?>
                </div>

        </div>
        <?php $this->block('blocks/coming-soon'); ?>
    </div>
    <div class="side-column">
        <?php if ($account->coming_visits): ?>
            <?php $block_id = $account->coming_visits[count($account->coming_visits)-1]->getId(); ?>
            <?php if (!isset($_COOKIE['block-visit-'.$block_id])): ?>
                <div class="info-block info-block-red info-block-visit">
                    <!--<span class="close" data-id="visit-<?php echo $block_id; ?>"></span>-->

                            <div class="cont pink_cont">
                                <?php foreach($account->coming_visits as $visit): ?>
                                    <?php if (!$this->cache->start('visit_notify_'.$visit->getId(), 'visit_notify')): ?>
                                        <div class="reg-info">
                                            <p><strong><a href="/account/doctorsVisitsComing">Запись на прием</a></strong> <br>
                                                <?php echo DateViewHelper::date($visit->dt, 'number'); ?> <br/>  <?php if ($visit->visit_start_time) echo DateViewHelper::date($visit->visit_start_time, 'time'); ?><br>
                                                <?php if ($visit->specialty_id):?>
                                                    <?php echo $visit->specialty->name; ?><br>
                                                <?php elseif ($visit->doctor_id && $visit->doctor_id != DoctorModel::RESERVED_DOCTOR_SLOT):?>
                                                    <?php echo $visit->doctor->getSpecialtyByClinicId($visit->clinic_id)->name; ?><br>
                                                <?php endif;?>
                                                <?php if ($visit->doctor_id && $visit->doctor_id != DoctorModel::RESERVED_DOCTOR_SLOT):?>
                                                    <a href="<?php echo DoctorPageLinkViewHelper::getLink($visit->doctor); ?>"> <?php echo $visit->doctor->last_name.' '. $visit->doctor->first_name.' '.$visit->doctor->second_name; ?></a><br>
                                                <?php endif;?>
                                                <?php if ($visit->purpose_of_visit_id):?>
                                                    <?php echo $visit->purpose_of_visit->name; ?>
                                                <?php endif;?>
                                            </p>
                                            <?php if ($visit->clinic_id):?>
                                                <p><strong><a href="<?php echo ClinicPageLinkViewHelper::getLink($visit->clinic); ?>"><?php echo $visit->clinic_name; ?></a></strong>
                                                <div class="r8374"></div>
                                                    <?php if ($visit->clinic->metro_station): ?>
                                                        <br />
                                                        <?php if ($visit->clinic->metro_station->metro_branch): ?>
                                                            <?php echo MetroBranchIconViewHelper::getImage($visit->clinic->metro_station->metro_branch)?>
                                                        <?php endif; ?>
                                                        <?php echo $visit->clinic->metro_station->name; ?>
                                                    <?php endif; ?>
                                                <br/>
                                                <?php echo $visit->clinic->address; ?>
                                                </p>
                                            <?php endif;?>
                                            <a class="review-link rev-popup-open cancel-visit" data-id="<?php echo $visit->getid(); ?>"><span></span>Отменить</a>

                                        </div>
                                    <?php $this->cache->end(); ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>

                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($account->last_uncommented_visit): ?>
            <?php if (!isset($_COOKIE['block-uncommented-'.$account->last_uncommented_visit->getId()])): ?>
                <?php if (!$this->cache->start('visit_uncommented_'.$account->last_uncommented_visit->getId(), 'visit_uncommented')): ?>
                    <div class="info-block info-block-red info-block-uncommented">
                        <span class="close" data-id="uncommented-<?php echo $account->last_uncommented_visit->getId(); ?>"></span>

                        <?php $unique_id = $account->last_uncommented_visit->getUniqueId().rand(0,100000); ?>
                        <div class="cont" id="visit-remind-block-<?php echo $unique_id; ?>">
                            <p><strong>Вы были на приеме у врача</strong> <?php echo DateViewHelper::date($account->last_uncommented_visit->dt, 'number'); ?></p>

                            <div class="doctor-item flo">
                                <div class="avatar">
                                    <?php if ($account->last_uncommented_visit->doctor_id && $account->last_uncommented_visit->doctor_id != DoctorModel::RESERVED_DOCTOR_SLOT):?>
                                        <?php echo DoctorAvatarViewHelper::view($account->last_uncommented_visit->doctor, 66, 64); ?>
                                    <?php elseif ($account->last_uncommented_visit->clinic_id):?>
                                        <?php echo ClinicAvatarViewHelper::view($account->last_uncommented_visit->clinic, 74, 31); ?>
                                    <?php endif;?>
                                </div>
                                <div class="descr">
                                    <p>
                                        <?php if ($account->last_uncommented_visit->doctor_id && $account->last_uncommented_visit->doctor_id != DoctorModel::RESERVED_DOCTOR_SLOT):?>
                                            <a href="<?php echo DoctorPageLinkViewHelper::getLink($account->last_uncommented_visit->doctor); ; ?>">
                                            <span class="post"><?php echo $account->last_uncommented_visit->doctor->specialties_names; ?> </span>
                                            <?php echo $account->last_uncommented_visit->doctor->full_name; ?></a>
                                        <?php elseif ($account->last_uncommented_visit->clinic_id && $account->last_uncommented_visit->specialty_id):?>
                                            <span class="post"><?php echo StringHelper::startProposalWord($account->last_uncommented_visit->specialty->name);?></span>
                                        <?php endif;?>
                                    </p>
                                    <a href="#add-review-popup-<?php echo $unique_id; ?>" id="<?php echo $unique_id; ?>" class="review-link rev-popup-open">
                                        <span></span>Оставить отзыв
                                    </a>
                                    <script>
                                        $(document).ready(function(){
                                            block_controller = new VisitRemindBlockController(<?php echo $account->last_uncommented_visit->getUniqueId(); ?>, '', <?php echo $unique_id; ?>);
                                            block_controller.init();
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php $this->cache->end(); ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>



        <div class="search-form">
            <ul class="tabs flo">
                <li class="active"><span><i></i>Мои врачи</span></li>
                <li><span><i></i>Мои клиники</span></li>
            </ul>
            <div class="box">
                <div class="section visible">
                    <?php if ($account->favorite_doctors): ?>
                        <?php $count = 0; ?>
                        <?php foreach($account->favorite_doctors as $doctor): ?>
                            <?php $count++; ?>
                            <?php if ($count > 2) break; ?>
                            <div class="doctor-item flo">
                                <div class="avatar">
                                    <?php echo DoctorAvatarViewHelper::view($doctor, 66, 64); ?></div>
                                <div class="descr">
                                    <p><a href="/doctor/get?id=<?php echo $doctor->getId(); ?>"><span class="post"><?php echo $doctor->specialties_names; ?></span> <?php echo $doctor->full_name; ?></a></p>
                                </div>
                            </div>

                        <?php endforeach; ?>
                        <p class="right-align"><a class="more-link" href="/account/my_doctor">Показать еще</a></p>
                    <?php else: ?>
                        <div class="empty-list">
                            <p>Добавьте врачей в свой список</p>
                            <a href="/doctor" class="find-doctor-btn">Найти врача</a> </div>
                    <?php endif; ?>


                </div>
                <div class="section">
                    <?php if ($account->favorite_clinics): ?>
                        <?php $count = 0; ?>
                        <?php foreach($account->favorite_clinics as $clinic): ?>
                            <?php $count++; ?>
                            <?php if ($count > 2) break; ?>
                            <div class="doctor-item flo">
                                <div class="avatar">
                                    <?php if($clinic->image_id):?>
                                        <img src="<?php echo $clinic->image->resize(66,64)->path; ; ?>" />
                                    <?php else:?>
                                        <img style="width:66px;height:64px" src="/media/images/no-photo.gif" />
                                    <?php endif?>
                                </div>
                                <div class="descr">
                                    <p><a href="/clinic/get?id=<?php echo $clinic->getId(); ?>"><span class="post"><?php echo $clinic->name; ?></span></a></p>

                                    <div class="address">
                                        <?php if ($clinic->metro_station): ?>
                                            <?php if ($clinic->metro_station->metro_branch): ?>
                                                <?php echo MetroBranchIconViewHelper::getImage($clinic->metro_station->metro_branch)?>
                                            <?php endif; ?>
                                            <?php echo $clinic->metro_station->name; ?>
                                        <?php endif; ?>
                                        <br />
                                        <?php echo $clinic->address; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <p class="right-align"><a class="more-link" href="/account/my_clinic">Показать еще</a></p>
                    <?php else: ?>
                        <div class="empty-list">
                            <p>Добавьте клиники в свой список</p>

                            <a href="/clinic" class="find-clinic-btn">Найти клинику</a>

                        </div>
                    <?php endif; ?>


                </div>
            </div>

    </div>
        <?php if ($account->selected_diseases): ?>
            <div class="read-block">
                <p><strong>Почитать о заболеваниях</strong></p>
                <ul>
                    <?php $count = 0; ?>
                    <?php foreach($account->selected_diseases as $disease): ?>
                        <?php $count++; ?>
                        <?php if ($count > 4) break; ?>
                        <li><a href="/disease/get?id=<?php echo $disease->getId();?>"><?php echo $disease->title; ?></a></li>
                    <?php endforeach; ?>
                </ul>
                <p class="right-align"><a class="more-link" href="/account/my_disease">Почитать еще</a></p>
            </div>
        <?php else: ?>
            <script>
                $(document).ready(function(){
                    var disease_search_controller2 = new DiseaseQuickSearchFormController(0,0);

                    disease_search_controller2.setInputElement($('#disease-quick-search2 .txt'));
                    disease_search_controller2.setDrowDownContainer($('#disease-quick-search2 .drop-menu'));
                    disease_search_controller2.setSubmitElement($('#disease-quick-search2 .submit-btn'));
                    disease_search_controller2.init();
                });
            </script>
            <div class="search-info" id="disease-quick-search2">
                <h5>Найти информацию о заболевании</h5>
                <div class="quick-search-block">
                    <form action="/search/results" method="GET">
                        <div class="fields flo">
                            <input type="text" class="txt" autocomplete="off" name="query" value="" />
                            <input type="submit" class="submit-btn" data-action-for-counters="main-right" value=""/>
                        </div>
                        <ul class="drop-menu">

                        </ul>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
