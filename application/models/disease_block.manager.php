<?php
	class DiseaseBlockManager extends ModelManager
	{
		protected $table_name = 'disease_block';
		protected $model_name = 'DiseaseBlockModel';

        /**
         * @param $disease_id
         * @return DiseaseBlockModel[]
         */
		public function getActiveListByDiseaseId($disease_id)
		{
			$sql = 'SELECT *
                    FROM disease_block
                    WHERE `disease_id` = "' . (int)($disease_id) . '"
                        AND `is_active` = 1
                    ORDER BY disease_block_type_id ASC';

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

        /**
         * @param $disease_id
         * @param $flag
         *
         * @return DiseaseBlockModel[]
         */
		public function getActiveListByDiseaseIdAndFlag($disease_id, $flag)
		{
			if(!in_array($flag, array('male', 'female', 'children', 'pregnant', 'adult', 'newborn')))
			{
				return array();
			}

			$flag = $flag . '_flag';

			$sql = 'SELECT *
                        FROM disease_block
                        WHERE `disease_id` = "' . (int)($disease_id) . '"
                            AND ' . $flag . ' = 1
                            AND `is_active` = 1
                    ORDER BY disease_block_type_id ASC';

			$data = $this->db->query($sql);

			return (count($data)) ? $this->initList($data) : array();
		}

		public function getActiveDiseaseTabsFlagsByDiseaseId($disease_id)
		{
			$sql = 'SELECT
                        IF( (SELECT COUNT(*)
                            FROM disease_block
                            WHERE disease_id =' . (int)$disease_id . '
                                AND male_flag = 1
                                AND is_active = 1) > 0, 1, 0) as male,
                        IF( (SELECT COUNT(*)
                            FROM disease_block
                            WHERE disease_id =' . (int)$disease_id . '
                                AND female_flag = 1
                                AND is_active = 1) > 0, 1, 0) as female,
                        IF( (SELECT COUNT(*)
                            FROM disease_block
                            WHERE disease_id =' . (int)$disease_id . '
                                AND children_flag = 1
                                AND is_active = 1) > 0, 1, 0) as children,
                        IF( (SELECT COUNT(*)
                            FROM disease_block
                            WHERE disease_id =' . (int)$disease_id . '
                                AND newborn_flag = 1
                                AND is_active = 1) > 0, 1, 0) as newborn,
                        IF( (SELECT COUNT(*)
                            FROM disease_block
                            WHERE disease_id =' . (int)$disease_id . '
                                AND pregnant_flag = 1
                                AND is_active = 1) > 0, 1, 0) as pregnant,
                        IF( (SELECT COUNT(*)
                            FROM disease_block
                            WHERE disease_id =' . (int)$disease_id . '
                                AND adult_flag = 1
                                AND is_active = 1) > 0, 1, 0) as adult';

			$data = $this->db->query($sql);

			$disease_flags = new DiseaseTabsFlags();
			$disease_flags->male = $data[0]['male'];
			$disease_flags->female = $data[0]['female'];
			$disease_flags->adult = $data[0]['adult'];
			$disease_flags->pregnant = $data[0]['pregnant'];
			$disease_flags->newborn = $data[0]['newborn'];
			$disease_flags->children = $data[0]['children'];
			return $disease_flags;
		}

        public function getDiseaseForSpecializationDefinedBySpecialty($specialtyID) {
            $disease = array();

            if(gettype($specialtyID) == 'integer') {
                $sql = "SELECT DISTINCT d.id AS disease_id, d.*, sp.id AS specialization_id, sp.name AS specialization_title, sp.alias AS specialization_alias
                        FROM `specialty_to_specialization` AS sts
                            INNER JOIN `specialization` AS sp ON sp.id = sts.specialization_id
                            INNER JOIN `specialty_to_disease` AS std ON std.specialty_id = sts.specialty_id
                            INNER JOIN `disease` AS d ON std.disease_id = d.id
                        WHERE sts.specialization_id = (
                            SELECT DISTINCT sts.specialization_id
                                FROM `specialty_to_specialization` AS sts
                                WHERE %s
                                LIMIT 1
                            )
                            ORDER BY d.title";

                $sqlResult = sprintf($sql, "sts.specialty_id = {$specialtyID} AND sts.is_main = 1");
                $disease = $this->db->query($sqlResult);

                if(!count($disease)) {
                    $sqlResult = sprintf($sql, "sts.specialty_id = {$specialtyID}");
                    $disease = $this->db->query($sqlResult);
                }
            }

            return $disease;
        }

        public function constructSimilarDiseases(DynamicModel $currentDisease, $diseases) {
            $resultArray = array();
            $numberFroListDiseases = 0;
            $remainingAmount = 0;
            $specialization = '';
            $step = 7;

            foreach($diseases AS $dKey => $dValue) {
                if(!$specialization) $specialization = empty($dValue['name'])?'':$dValue['name'];

                if($currentDisease->id == $dValue['disease_id']) {
                    $numberFroListDiseases = $dKey + 1;
                    unset($diseases[$dKey]);
                }
            }
            $start = $numberFroListDiseases + 1;
            $end = $start + $step;
            $count = count($diseases);

            if($start > $count) {
                $start = 1;
                $end = $step + 1;
            } elseif($start == $count) {
                $start = $count;
                $end = $step;
            } elseif($start < $count) {
                if($end > $count) {
                    $remainingAmount = $end - $count;
                    $leftUntilTheEnd = $count - $start;
                }

                $end = $remainingAmount > 0 ? $remainingAmount : $end;
            }

            foreach($diseases AS $dKey => $dValue) {
                $updateKey = $dKey + 1;
                if(
                    ($start <= $updateKey && $end > $updateKey)
                    || ($remainingAmount > 0 && ($start <= $updateKey || $end > $updateKey) )
                ) {
                    $resultArray['diseases_group'][] = $dValue;
                }
            }

            $resultArray['specialization'] = $specialization;

            return $resultArray;
        }

        public function deleteByDiseaseId($diseaseId)
        {
            $this->orm_model->delete('disease_id = ' . (int)$diseaseId);
        }
	}