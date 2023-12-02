<?php
	class TargetCallManager extends ModelManager
	{
		protected $table_name = "target_call";
		protected $model_name = "TargetCallModel";

        public function getOneByPhone($phone)
        {
            $sql = 'SELECT *
                    FROM target_call
                    WHERE phone = ' .$this->db->escape($phone);
            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }

        public function getOneByVisitId($visit_id)
        {
            $sql = 'SELECT tc.*
                    FROM target_call tc
                    INNER JOIN appeal a ON a.target_call_id = tc.id
                    INNER JOIN visit v ON v.appeal_id = a.id
                    WHERE v.id = ' .(int)$visit_id;
            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }
	}