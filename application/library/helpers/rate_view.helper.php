<?php

class RateViewHelper
{
	public static function view($rate, $isCard = 0, $is_best=0) {
		if(!$rate) {
			return '';
		}
		$rate = ($rate > 5 or $is_best>0) ? 5 : $rate;
		$bonus_class = $is_best?'is_best':'';
		if($isCard) {
			return '<div class="rating-line '.$bonus_class.'"><span style="width:' . ($rate * 20) . '%"></span></div>';
		} else {
			return '<div class="rating-line '.$bonus_class.'">
					<span class="rating-foreground" style="width:' . ($rate * 20) . '%">
						<meta itemprop="ratingCount" content="' . $rate . '" />
					</span>
				</div>';
		}
	}

	public static function viewSmall($rate) {
		if(!$rate) {
			return '';
		}
		return '<div class="rating-line-sm"><span style="width:' . ($rate * 20) . '%"></span></div>';
	}

	public static function viewAdvise($rate) {
		return '<i class="count">' . ($rate * 100) . '%</i>';
	}

	public function viewDoctorAdvise($rate) {
		return '<div class="advice"> <i class="count">' . ($rate * 100) . '%</i>';
	}

	public static function viewDoctorReviewRate($rate) {
		return '<div class="rating-line"><span style="width:' . (100 / 5 * $rate) . '%;"></span></div>';
	}
}