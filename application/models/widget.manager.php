<?php
	class WidgetManager extends ModelManager
	{
		protected $table_name = "widget";
		protected $model_name = "WidgetModel";

        protected function beforeSave(DynamicModel $model)
        {
            /**
             * @var WidgetModel $model
             */
            if($model->isNew() && $model->widget)
            {
                $model->accept_html = $model->widget->accept_html;
                $model->banner_html = $model->widget->banner_html;
                $model->banner_240_160_html = $model->widget->banner_240_160_html;
                $model->form_html = $model->widget->form_html;
                $model->notification_html = $model->widget->notification_html;

				setlocale(LC_ALL, 'ru_RU.UTF-8');
                FileHelper::createFolder(ABS_ROOT . '/media/widget/'.$model->folder);
                FileHelper::copyFolder(ABS_ROOT . '/media/widget/'.$model->widget->folder, ABS_ROOT . '/media/widget/'.$model->folder);
            }
        }

        protected function afterSave(DynamicModel $model)
        {
            /**
             * @var WidgetModel $model
             */
            $widget = $model;

            $widget_code_generator = new WidgetCodeGenerator();
            $widget_code_generator->generate($widget);

            file_put_contents(ABS_ROOT . '/media/widget/'.$widget->folder.'/source/html/accept.html', $widget->accept_html);
            file_put_contents(ABS_ROOT . '/media/widget/'.$widget->folder.'/source/html/banner.html', $widget->banner_html);
            file_put_contents(ABS_ROOT . '/media/widget/'.$widget->folder.'/source/html/banner240x160.html', $widget->banner_240_160_html);
            file_put_contents(ABS_ROOT . '/media/widget/'.$widget->folder.'/source/html/form.html', $widget->form_html);
            file_put_contents(ABS_ROOT . '/media/widget/'.$widget->folder.'/source/html/notification.html', $widget->notification_html);

            if($model->is_need_to_compile)
            {
                exec('python ' . ABS_ROOT . '/media/compile.py ' . ABS_ROOT . '/media/widget/'.$model->folder.'/source/html ' . ABS_ROOT .'/media/widget/'.$model->folder.'/build/js/compiled');
            }
        }

        protected function afterDelete(DynamicModel $model)
        {
            @FileHelper::deleteFolder(ABS_ROOT . '/media/widget/'.$model->folder);
        }
	}