<?php

    class AvatarType extends Type
    {

        public function getPath()
        {
            $uploadPath = Application::getUploadDir(TRUE) . '/' . $this->fieldInfo['base_dir'];
            $currentFile = $uploadPath . $this->getValue();
            return $currentFile;
        }

        public function getSaveValue($value)
        {
            $uploadPath = Application::getUploadDir(TRUE) . '/' . $this->fieldInfo['base_dir'];
            $currentFile = $uploadPath . $value;
            if (!empty($_REQUEST[$this->fieldName . '_delete']) || !empty($_FILES[$this->fieldName]['name'])) {
                $db = Register::get('db');
                $db->query("update " . $this->table . " set " . $this->fieldName . "='' where " . $this->fieldName . "='" . $value . "';");

                if (is_file($currentFile))
                    unlink($currentFile);
            }

            if (!empty($_FILES[$this->fieldName]['name'])) {

                $fileName = $uploadPath . time() . '-' . $_FILES[$this->fieldName]['name'];
                if (move_uploaded_file($_FILES[$this->fieldName]['tmp_name'], $fileName)) {
                    $aThumbs = array();

                    if (is_array(@$this->fieldInfo['images'])) {
                        $aThumbs = $this->fieldInfo['images'];
                    }
                    foreach ($aThumbs as $thumb_sufix=> $thumb_options) {
                        list($aOptions['width'], $aOptions['height']) = explode("x", $thumb_options);

                        if (empty($aOptions['width']) || $aOptions['width'] == 0) $aOptions['width'] = 100;
                        if (empty($aOptions['height']) || $aOptions['height'] == 0) $aOptions['height'] = 100;

                        $thumbPath = $uploadPath . $thumb_sufix . '-' . basename($fileName);

                        $this->img_resize($fileName, $thumbPath, $aOptions['width'], $aOptions['height']);
                    }
                    return basename($fileName);
                }
                die();
            }
            return self::NOT_SET;
        }

        public function getFormValue($val = '')
        {

            if (empty($val[$this->fieldName])) {
                if (!empty($_SESSION[$this->fieldName])) {
                    $this->fieldInfo['base_dir'] = "temp/";
                    $szFileName = $this->getPath() . $_SESSION[$this->fieldName];
                    $this->setValue($_SESSION[$this->fieldName]);
                } else
                    $szFileName = $this->getPath();
            } elseif (!empty($val[$this->fieldName])) {
                $szFileName = $this->getPath() . $val[$this->fieldName];
                $this->setValue($val[$this->fieldName]);
            }

            if (is_file($szFileName))
                $nFileSize = $this->size2string(filesize($szFileName));
            else {
                $szFileName = '';
                $nFileSize = 0;
            }

            if (empty($szFileName)) {
                $this->setValue('');
            }
            $subdomain = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));

            $link = '<a href="/uploads/images/' . $subdomain . '/' . $this->fieldInfo['base_dir'] . $this->getValue() . '">' . ($this->getValue()) . '</a>';

            $result = <<<EOD
			<table cellpadding="3" cellspacing="3" border="0" style="border:1px solid #e4e4e4">
			<tr>
			        <td colspan="1" class="td_main" valign="top">
			        
EOD;
            if ($this->getValue()) {
                $result .= '<img src="/uploads/images/' . $subdomain . '/' . $this->fieldInfo['base_dir'] . '' . $this->getValue() . '" width="100px" height="100px" />';
            } else {
                $result .= '<span class="grey">нет</span>';
            }
            $result .= <<<EOD
			        </td>
			        <td>
			        		<a href="javascript:void(0)" onclick="if($(this).next().css('display') == 'none'){ $(this).next().show(150)} else { $(this).next().hide(150)}">Изменить</a>
			        		<div style="display:none">
			        			<br />
				        		<input type="file" name="{$this->fieldName}" class=""><br />
				        		<span class="grey">или</span><br />
								<input type="hidden" name="form[{$this->fieldName}]" value="{$this->getValue()}" align="absmiddle">
					            <input type="checkbox" name="{$this->fieldName}_delete" value="1"> удалить
								
							</div>
			        </td>
			        
EOD;
            if (!empty($nFileSize)) {
                $result .= <<<EOD
					
				
				
						
EOD;
            }
            $result .= '</tr></table>';
            return $result;
        }

        public function size2string($bytes)
        {
            $aVal = array('b', 'Kb', 'Mb', 'Gb', 'Tb');
            $pow = intval(log($bytes, 1024));
            return round($bytes / pow(1024, $pow), 2) . ' ' . $aVal[$pow];
        }

        public function getViewValue()
        {
            $subdomain = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));

            if ($this->getValue()) {
                return '<img src="/uploads/images/' . $subdomain . '/' . $this->fieldInfo['base_dir'] . '' . $this->getValue() . '" width="50px" height="50px" />';
            } else {
                return '<img src="/media/img/person.png" width="50px" height="50px" />';
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

        public function img_resize($src, $dest, $width, $height, $rgb = 0xffffff, $quality = 100)
        {

            if (!file_exists($src)) return FALSE;

            $size = getimagesize($src);

            if ($size === FALSE) return FALSE;

            $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
            $icfunc = "imagecreatefrom" . $format;
            if (!function_exists($icfunc)) return FALSE;

            $x_ratio = $width / $size[0];
            $y_ratio = $height / $size[1];

            $ratio = min($x_ratio, $y_ratio);
            $use_x_ratio = ($x_ratio == $ratio);

            $new_width = $use_x_ratio ? $width : floor($size[0] * $ratio);
            $new_height = !$use_x_ratio ? $height : floor($size[1] * $ratio);
            $new_left = $use_x_ratio ? 0 : floor(($width - $new_width) / 2);
            $new_top = !$use_x_ratio ? 0 : floor(($height - $new_height) / 2);

            //var_dump($icfunc);
            $isrc = $icfunc($src);
            $idest = imagecreatetruecolor($width, $height);

            imagefill($idest, 0, 0, $rgb);
            imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
            imagejpeg($idest, $dest);

            imagedestroy($isrc);
            imagedestroy($idest);

            return $dest . '.' . $format;
        }
    }