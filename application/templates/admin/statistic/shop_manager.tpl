<?php
	/**
	 * @var View $this
	 * @var array $statistic
	 */
?>
<div style="margin-botton: 20px;">
	<a href="/admin/statistic/getShopManagerFile">Выгрузить в *.csv</a>
</div>

<?php if($statistic): ?>
	<?php foreach($statistic as $user => $v): ?>
		<h2 style="font-size:16px;">Пользователь <?php echo $user; ?></h2>
		<?php if($v): ?>
			<table class="statistic-table">
				<thead>
					<th>
						Дата
					</th>
					<th>
						Фотографий загружено
					</th>
					<th>
						Заполнено информацией
					</th>
				</thead>
				<?php foreach($v as $v1): ?>
				<tr>
					<td>
						<?php echo $v1['date']; ?>
					</td>
					<td>
						<?php echo $v1['uploaded_image_count']; ?>
					</td>
					<td>
						<?php echo $v1['status_confirmed_count']; ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		<?php else: ?>
			нет действий
		<?php endif; ?>
	<?php endforeach; ?>
<?php else: ?>
	Данных нет
<?php endif; ?>