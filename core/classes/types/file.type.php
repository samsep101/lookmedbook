<?php

    class FileType extends Type
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

                if (is_file(iconv('utf-8', 'windows-1251', $currentFile)))
                    unlink(iconv('utf-8', 'windows-1251', $currentFile));
            }

            if (!empty($_FILES[$this->fieldName]['name'])) {
                if (move_uploaded_file($_FILES[$this->fieldName]['tmp_name'], $uploadPath . iconv('utf-8', 'windows-1251', $_FILES[$this->fieldName]['name']))) {
                    return $_FILES[$this->fieldName]['name'];
                }
            }
            return self::NOT_SET;
        }

        public function getFormValue($val = '')
        {

            $subdomain = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));
            if (empty($val[$this->fieldName]))
                $szFileName = $this->getPath();
            elseif (!empty($val[$this->fieldName])) {
                $szFileName = $this->getPath() . $val[$this->fieldName];
                $this->setValue($val[$this->fieldName]);
            }

            if (file_exists($szFileName))
                $nFileSize = $this->size2string(filesize(iconv('utf-8', 'windows-1251', $szFileName)));
            else {
                $szFileName = '';
                $nFileSize = 0;
            }

            $link = '<a href="/upload/files/' . $subdomain . '/' . $this->fieldInfo['base_dir'] . $this->getValue() . '">' . ($this->getValue()) . '</a>';

            $result = <<<EOD
			<table width="100%" cellpadding="3" cellspacing="3" border="0" style="border:1px solid #e4e4e4">
			<tr>
			        <td colspan="1" class="td_main">
			                <input type="file" name="{$this->fieldName}" class="">
			                <input type="hidden" name="form[{$this->fieldName}]" value="{$this->getValue()}">
			
			        </td>
			        <td>
			                <input type="checkbox" name="{$this->fieldName}_delete" value="1"> удалить
			        </td>
			</tr>
			<tr>
			        <td width="30%" class="td_main">
			        	{$link}
			        	<!-- Размер : {$nFileSize} -->
			        </td>
			        <td class="td_main">
			                
							<!-- Файл: {$this->value} -->
			        </td>
			
			</tr>
			</table>		
EOD;
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
            return $this->getValue();
        }
    }