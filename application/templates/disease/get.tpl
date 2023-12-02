<?php
	/**
	 * @var int $counter_number
	 */
?>
<?php
if (isset($_COOKIE['already_registred_account'])) {
	$already_registred_account = 1;
} else {
	$already_registred_account = 0;
}
?>

<script>
	$(function() {
		<?php if (isset($disease_tabs_flags)) { ?>
			var first_tab = 0;
			first_tab = '<?php echo $card; ?>';
		<?php } ?>

		$(document).ready(function(){
			var disease_controller = new DiseasePageController('<?php echo $disease->id?>',<?php echo (!Acc::isAuthed()) ? 1 : 0; ?>, <?php echo $already_registred_account; ?>, first_tab, "<?php echo (isset($label_for_counters)) ? $label_for_counters : ''; ?>");
			disease_controller.changeSpecialtyBlock(first_tab);
			disease_controller.counter_number = <?php echo $counter_number;?>;
			disease_controller.disease_green_btn = <?php echo ($disease_green_btn)?1:0; ?>;
			disease_controller.init();
		});


		<?php if ($disease->my_disease) { ?>
			$('.btn-bookmark-illness').addClass('btn-bookmark-added');
			$('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt txt-added">В закладках</span>');
		<?php } elseif (Acc::isAuthed()) { ?>
			$('.btn-bookmark-illness').removeClass('btn-bookmark-added');
			$('.btn-bookmark-illness').html('<i class="icon-add"></i> <span class="txt">Добавить в закладки</span>');
		<?php } ?>

    $('.illness-nav').affix({
        offset: {
          top: function() { return $('#hero').height(); }
        }
      });

	});
</script>
<link as="style" rel="preload" href="/media/css/product-article.css?rnd=<?= Articles_Viewer::RND?>" type="text/css" class="lazy-css">
<script type="text/javascript" src="/media/js/articles-spoiler.js?rnd=<?= Articles_Viewer::RND?>" async></script>

<?php if (!$disease->is_active) : ?>
<div class="error-plate">
    <p class="text-center">Отображение статьи отключено</p>
</div>
<?php endif; ?>
<?php if ($disease) { 	include('get_desease.tpl'); } ?>
<?php $this->block('blocks/adv/content_page'); ?>


