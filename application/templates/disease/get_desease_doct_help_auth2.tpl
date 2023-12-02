<div align="center">
<div style="width:50%">
<ul class="todo-list" style="text-align: left;">
        <li style="color: #000000">
        Выбрать подходящего врача
	<?php foreach ($disease_specialties as $specialty) { ?>
		<a class="disease-doctor des-page <?php if ($specialty->is_adult) { ?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female) { ?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn) { ?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Right" data-text="<?php echo $specialty->plural_name; ?>" data-url="<?php echo $specialty->specialtyUrl ?>" href="<?php echo $specialty->specialtyUrl ?>"><?php echo $specialty->name; ?></a>
	<?php
	break;
	} ?>
        </li>
        <li style="color: #000000">Сдать анализы</li>
        <li style="color: #000000">Получить от врача схему лечения</li>
        <li style="color: #000000">Выполнить все рекомендации</li>
</ol>
<br><br>
</p>

<?php foreach ($disease_specialties as $specialty) { ?>
	<a class="btn-double-floor des-page disease-doctor <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn) { ?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-action-for-counters="find-doctor" data-category-counters="find-doctor" data-action="FindDocButton" data-position="Right" data-url="<?php echo $specialty->specialtyUrl ?>" data-id="<?php echo $specialty->specialty_id; ?>" href="<?php echo $specialty->specialtyUrl ?>">
		<?php $btn_text = (!$disease_green_btn)?'Записаться к врачу '.$specialty->dative_name:'Найти врача '.$specialty->genitive_name?>
		<span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
	</a>
<?php
 break;
 } ?>
</div>
</div>