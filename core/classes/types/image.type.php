<?php

    class ImageType extends Type
    {

        public function getPath()
        {
            $uploadPath = Application::getUploadDir(true) . '/' . $this->fieldInfo['base_dir'];
            $currentFile = $uploadPath . $this->getValue();
            return $currentFile;
        }

        public function getSaveValue($value, $model = null)
        {
            if (isset($_FILES[$this->fieldName]) && $_FILES[$this->fieldName]['error'] == 0) {
                $alias = ($model && $model->image_alias) ? $model->image_alias :  null;
                return ImageUploader::upload($this->fieldInfo, $_FILES[$this->fieldName], $alias);
            }

            $form = $_REQUEST['form'];

            if (isset($form[$this->fieldName])) {
                return $form[$this->fieldName];
            }
            return self::NOT_SET;
        }

        public function getFormValue($val = '')
        {

            $image = ModelManagerFactory::getByName('image')->getOneById($val);

            $image_id = ($image) ? $image->getId() : '';

            $result = <<<EOD
			<table width="100%" cellpadding="0" cellspacing="0" border="1">
			<tr>
			        <td colspan="1" class="td_main">
			                <input type="file" name="{$this->fieldName}" class=""> имя файла должно иметь только латинские символы и цифры
			                <input id="image-{$image_id}" type="hidden" name="form[{$this->fieldName}]" value="{$image_id}">
			
			        </td>
           </tr>
			        
EOD;


            if ($image) {
                $resizedImage = $image->resize(180, 150);
                if ($resizedImage) {
                    $result .= '
                    <tr>
                            <td class="td_main">' .
                            '<a class="screenshot" href="' .$image->path .'" rel="' .$image->path .'" onclick="event.preventDefault()" style="cursor:default"><img src="' . $image->resize(180, 150)->path . '" /></a>'
                            . '<span class="deleteImageButton" onclick="$(this).parent().parent().remove(); $(\'#image-' . $image->getId() . '\').val(\'\')">[Удалить]</span>
                            </td>
                    </tr>';
                }
            }
            $result .= '</table>';

            return $result;
        }

        public function size2string($bytes)
        {
            $aVal = array('b', 'Kb', 'Mb', 'Gb', 'Tb');
            $pow = intval(log($bytes, 1024));
            return round($bytes / pow(1024, $pow), 2) . ' ' . $aVal[$pow];
        }

        public function getViewValue($val = '')
        {
            if ($val) {
                $image = ModelManagerFactory::getByName('image')->getOneById($val);
				/**
				 * @var ImageModel $image
				 */
				if ($image) {
                    return '<a class="screenshot" href="' .$image->path .'" rel="' .$image->path .'" onclick="event.preventDefault()" style="cursor:default"><img src="' . $image->crop(100, 75)->path . '" /></a>';
                }
                return '';
            }
        }

        private function getImageParams($aOptions)
        {
            if (is_array($aOptions)) {
                $szSize = $aOptions['size'];
            } else {
                $szSize = $aOptions;
            }

            $aData['width'] = 0;
            $aData['height'] = 0;
            $aData['biggestSide'] = 0;

            if (strpos($szSize, 'x')) {
                list($width, $height) = explode('x', $szSize);
                $aData['width'] = intval($width);
                $aData['height'] = intval($height);
            } else {
                $aData['biggestSide'] = intval($szSize);
            }

            return $aData;
        }
    }