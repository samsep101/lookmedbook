<?php

    class LinkType extends Type
    {

        public function getFormValue($val = '')
        {

            $value = htmlspecialchars($val);

            $szResult = '<a href="' . SITE_URL .MEDIA_UPLOAD_PATH . $this->fieldInfo['folder'] . $val . '" target="_blank">' . $val . '</a>';

            return $szResult;
        }

        public function getViewValue($value)
        {
            return $this->getFormValue($value);
        }

    }