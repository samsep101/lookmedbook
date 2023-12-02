<?php

    class SpecialtyHelper
    {
        public static function getNameByCount($specialty_id, $count)
        {
            /**
             * @var SpecialtyManager $specialty_manager
             * @var SpecialtyModel   $specialty
             */

            if (is_object($specialty_id)) {
                $specialty = $specialty_id;
            } else {
                $specialty_manager = ModelManagerFactory::getByName('specialty');
                $specialty         = $specialty_manager->getOneById($specialty_id);
            }

            if($specialty)
            {
                if($count % 100 > 10 && $count % 100 < 21 || $count % 10 == 0 || $count % 10 > 4 && $count % 10 <= 9)
                {
                    $name = $specialty->genitive_name_plural;
                }
                else
                {
                    $name = $specialty->genitive_name;
                }

                if(!$name)
                {
                    return '';
                }

                if(strpos($name, 'врач') !== FALSE)
                {
                    $elements = explode(' ', $name);

                    $name = '';
                    for($i = 1; $i < count($elements); $i++)
                    {
                        $name .= $elements[$i] . ' ';
                    }

                    trim($name, ' ');
                }

                return $name;
            }
            else
            {
                return '';
            }
        }

        public static function getDoctorWordForm($count)
        {
            if($count % 100 > 9 && $count % 100 < 21 || $count % 10 == 0 || $count % 10 > 4)
            {
                $result = 'врачей';
            }
            else if($count % 100 == 1 || $count % 10 == 1)
            {
                $result = 'врач';
            }
            else
            {
                $result = 'врача';
            }

            return $result;
        }

        public static function getClinicWordForm($count)
        {
            if($count % 100 > 9 && $count % 100 < 21 || $count % 10 == 0 || $count % 10 > 4)
            {
                $result = 'клиник';
            }
            else if($count % 100 == 1 || $count % 10 == 1)
            {
                $result = 'клинику';
            }
            else
            {
                $result = 'клиники';
            }

            return $result;
        }

        public static function getSpecialtiesLetterGroups($specialties, $specialties_user_groups = array(), $parentOnly = FALSE)
        {
            $specialties_groups = array();

            if(count($specialties))
            {
                $encoding = 'utf-8';

                $specialties_groups = array(
                    array(
                        'letters' => 'АБВГД',
                        'name'    => 'А-Д'
                    ),
                    array(
                        'letters' => 'ИЙКЛМН',
                        'name'    => 'И-Н'
                    ),
                    array(
                        'letters' => 'ОПР',
                        'name'    => 'О-Р'
                    ),
                    array(
                        'letters' => 'СТФХЦЧШЩЪЫЬЭЮЯ',
                        'name'    => 'С-Я'
                    )
                );

                if(count($specialties_user_groups) && is_array($specialties_user_groups)) $specialties_groups = $specialties_user_groups;

                foreach($specialties_groups AS $sgKey => $sgValue)
                {
                    if($sgValue['letters'])
                    {
                        $letter       = 0;
                        $countLetters = mb_strlen($sgValue['letters'], $encoding);

                        while($letter < $countLetters)
                        {
                            $group       = array();
                            $groupLetter = mb_substr($sgValue['letters'], $letter, 1, $encoding);

                            foreach($specialties AS $sKey => $sValue)
                            {
                                $firstLetter = mb_strtoupper(mb_substr($sValue->name, 0, 1, $encoding), $encoding);
                                if(!$parentOnly || ($parentOnly && $sValue->gparent))
                                {
                                    if($groupLetter == $firstLetter && !$specialties[$sKey]->inGroup)
                                    {
                                        $otherPart                   = mb_substr($sValue->name, 1, mb_strlen($sValue->name, $encoding) - 1, $encoding);
                                        $resultName                  = $firstLetter . $otherPart;
                                        $sValue->name                = $resultName;
                                        $sValue->firstLetter         = $firstLetter;
                                        $sValue->otherPart           = $otherPart;
                                        $group[]                     = $sValue;
                                        $specialties[$sKey]->inGroup = 1;
                                    }
                                }
                            }
                            if(!empty($group)) $specialties_groups[$sgKey]['groups'][] = $group;

                            $letter++;
                        }
                    }
                }
            }

            return $specialties_groups;
        }
    }