<?php
	class PageManager extends ModelManager
	{
		protected $table_name = 'page';
		protected $model_name = 'PageModel';

        /**
		 * @return PageModel[]
		 */
		public function getListBySort($sort)
		{
			$data = $this->orm_model->select()->where('sort = ?', $sort)->fetchAll();
			return (count($data)) ? $this->initList($data) : array();
		}

        public function getOneByCode($code)
        {
            $sql = 'SELECT *
                    FROM page
                    WHERE code = ' ."'" .$code ."'";

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }
	}
