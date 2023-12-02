<?php

    class CitrusController extends Controller
    {
        
        public function index(){

            // Метод по-умолчанию ( http://lookmedbook.citrus.one/citrus )
            Test::dump('index');
        }
        
        public function team(){
            
            // Пользовательский метод ( http://lookmedbook.citrus.one/citrus/team )
            Test::dump('weeeeeee !', false);
            
            // Пользовательский метод с параметрами ( http://lookmedbook.citrus.one/citrus/team/param1/value1/param2/value2 )
            Test::dump([
                $this->request('param1'),
                $this->request('param2'),
                $this->request('param3','default_value3')
            ]);
        }
        
        public function custom_get(){
            
            //Метод для динамичного урл, роутинг прописывается в файле /application/config/map.cfg.php (для этого метода прописан роутер /citrus/:id/:param)
            //Стандартный роутинг описан тут: /core/classes/dispatcher.class.php:166
            //При пересечении пользовательского роутинга со стандартным необходимо явно прописывать роутер для нужных методов, например,
            //при указании роутера /citrus/:id, метод team() уже не будет работать по адресу /citrus/team - для этого нужно создать дополнительный роутер для метода team(),
            //при этом явный роутер должен быть прописан раньше, чем динамичный
            $check_id = $this->request('id');
            $check_param = $this->request('param');
            
            //Тест: http://lookmedbook.citrus.one/citrus/some_id1/some_param1
            //Тест: http://lookmedbook.citrus.one/citrus/some_id2/some_param2
            //Тест: http://lookmedbook.citrus.one/citrus/404?no
            //Тест: http://lookmedbook.citrus.one/citrus/force_404
            
            if ($check_id == 'force_404'){
                ErrorPageViewHelper::page404('404');
            }
            
            Test::dump([
                $check_id,
                $check_param
            ]);
        }
        
        public function test_view(){
               
            // Вывод шаблона http://lookmedbook.citrus.one/citrus/test_view - там же тест аякса (метод ниже)

            // Оболочка для вывода (хедер, футер, ...) - ('home','ajax','landing',  и тд), не обязательно
            // Второй вариант использования: $this->view->setLayout('image_slider');
            // Некоторые оболочки подразумевают заранее заданные переменные/методы, например, оболочка 'home' для обычных разделов и страниц - в этом случае контроллер должен наследоваться от BaseController
            $this->layout = 'system';
            
            // Задаем контент для вывода
            $this->view->param1 = 'Citrus view test';
            $this->view->arr1 = ['citrus', 'team'];
            
            // Рендер и вывод шаблона /application/templates/citrus/test_view_template.tpl
            $this->render('citrus/test_view_template');
        }
        
        public function ajaxCheck(){
            
            //Обработка аякс запроса, роутер прописан в /application/config/map.cfg.php, но если нет пересечения со стандартным роутингом - можно не прописывать (будет использоваться как любой пользовательский метод)
            
            $this->layout = 'ajax';
            
            //Получаем параметр ajax-запроса
            $this->view->href = $this->request('href', '');
            
             //Тестовая модель /application/models/citrus.model.php
            $citrus = new CitrusModel();
            
            $this->view->param1 = $citrus->get_current_date();
            
            //Тестовый менеджер /application/models/citrus.manager.php
            //Можно вызвать менеджер другим способом: $citrus_manager = ModelManagerFactory::getByName('citrus');
            $citrus_manager = new CitrusManager();
            
            //Пользовательский метод менеджера /application/models/citrus.manager.php
            $this->view->param2 = $citrus_manager->getCitrusByAlias('lime');
            
            //Стандартный метод менеджера /core/classes/model_manager.class.php
            $this->view->arr2 = $citrus_manager->getList();
            
            //Рендер шаблона и получение результата (/application/templates/citrus/test_ajax.tpl)
            $html = $this->renderInString('citrus/test_ajax');
            
            //Передача данных в колбек аякса
            JsonResponse::result(array(
               'html' => $html,
            ));
        }
    }