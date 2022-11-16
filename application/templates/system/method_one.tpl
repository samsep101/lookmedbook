
        public function getOneBy<?php echo $field_camel; ?>($<?php echo $field_name; ?>){
            $data = $this->orm_model->select()->where('<?php echo $field_name; ?> = ?', $<?php echo $field_name; ?>)->fetchOne();
            return $this->initOne($data);
        }
