<?php
/**
 * @var \DiseaseBlockModel[] $disease_blocks_content
 * @var \SpecialtyModel[] $disease_specialties
 * @var \DiseaseModel $disease
 */
?>
<?php foreach ($disease_blocks_content as $block): ?>
    <?php $field_anchor = 'b'.$block->id;?>
    <?php if ($block->disease_block_type_id == DiseaseDraftBlockModel::DISEASE_BLOCK_DIAGNOSTIC): ?>
        <div class="section">
            <noindex>
                <div class="like_p with-sign">
                    <?= SITE_NAME; ?> напоминает: что данный материал размещен исключительно в ознакомительных целях и не заменяет консультацию врача!
                </div>
            </noindex>
            <?php if ($disease_specialties):?>
                <div class="doing-box not-hide in-middle">
                    <ol class="todo-list">
                        <li>
                            <?php if (!Acc::isAuthed()): ?>
                            <p>Врач
                                <?php foreach ($disease_specialties as $specialty):?>
                                    <a class="disease-doctor des-page <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Center" data-text="<?php echo $specialty->plural_name; ?>" data-url="/doctor?specialty_id=<?php echo $specialty->specialty_id; ?>&time_of_visit=any&sort_by=recomend" href="/doctor?specialty_id=<?php echo $specialty->specialty_id; ?>&time_of_visit=any&sort_by=recomend"><?php echo $specialty->name; ?></a>
                                <?php
                                break;
                                endforeach;?>
                                поможет при лечении заболевания
                            </p>
                            <?php foreach ($disease_specialties as $specialty):?>
                                <a class="btn-double-floor des-page disease-doctor
                                <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?>
<?php if ($specialty->is_male){?>male-block <?php }?>
<?php if ($specialty->is_female){?>female-block <?php }?>
<?php if ($specialty->is_children){?>children-block <?php }?>
<?php if ($specialty->is_newborn){?>newborn-block <?php }?>
<?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>"

                                   data-action-for-counters="find-doctor"
                                   data-category-counters="find-doctor"
                                   data-action="FindDocButton"
                                   data-position="Right"
                                   data-url="<?php echo $specialty->specialtyUrl ?>"
                                   data-id="<?php echo $specialty->specialty_id; ?>"
                                   onclick="recordController.showForm(0,0,<?=$disease->id?>)"
                                   >
  									<?php $btn_text = (!$disease_green_btn)?'Записаться к врачу '.$specialty->dative_name:'Найти врача '.$specialty->genitive_name?>
                                    <span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
                                </a>
                            <?php
                             break;
                             endforeach;?>
                            <?php else: ?>
                            <p>Врач
                                <?php foreach ($disease_specialties as $specialty):?>
                                    <a class="disease-doctor des-page <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Center" href="/doctor?specialty_id=<?php echo $specialty->specialty_id; ?>&time_of_visit=any&sort_by=recomend" data-text="<?php echo $specialty->plural_name; ?>"><?php echo $specialty->name; ?></a>
                                <?php
                                 break;
                                 endforeach;?>
                                поможет при лечении заболевания
                            </p>
                            <?php foreach ($disease_specialties as $specialty):?>
                                <a class="btn-double-floor des-page disease-doctor <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="find-doctor" data-action="FindDocButton" data-position="Center" href="/doctor?specialty_id=<?php echo $specialty->specialty_id; ?>&time_of_visit=any&sort_by=recomend">
 									<?php $btn_text = (!$disease_green_btn)?'Записаться к врачу '.$specialty->dative_name:'Найти врача '.$specialty->genitive_name?>
                                    <span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
                                </a>
                            <?php
                            break;
                            endforeach;?>
                            <?php endif; ?>
                        </li>
                    </ol>
                </div>
            <?php endif?>
        </div>
    <?php endif; ?>

    <div class="section" id="<?php echo $field_anchor; ?>">
        <h2>
            <?php if ($block->hasExtendableName() || $this->section !== 'default'):
                    echo $block->disease_block_type->name . ' ' . $disease->h2_extend;
                else:
                    echo $block->disease_block_type->name;
                endif; ?>
        </h2>
        <div class="like_p">
            <?php $block->content = preg_replace('/<br \/>/','',$block->content);?>
            <?php $block->content = preg_replace('/<br\/>/','',$block->content);?>
            <?php echo html_entity_decode($block->content,ENT_COMPAT,'UTF-8'); ?>
        </div>
    </div>
    <?php
    if ($actions && $block->disease_block_type_id == DiseaseDraftBlockModel::DISEASE_BLOCK_SYMPTOMS): ?>
        <?php $this->block('disease/blocks/actions'); ?>
    <?php endif?>
    <?php /***** pediatr banner *****/ ?>
    <?php $is_children = false; ?>
    <?php foreach ($disease_specialties as $specialty):?>
    	<?php if ($specialty->is_children) { $is_children = true; break; } ?>
    <?php endforeach;?>
    <?php switch ($block->disease_block_type_id) {
        case DiseaseDraftBlockModel::DISEASE_BLOCK_DIAGNOSTIC:
            if ($disease->page_ad_after_diagnostics) {
                echo $disease->page_ad_after_diagnostics;
            }
            break;
        case DiseaseDraftBlockModel::DISEASE_BLOCK_FORMS:
            if ($disease->page_ad) {
                echo $disease->page_ad;
            } else {
                $this->block('disease/blocks/adv_after_forms_slickjump');
            }
            break;
    }
endforeach;
