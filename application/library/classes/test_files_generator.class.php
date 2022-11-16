<?php
    class TestFilesGenerator
    {
        public function generateAll()
        {
            $model_files = $this->getModelFiles();

            foreach ($model_files as $file) {
                $this->generateByFilepath($file);
            }
        }

        public function generateByFilepath($filepath)
        {
            $html = file_get_contents($filepath);
            $class_name = $this->parseClassName($html);

            $entity_name = $this->getEntityNameByClassName($class_name);

            $file_source =
                "<?php\r\n" .
                    "\tclass " . $class_name . "Test extends PHPUnit_Framework_TestCase {\r\n\r\n";

            $file_source .= "\t\t/**\r\n";
            $file_source .= "\t\t * @var ".$class_name."\r\n";
            $file_source .= "\t\t */\r\n";
            $file_source .= "\t\t" . 'protected $object;' . "\r\n\r\n";

            $file_source .= "\t\tprotected function setUp(){\r\n";
            $file_source .= "\t\t\t\$this->object = new " . $class_name . "();\r\n";
            $file_source .= "\t\t}\r\n\r\n";

            if (!$this->checkTestFileExistsByClassName($class_name)) {
                $methods_names = $this->getClassMethodsNames($html);
                if ($methods_names) {
                    foreach ($methods_names as $method_name) {

                        $created_flag = FALSE;

                        if (preg_match('/get((?:Active)|(?:))?((?:One)|(?:List))(?:By(.+))/', $method_name, $matches)) {

                            $params = explode('And', $matches[3]);

                            if (count($params) == 1) {
                                $param = $params[0];

                                if (preg_match('/^(.+)Id/', $param, $matches1)) {
                                    $foreign_entity_name = $matches1[1];
                                    $foreign_entity_name = preg_replace('/([^^])([A-Z])/', '$1_$2', $foreign_entity_name);
                                    $foreign_entity_name = mb_strtolower($foreign_entity_name, 'utf-8');

                                    if ($matches[2] == 'List') {

                                        $file_source .=
                                            "\t\t/** \r\n " .
                                                "\t\t * todo: проверить тест \r\n" .
                                                "\t\t * @covers " . $class_name . "::" . $method_name . "\r\n" .
                                                "\t\t */\r\n" .
                                                "\t\tfunction test" . ucwords($method_name) . "(){\r\n\r\n";

                                        $plural = $this->getPlural($foreign_entity_name);

                                        $file_source .= "\t\t\t$" . $plural . " = ModelManagerFactory::getByName('" . $foreign_entity_name . "')->getListWithLimit(10);\r\n\r\n";
                                        $file_source .= "\t\t\tforeach($" . $plural . " as $" . $foreign_entity_name . "){\r\n";


                                        $file_source .= "\t\t\t\t$" . $this->getPlural($entity_name) . ' = $this->object->' . $method_name . '($' . $foreign_entity_name . "->getId());\r\n";
                                        $file_source .= "\t\t\t\t" . '$this->assertTrue(is_array($' . $this->getPlural($entity_name) . "));\r\n\r\n";

                                        $file_source .= "\t\t\t\tif ($" . $this->getPlural($entity_name) . ")\r\n";
                                        $file_source .= "\t\t\t\t\t" . 'foreach($' . $this->getPlural($entity_name) . ' as $' . $entity_name . "){\r\n";
                                        if ($matches[1] == 'Active') {
                                            $file_source .= "\t\t\t\t\t\t" . '$this->assertTrue((bool)$' . $entity_name . '->active);' . "\r\n";
                                        }

                                        $file_source .= "\t\t\t\t\t\t" . '$this->assertTrue(is_object($' . $entity_name . '));' . "\r\n";
                                        $file_source .= "\t\t\t\t\t\t" . '$this->assertEquals($' . $entity_name . '->' . $foreign_entity_name . '_id, $' . $foreign_entity_name . '->getId());' . "\r\n";
                                        $file_source .= "\t\t\t\t\t}\r\n";

                                        $created_flag = TRUE;

                                        $file_source .= "\t\t\t}\r\n";

                                        $file_source .= "\t\t}\r\n\r\n";
                                    } elseif ($matches[2] == 'One') {
                                        // Если получаем одну запись, то проверяем следующим образом - получаем список записей и пробуем получить их же
                                        // при помощи метода

                                        $file_source .=
                                            "\t\t/** \r\n " .
                                                "\t\t * todo: проверить тест \r\n" .
                                                "\t\t * @covers " . $class_name . "::" . $method_name . "\r\n" .
                                                "\t\t */\r\n" .
                                                "\t\tfunction test" . ucwords($method_name) . "(){\r\n\r\n";

                                        $file_source .= "\t\t\t$" . $this->getPlural($entity_name) . ' = $this->object->getListWithLimit(20);' . "\r\n\r\n";
                                        $file_source .= "\t\t\t" . '$this->object->clearRegister();' . "\r\n\r\n";
                                        $file_source .= "\t\t\t" . 'foreach($' . $this->getPlural($entity_name) . ' as $' . $entity_name . '){' . "\r\n";
                                        $file_source .= "\t\t\t\t" . '$test_object = $this->object->' . $method_name . '($' . $entity_name . '->' . $foreign_entity_name . '_id);' . "\r\n";
                                        $file_source .= "\t\t\t\t\t\t" . '$this->assertTrue(is_object($test_object));' . "\r\n";
                                        $file_source .= "\t\t\t\t" . '$this->assertEquals($' . $entity_name . '->getId(), $test_object->getId());' . "\r\n";
                                        $file_source .= "\t\t\t" . '}' . "\r\n";

                                        $created_flag = TRUE;

                                        $file_source .= "\t\t}\r\n\r\n";
                                    }
                                } else {

                                    $foreign_entity_name = $param;
                                    $foreign_entity_name = preg_replace('/([^^])([A-Z])/', '$1_$2', $foreign_entity_name);
                                    $foreign_entity_name = mb_strtolower($foreign_entity_name, 'utf-8');

                                    if ($matches[2] == 'One') {

                                        $file_source .=
                                            "\t\t/** \r\n " .
                                                "\t\t * todo: проверить тест \r\n" .
                                                "\t\t * @covers " . $class_name . "::" . $method_name . "\r\n" .
                                                "\t\t */\r\n" .
                                                "\t\tfunction test" . ucwords($method_name) . "(){\r\n\r\n";

                                        // Если получаем одну запись, то проверяем следующим образом - получаем список записей и пробуем получить их же
                                        // при помощи метода
                                        $file_source .= "\t\t\t$" . $this->getPlural($entity_name) . ' = $this->object->getListWithLimit(20);' . "\r\n\r\n";
                                        $file_source .= "\t\t\t" . '$this->object->clearRegister();' . "\r\n\r\n";
                                        $file_source .= "\t\t\t" . 'foreach($' . $this->getPlural($entity_name) . ' as $' . $entity_name . '){' . "\r\n";
                                        $file_source .= "\t\t\t\t" . '$test_object = $this->object->' . $method_name . '($' . $entity_name . '->' . $foreign_entity_name . ');' . "\r\n";
                                        $file_source .= "\t\t\t\t\t\t" . '$this->assertTrue(is_object($test_object));' . "\r\n";
                                        $file_source .= "\t\t\t\t" . '$this->assertEquals($' . $entity_name . '->getId(), $test_object->getId());' . "\r\n";
                                        $file_source .= "\t\t\t" . '}' . "\r\n";

                                        $created_flag = TRUE;

                                        $file_source .= "\t\t}\r\n\r\n";
                                    }
                                }
                            }


                        }

                        if (!$created_flag) {
                            $file_source .=
                                "\t\t/** \r\n " .
                                    "\t\t * todo: реализовать тест \r\n" .
                                    "\t\t * @covers " . $class_name . "::" . $method_name . "\r\n" .
                                    "\t\t */\r\n" .
                                    "\t\tfunction test" . ucwords($method_name) . "(){\r\n\r\n" .
                                    "\t\t}\r\n\r\n\r\n";
                        }

                    }
                } else {
                    return;
                }
            }

            $file_source .= "\t}\r\n";

            file_put_contents('tests/application/models/'.$class_name.'Test.php', $file_source);

        }

        private function getPlural($word)
        {
            switch ($word[strlen($word) - 1]) {
                case 's':
                    $word .= 'es';
                    break;
                case 'y':
                    $word[strlen($word) - 1] = 'i';
                    $word .= 'es';
                    break;
                default:
                    $word = $word . 's';
            }

            return $word;
        }

        private function getEntityNameByClassName($class_name)
        {
            if (preg_match('/^(.+)((?:Model)|(?:Manager))$/', $class_name, $matches)) {
                $name = $matches[1];
                $name = preg_replace('/([^^])([A-Z])/', '$1_$2', $name);
                $name = mb_strtolower($name, 'utf-8');
                return $name;
            } else {
                return FALSE;
            }
        }

        private function parseClassName($html)
        {
            if (preg_match('/class ([A-Za-z0-9]+)[ {]/ims', $html, $matches)) {
                return $matches[1];
            } else {
                return FALSE;
            }
        }

        private function getModelFiles()
        {
            $files = glob('application/models/*.manager.php');

            return $files;
        }

        private function checkTestFileExistsByClassName($class_name)
        {
            return file_exists('tests/application/models/' . $class_name . 'Test.php');
        }

        private function getClassMethodsNames($html)
        {
            if (preg_match_all('/public +function +([A-Za-z0-9]+)/ims', $html, $matches)) {
                return $matches[1];
            } else {
                return array();
            }
        }


    }