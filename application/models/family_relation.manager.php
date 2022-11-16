<?php
	class FamilyRelationManager extends ModelManager
	{
		protected $table_name = 'family_relation';
		protected $model_name = 'FamilyRelationModel';

		public function getConfirmedListByAccountId($account_id)
		{
			$sql = 'SELECT *, family_relation.id as relation_id
                    FROM ' . $this->table_name . '
                    INNER JOIN account ON account.id = family_relation.account2_id
                    WHERE family_relation.account1_id = ' . (int)$account_id . '
                    AND family_relation.is_confirmed = 1';
			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function getConfirmedListByToAccountId($to_account_id)
		{
			$sql = 'SELECT *, family_relation.id as relation_id
                    FROM ' . $this->table_name . '
                    INNER JOIN account ON account.id = family_relation.account1_id
                    WHERE family_relation.account2_id = ' . (int)$to_account_id . '
                    AND family_relation.is_confirmed = 1';
			$data = $this->db->query($sql);

			return $this->initList($data);
		}

		public function checkExistsByAccount1IdAndAccount2IdAndIsConfirmed($account1_id, $account2_id)
		{
			$sql = 'SELECT COUNT(*) as `result`
                    FROM family_relation
                    WHERE account1_id = ' . (int)$account1_id . '
                    AND account2_id = ' . (int)$account2_id . '
                    AND is_confirmed = 1';

			$data = $this->db->query($sql);

			return (bool)$data[0]['result'];
		}
	}