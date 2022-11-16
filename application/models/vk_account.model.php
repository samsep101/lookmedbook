<?php
	/**
	 * @property int $id
	 * @property int $account_id
	 * @property AccountModel $account
	 * @property string $uid
	 * @property string $first_name
	 * @property string $last_name
	 * @property string $profile_url
	 * @property string $home_phone
	 * @property string $activity
	 * @property int $count_groups
	 * @property int $count_friends
	 * @property string $relation_type
	 * @property string $relation_partner_uid
	 * @property string $relation_partner_first_name
	 * @property string $relation_partner_last_name
	 * @property string $interests
	 * @property string $movies
	 * @property string $tv
	 * @property string $books
	 * @property string $games
	 * @property string $about
	 * @property int $is_account_connected
	 *
	 * @property string $full_name
	 */
    class VkAccountModel extends DynamicModel {

		protected function _field_full_name()
		{
			$this->full_name = $this->last_name . ' ' . $this->first_name;
			return $this->full_name;
		}
	}