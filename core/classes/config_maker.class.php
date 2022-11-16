<?php
    class ConfigMaker {

        public static function createControllersAndGrants()
        {

            $sql = "SHOW TABLES FROM ".DB_NAME;
            $db = Register::get('db');
            $result = $db->query($sql);

            if (!$result) {
                echo "DB Error, could not list tables\n";
                echo 'MySQL Error: ' . $db->error();
                exit;
            }

            $controller_manager = new ControllerManager();
            $grant_manager = new GrantManager();

            foreach($result as $table){
                $tableName = $table['Tables_in_'.DB_NAME];
                if($tableName){
                    $entry = ModelManagerFactory::getByName('controller')->getOneByCode($tableName);
                    if($entry){
                        //Если создан контроллер, то ничего не делаем
                    } else {

                        $controller = new ControllerModel();
                        $controller->code = $tableName;
                        $controller->name = $tableName;
                        $controller->is_active = 1;

                        if($controller_manager->save($controller)){
                            $controller_id = $db->lastInsertId();
                            $grant = new GrantModel();
                            $grant->role_id = '1';
                            $grant->controller_id = $controller_id;
                            $grant->list = '1';
                            $grant->add = '1';
                            $grant->edit = '1';
                            $grant->delete = '1';
                            $grant->delete_list = '1';
                            $grant->save = '1';
                            $grant_manager->save($grant);
                        }
                    }
                }
            }
            echo 'Controllers and Grants is OK.';
        }

        public function createConfigs(){
            $sql = "SHOW TABLES FROM ".DB_NAME;
            $db = Register::get('db');
            $result = $db->query($sql);

            if (!$result) {
                echo "DB Error, could not list tables\n";
                echo 'MySQL Error: ' . $db->error();
                exit;
            }


            foreach($result as $table){
                $tableName = $table['Tables_in_'.DB_NAME];
                if($tableName){
                    if(!file_exists(ABS_ROOT . '/application/config/cms_generator_configs/'.$tableName.'.cfg.php')){
                        $tableDesc = $db->query('DESCRIBE '.DB_NAME.'.'.$tableName);
                        $fileArray = null;
                        foreach($tableDesc as $desc){
                            $fileArray[] = $desc;
                        }

                        $tpl = "<?php\n";
                        $tpl.= "$".$tableName." = array(\n";
                        $tpl.= "    'table'         =>  DB_PREFIX.'".$tableName."',\n";
                        $tpl.= "    'title'         =>  '".$tableName."',\n";
                        $tpl.= "    'fields'        =>  array(\n";
                        $k = 0;
                        foreach($tableDesc as $desc){
                            //$desc['Field'] = strtolower($desc['Field']);
                            if($k == 0){
                                $tpl.= "        '".$desc['Field']."' => 'index',\n";
                            } elseif(strpos(strtolower($desc['Field']),'_id') > 0) {



                                $tpl.= "        '".$desc['Field']."' => array(\n";
                                $tpl.= "                'type'			=> 'category',\n";
                                $tpl.= "                'cross_name'	=> 'name',\n";
                                $tpl.= "                'cross_index'	=> 'id',\n";
                                $tpl.= "                'cross_table'	=> DB_PREFIX.'".str_replace('_id','',$desc['Field'])."',\n";
                                $tpl.= "                'first'			=> array( '0'	=>	'',),\n";
                                $tpl.= "                'filter' => 'true',\n";
                                $tpl.= "        ),\n";
                            } else {
                                $tpl.= "        '".$desc['Field']."' => 'input',\n";
                            }
                            $k++;
                        }
                        //Список полей
                        $tpl.= "    ),\n";
                        $tpl.= "    'generator' => array(\n";
                        $tpl.= "        'fields' => array(\n";
                        foreach($tableDesc as $desc){
                            //$desc['Field'] = strtolower($desc['Field']);
                            $tpl.= "            '".$desc['Field']."' => '".$desc['Field']."',\n";
                        }
                        $tpl.= "        ),\n";
                        $tpl.= "        'list' => array(\n";
                        $tpl.= "            'fields' => array(\n";
                        foreach($tableDesc as $desc){
                            //$desc['Field'] = strtolower($desc['Field']);
                            $tpl.= "            '".$desc['Field']."',\n";
                        }
                        $tpl.= "            ),\n";
                        $tpl.= "            'title'	 => 'Список',\n";
                        $tpl.= "        ),\n";
                        $tpl.= "        'edit'	=> array(\n";
                        $tpl.= "            'fields' => array(\n";
                        $tpl.= "                'Сущность' => array(\n";
                        foreach($tableDesc as $desc){
                            //$desc['Field'] = strtolower($desc['Field']);
                            $tpl.= "                    '".$desc['Field']."',\n";
                        }
                        $tpl.= "                ),\n";
                        $tpl.= "            ),\n";
                        $tpl.= "            'title'	=> 'Редактирование',\n";
                        $tpl.= "            'submit'=> 'Сохранить',\n";
                        $tpl.= "        ),\n";
                        $tpl.= "        'add'	=> array(\n";
                        $tpl.= "            'fields' => array(\n";
                        $tpl.= "                'Сущность' => array(\n";
                        foreach($tableDesc as $desc){
                            //$desc['Field'] = strtolower($desc['Field']);
                            $tpl.= "                    '".$desc['Field']."',\n";
                        }
                        $tpl.= "                ),\n";
                        $tpl.= "             ),\n";
                        $tpl.= "            'title'	=> 'Создать',\n";
                        $tpl.= "            'submit'=> 'Создать',\n";
                        $tpl.= "        ),\n";
                        $tpl.= "    ),\n";
                        $tpl.= ");\n";
                        $tpl.= "CmsGeneratorConfigRegister::add('".$tableName."', $".$tableName.");\n";

                        file_put_contents(ABS_ROOT . '/application/config/cms_generator_configs/'.$tableName.'.cfg.php', $tpl);

                    }
                }
            }
            echo 'Configs is OK.';

        }

        public static function createModelsAndManagers()
        {

            $sql = "SHOW TABLES FROM ".DB_NAME;
            $db = Register::get('db');
            $result = $db->query($sql);

            if (!$result) {
                echo "DB Error, could not list tables\n";
                echo 'MySQL Error: ' . $db->error();
                exit;
            }


            foreach($result as $table){
                $tableName = $table['Tables_in_'.DB_NAME];
                $tableName = strtolower($tableName);
                if($tableName){
                    $nameArray = explode('_',$tableName);
                    $newName = '';
                    foreach($nameArray as $name){
                        $newName.= ucfirst($name);
                    }


                    if(!file_exists(ABS_ROOT . '/application/models/'.$tableName.'.manager.php')){
                        $tpl = "<?php\n";
                        $tpl.= "class ".$newName."Manager extends ModelManager\n";
                        $tpl.= "{\n";
                        $tpl.= "    protected \$table_name = '".$tableName."';\n";
                        $tpl.= "    protected \$model_name = '".$newName."Model';\n";
                        $tpl.= "}\n";
                        file_put_contents(ABS_ROOT . '/application/models/'.$tableName.'.manager.php', $tpl);
                    }

                    if(!file_exists(ABS_ROOT . '/application/models/'.$tableName.'.model.php')){
                        $tpl = "<?php\n";
                        $tpl.= "class ".$newName."Model extends DynamicModel\n";
                        $tpl.= "{\n";
                        $tpl.= "}\n";
                        file_put_contents(ABS_ROOT . '/application/models/'.$tableName.'.model.php', $tpl);
                    }
                }
            }
            echo 'Models and Managers is OK.';
        }

        public static function getIndexFieldName($tableName)
        {

            $db = Register::get('db');
            $tableDesc = $db->query('DESCRIBE '.DB_NAME.'.'.$tableName);
            $fildName = null;
            foreach($tableDesc as $desc){
                $fildName = $desc['Field'];
                break;
            }
            return $fildName;
        }
    }