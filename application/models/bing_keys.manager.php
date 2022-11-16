<?php
    class BingKeysManager extends ModelManager
    {
        protected $table_name = 'bing_keys';
        protected $model_name = 'BingKeysModel';

        public function getOneWithMinimalTransactionsCount()
        {
            $sql = 'SELECT *
                    FROM bing_keys
                    WHERE transactions_count < 5000
                    ORDER BY transactions_count ASC';

            $data = $this->db->query($sql);

            return ($data) ? $this->initOne($data[0]) : null;
        }

        public function updateTransactionsCountById($id, $count)
        {
            $sql = 'UPDATE bing_keys
                    SET transactions_count = ' .(int)$count .'
                    WHERE id = ' .(int)$id;

            $this->db->query($sql);
        }
    }