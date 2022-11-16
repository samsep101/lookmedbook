<p>Врач
	<?php foreach ($disease_specialties as $specialty) { ?>
		<a class="disease-doctor des-page <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="disease-right-doctor" data-action="FindDocLink" data-position="Right" href="<?php echo $specialty->specialtyUrl ?>" data-text="<?php echo $specialty->plural_name; ?>"><?php echo $specialty->name; ?></a>
	<?php
	break;
	} ?>
	поможет при лечении заболевания
</p>
<?php foreach ($disease_specialties as $specialty) { ?>
	<a class="btn-double-floor des-page disease-doctor <?php if ($specialty->is_adult){?>adult-block male-block female-block <?php }?><?php if ($specialty->is_male){?>male-block <?php }?><?php if ($specialty->is_female){?>female-block <?php }?><?php if ($specialty->is_children){?>children-block <?php }?><?php if ($specialty->is_newborn){?>newborn-block <?php }?><?php if ($specialty->is_pregnant){?>pregnant-block<?php }?>" data-id="<?php echo $specialty->specialty_id; ?>" data-category-counters="find-doctor" data-action-for-counters="find-doctor" data-action="FindDocButton" data-position="Right" href="<?php echo $specialty->specialtyUrl ?>">
		<?php $btn_text = (!$disease_green_btn)?'Записаться к '.$specialty->dative_name:'Найти '.$specialty->genitive_name?>
		<span <?php echo ButtonPaddingHelper::getWideButtonSpecialtyPadding($specialty->dative_name);?> class="just-text"><?php echo $btn_text;?></span>
	</a>
<?php
 break;
 }
?>
