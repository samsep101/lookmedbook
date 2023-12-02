<?php

require_once ABS_ROOT.'/core/funcs/string.helpers.php';

class ContentAtlasController extends BaseController {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */
    
    private $is_production = null;

    public function __construct() {

        error_reporting(-1);

        if(!empty($_GET['env']) AND $_GET['env'] == 'production'){
            $this->is_production = true;
        }

        if(!empty($_GET['env']) AND $_GET['env'] == 'test'){
            $this->is_production = false;
        }

        // null default
    }

    private function checkPermission() {

        if(is_null($this->is_production)) {
            exit('You have not permission, asshole!');
        }
    }

    /** @return PDO */
    protected function db_content() {

        $this->checkPermission();

        static $DB = null;

        if(is_null($DB)){

            $conf = ['lookmedbook_content', 'localhost', 'lookmedbook', 'O9j8C6d9'];
            if($this->is_production){
                $conf = ['lookmedbook_content', 'localhost', 'look_content', 'kj34ghfD4'];
            }

            $DB = new PDO('mysql:dbname='.$conf[0].';host='.$conf[1], $conf[2], $conf[3], [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
            ]);
            $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }

        return $DB;
    }

    /** @return PDO */
    protected function db_lmb(){

        $this->checkPermission();

        static $DB = null;

        if(is_null($DB)){

            $conf = ['lookmedbook', 'localhost', 'lookmedbook', 'O9j8C6d9'];
            if($this->is_production){
                $conf = ['lookmedbook_prod', 'localhost', 'look_prod', 'dfghmt563'];
            }

            $DB = new PDO('mysql:dbname='.$conf[0].';host='.$conf[1], $conf[2], $conf[3], [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
            ]);
            $DB->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        
        return $DB;
    }

    public function pullDisease() {

        $this->checkPermission();

        $pullProjects = [1275, 1274, 1273, 1272, 1271];
        $statistic = [
            'inserts' => 0,
            'deleted' => 0,
            'selects' => 0
        ];

        $t = [
            'c_projects' => 'acts_project',
            'c_diseases' => 'acts_diseases',
            'lmb_disease' => 'disease',
            'lmb_disease_block' => 'disease_block'
        ];
        
        $types = [
            1 => ['acts_disease_common_symptoms', 'Симптомы'],
            2 => ['acts_disease_incubation_period', 'Инкубационный период'],
            3 => ['acts_disease_forms', 'Формы'],
            4 => ['acts_disease_causes_factors', 'Причины'],
            5 => ['acts_disease_diagnostica', 'Диагностика'],
            6 => ['acts_disease_principles_treatment', 'Лечение'],
            7 => ['acts_disease_complication_consequences', 'Осложнения и последствия'],
            8 => ['acts_disease_principles_prevention', 'Профилактика'],
            9 => ['acts_disease_more', 'Дополнительно'],
        ];

        $projects = $this->db_content()->query(sprintf('SELECT id, project_desc, name_disease FROM %s WHERE id IN (%s) AND status = 8', $t['c_projects'], implode(', ', $pullProjects)));
        $statistic['selects']++;

        if($projects->rowCount()){

            foreach($projects as $project){

                /*`id` int(11) NOT NULL,
                    `name` varchar(255) DEFAULT NULL,
                    `ws` int(11) DEFAULT NULL,
                    `areas` int(11) DEFAULT NULL,
                    `is_adult` int(1) DEFAULT NULL,
                    `is_male` int(1) DEFAULT NULL,
                    `is_female` int(1) DEFAULT NULL,
                    `is_children` int(1) DEFAULT NULL,
                    `is_newborn` int(1) DEFAULT NULL,
                    `is_pregnant` int(1) DEFAULT NULL*/
                $disease = $this->db_content()->query(sprintf('SELECT * FROM %s WHERE id = %d LIMIT 1', $t['c_diseases'], $project['name_disease']))->fetch();
                $statistic['selects']++;
                $disease_lmb = $this->db_lmb()->query(sprintf('SELECT * FROM %s WHERE content_project_id = %d', $t['lmb_disease'], $project['id']))->fetch();
                $statistic['selects']++;
                
                if($disease) {
                    
                    if( false === $disease_lmb){

                        $morpher = StringHelpers\free_morpher($disease['name']);

                        $q = sprintf("INSERT INTO %s (title, alias, content, is_active, genitive_name, prepositional_name, date_update, content_project_id)
                            VALUES ('%s', '%s', '%s', 1, '%s', '%s', NOW(), %d)",
                                $t['lmb_disease'],

                                $disease['name'],
                                StringHelpers\slug($disease['name']),
                                html_entity_decode($project['project_desc']),
                                mb_strtolower($morpher['Р']),
                                mb_strtolower($morpher['П']),
                                $project['id']
                        );
                    
                        $q = $this->db_lmb()->exec($q);
                        $statistic['inserts']++;

                        $lmb_disease_id = $this->db_lmb()->lastInsertId();

                    } else {

                        $lmb_disease_id = $disease_lmb['id'];
                    }

                    $this->db_lmb()->exec(sprintf('DELETE FROM %s WHERE disease_id = %d', $t['lmb_disease_block'], $lmb_disease_id));
                    $statistic['deleted']++;

                    foreach($types as $key => $data){

                        /*`id` int(11) NOT NULL,
                            `name` varchar(255) DEFAULT NULL,
                            `content` text,
                            `is_active` int(1) DEFAULT NULL,
                            `project_id` int(11) DEFAULT NULL,
                            `is_adult` int(1) DEFAULT NULL,
                            `is_male` int(1) DEFAULT NULL,
                            `is_female` int(1) DEFAULT NULL,
                            `is_children` int(1) DEFAULT NULL,
                            `is_newborn` int(1) DEFAULT NULL,
                            `is_pregnant` int(1) DEFAULT NULL*/
                        $type_data = $this->db_content()->query(sprintf('SELECT * FROM %s WHERE project_id = %d LIMIT 1', $data[0], $project['id']));
                        $statistic['selects']++;

                        if($type_data->rowCount()){

                            $type_data = $type_data->fetch();

                            if(!empty($type_data['content'])){

                                $q = vsprintf("INSERT INTO %s (disease_id, disease_block_type_id, content, is_active, male_flag, female_flag, adult_flag, children_flag, newborn_flag, pregnant_flag)
                                        VALUES (%d, %d, '%s', 1, %d, %d, %d, %d, %d, %d)", [
                                    $t['lmb_disease_block'],
                                    $lmb_disease_id,
                                    $key,
                                    html_entity_decode($type_data['content']),
                                    $type_data['is_male'],
                                    $type_data['is_female'],
                                    $type_data['is_adult'],
                                    $type_data['is_children'],
                                    $type_data['is_newborn'],
                                    $type_data['is_pregnant'],
                                ]);

                                $q = $this->db_lmb()->exec($q);
                                $statistic['selects']++;
                            }
                        }
                    }
                }
            }
        }

        debug($statistic);
    }
}

/* END CLASS: ContentAtlasController extends BaseController */