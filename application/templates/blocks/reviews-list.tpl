<?php
/**
 * @var VisitRatingModel[] $reviews
 * @var DoctorModel|null $doctor
 */
?>
<?php $counter = 1; ?>
<?php foreach($reviews as $review): ?>
	<div id="doctor-review-<?php echo (isset($review->visit) and isset($review->visit->rating)) ? $review->visit->rating->getId() : rand(1,10000); ?>" class="review-box flo <?php if ($counter % 2 == 0) echo 'fright';?>" itemprop="review" itemscope itemtype="http://schema.org/Review">
		<span class="chk-pic"></span>

		<div class="aside">
			<p class="name">Оценка пациента</p>

			<div class="rating-item">
				<p>Сервис в регистратуре</p>
				<?php echo RateViewHelper::viewDoctorReviewRate($review->service_at_the_reception); ?>
			</div>
			<div class="rating-item">
				<p>Время ожидания</p>
				<?php echo RateViewHelper::viewDoctorReviewRate($review->waiting_time); ?>
			</div>
			<div class="rating-item">
				<p>Атмосфера</p>
				<?php echo RateViewHelper::viewDoctorReviewRate($review->relationship); ?>
			</div>
			<div class="rating-item">
				<p>Соответствие цене</p>
				<?php echo RateViewHelper::viewDoctorReviewRate($review->value_for_money); ?>
			</div>
			<div class="rating-item">
				<p>Диагноз и дальнейшее лечение ясны</p>
				<?php echo RateViewHelper::viewDoctorReviewRate($review->diagnosis_is_clear); ?>
			</div>

			<?php
			$accountRating = ($review->service_at_the_reception +
					$review->waiting_time +
					$review->relationship +
					$review->value_for_money +
					$review->diagnosis_is_clear) / 5;
			?>
      <span itemprop="reviewRating" itemtype="http://schema.org/Rating" itemscope>
        <meta itemprop="ratingValue" content="<?= $accountRating?>">
      </span>
		</div>

		<div class="review-cont">
			<?php if (isset($doctor) && $doctor->advice_rate): ?>
				<div class="advice">
					<?php echo RateViewHelper::viewAdvise($doctor->advice_rate); ?>
					<span>Посоветуют друзьям</span>
				</div>
			<?php endif; ?>
			<div class="comment-data">
				<img src="/media/images/account_image.gif" alt=""/>
				<p class="author-name" itemscope  itemtype="http://schema.org/Person" itemprop="author"><span itemprop="name"><?= $review->getAccountNameOrFallback() ?></span></p>
				<span class="comment-date"><time itemprop="datePublished" datetime="<?php echo date('Y-m-d', strtotime($review->dt)); ?>"><?php echo DateViewHelper::date($review->dt, 'dd.mm.YYYY'); ?></time></span>
			</div>
			<p class="comment-text"><span itemprop="description"><?php echo $review->text;?></span></p>
		</div>

  </div>

	<?php if($counter % 2 == 0) echo '<div class="clearfix"></div>'; ?>
	<?php $counter++; ?>
<?php endforeach;?>

<div id="review-container"></div>
