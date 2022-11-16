<?php
	/**
	 * @var View $this
	 * @var string $csrf
	 *
	 */
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $this->company; ?></title>
    <?php echo $this->block('admin/blocks/head');?>
	<script type="text/javascript">
		SessionInfo.csrf = '<?php echo (isset($csrf)) ? $csrf : ''; ?>';
	</script>
</head>
<body class="admin">

<div class="clear"></div>

<?php if(Acl::userId()){ ?>
    <img src="/media/images/eyes_owl_line_tr.png" id="logo">
    <p align="right"><a href="/admin/security/logout"><b>Выйти</b></a></p>


<table style="width:100%" cellpadding="0" cellspacing="0">
    <tr>
        <td id="leftMenu" style="width:240px" valign="top">
            <div id="tabs" class="tabs">
                <?php $this->block('admin/blocks/left-menu'); ?>
            </div>
        </td>

        <td valign="top" id="rightContent">
<?php }?>

<?php echo $this->content(); ?>

<?php if(Acl::userId()){ ?>
        </td>
    </tr>
</table>
<?php }?>
<div class="clear"></div>

<div id="message-box" class="message-box"></div>


<script type="text/javascript" src="/media/js/jquery.autocomplete.min.js?<?php echo RELEASE_NUMBER?>"></script>
<script type="text/javascript" src="/media/js/common.js?<?php echo RELEASE_NUMBER?>"></script>

</body>
</html>


