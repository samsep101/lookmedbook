<?php
    class SearchLogManager extends ModelManager
    {
        protected $table_name = 'search_log';
        protected $model_name = 'SearchLogModel';

        public function getListByDates($start_date = null, $finish_date = null)
        {
            $sql = 'SELECT *
                    FROM search_log';

            if($start_date)
            {
                $sql .= ' WHERE time >= ' ."'" .$start_date ." 00:00:00'";
            }

            if($finish_date)
            {
                if($start_date)
                {
                    $sql .= ' AND time < ' ."'" .$finish_date ." 23:59:59'";
                }
                else
                {
                    $sql .= ' WHERE time < ' ."'" .$finish_date ." 23:59:59'";
                }
            }

            $sql .= ' ORDER BY time DESC';

            $data = $this->db->query($sql);

            return $this->initList($data);
        }
    }