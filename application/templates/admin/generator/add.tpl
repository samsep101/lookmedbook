<p class="actionBar">
		<span class="button">
			<a href="<?php echo ADMIN_FOLDER . '/' . $this->dataModel->getModelName(); ?>"><img class="cursorPointer"
                                                                                       src="/media/admin/icons/clipboard-audit-24-ns.png"
                                                                                       align="absmiddle"
                                                                                       border="0"/></a>
			<a href="<?php echo ADMIN_FOLDER . '/' . $this->dataModel->getModelName(); ?>"><?php echo $this->dataModel->getListTitle(); ?></a>
		</span>
    <?php  if ($acl->hasRights($this->dataModel->getModelName(), 'add')) { ?>
    <span class="button">
			<img class="cursorPointer taskIndexLink" src="/media/admin/icons/badge-circle-plus-24-ns.png"
                 align="absmiddle"/>
			<a href="<?php echo $this->dataModel->getModelName(); ?>/add/"><?php echo $title; ?></a>
		</span>
    <?php  }?>
</p>
<div style="clear:both"></div>

<?php //ini_set("memory_limit", "256M");?>
<script language="JavaScript" src="/media/js/admin/form_edit.js"></script>

<form action="<?php echo ADMIN_FOLDER; ?>/<?php echo $this->dataModel->getModelName(); ?>/add/?destination=<?php echo $destination; ?>" method="POST"
      enctype="multipart/form-data">
    <input type="hidden" name="csrf" value=<?php echo isset($csrf) ? '\''.$csrf.'\'' : 'null'; ?>>

    <?php echo $this->dataModel->getAddTooltip(); ?>

    <?php $this->block('admin/blocks/validation-errors'); ?>
    <?php foreach ($tabs as $tabId=> $tabName) { ?>

    <div id="tab-<?php echo $tabId; ?>" class="generatorEditDiv">
        <h4><?php echo $tabName; ?></h4>
        <br/>

        <div>
            <table class="generatorTable" cellpadding="3" cellspacing="3">
                <?php foreach ($tabFields[$tabName] as $fieldName=> $field) { ?>
                <?php  if ($field->hasLayout) { ?>
                    <tr>
                        <?php if ($this->dataModel->checkUserFilter($fieldName) && Acl::userGrant($this->dataModel->getModelName() . '_list_my')) { ?>
                        <input name="form[<?php echo $fieldName; ?>]" type="hidden" value="<?php echo Acl::userId(); ?>"/>
                        <?php  } else { ?>
                        <td class="label"><?php echo $this->dataModel->getFieldLabel($fieldName); ?>:</td>
                        <td><?php echo $field->getFormValue($model->$fieldName); ?></td>
                        <?php  }?>
                    </tr>
                    <?php  } ?>
                <?php  }?>
            </table>
        </div>
    </div>
    <?php  }?>
    </div>
    <br/>

    <p><input type="button" onclick="$($($(this).parent()).parent()).submit()" value="Создать" id="submit_action"></p>
</form>