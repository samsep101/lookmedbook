<?php
	class FamilyRelationModerateManager extends ModelManager
	{
		protected $table_name = 'family_relation_moderate';
		protected $model_name = 'FamilyRelationModerateModel';

    /**
		 * @return FamilyRelationModerateModel[]
		 */
		public function getListByAccountId($account_id)
		{
			$sql = 'SELECT *, family_relation_moderate.id as relation_id, account_phone.is_confirmed as phone_confirm
                    FROM ' . $this->table_name . '
                    INNER JOIN account ON account.id = family_relation_moderate.to_account_id
                    LEFT JOIN account_phone ON account.id = account_phone.account_id
                    WHERE family_relation_moderate.account_id = ' . (int)$account_id;
			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function checkExistsByAccountIdAndToAccountIdAndIsConfirmed($account_id, $to_account_id)
		{
			$sql = 'SELECT COUNT(*) as `result`
                    FROM family_relation_moderate
                    WHERE account_id = ' . (int)$account_id . '
                    AND to_account_id = ' . (int)$to_account_id . '
                    AND is_confirmed is null';

			$data = $this->db->query($sql);

			return (bool)$data[0]['result'];
		}

    /**
		 * @return FamilyRelationModerateModel[]
		 */
		public function getListByToAccountIdAndIsConfirmed($to_account_id)
		{
			$sql = 'SELECT *, family_relation_moderate.id as relation_id
                    FROM ' . $this->table_name . '
                    INNER JOIN account ON account.id = family_relation_moderate.account_id
                    WHERE family_relation_moderate.to_account_id = ' . (int)$to_account_id . '
                    AND family_relation_moderate.is_confirmed is null';
			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function checkExistsByAccountIdAndFullNameAndIsConfirmed($account_id, $full_name)
		{
			$sql = 'SELECT COUNT(*) as `result`
                    FROM family_relation_moderate
                    WHERE account_id = ' . (int)$account_id . '
                    AND full_name = "' . $this->db->escape($full_name) . '"
                    AND is_confirmed is null';

			$data = $this->db->query($sql);

			return (bool)$data[0]['result'];
		}
	}
