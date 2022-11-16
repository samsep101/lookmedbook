<?php

    class UploadifyType extends Type
    {

        public function getFormValue($val = '')
        {

            //var_dump($this);

            $html = '';
            if (isset($this->fieldInfo['label']) && ($this->fieldInfo['label'])) {
                $html .= '<span class="label">' . $this->fieldInfo['label'] . '&nbsp;</span>';
            }
            $html .= '<div class="loadImgButton" id="loadImgButton-' . $this->fieldInfo['name'] . '">Загрузить фото</div>';
            $html .= '<div id="' . $this->fieldInfo['name'] . '_block">';
            if (isset($this->value)) {
                $html .= '<div class="image-block">
                           <img src="' . ImageModel::getImage($this->value, 150, 100) . '" />
                           <div class="delete-image-button">Удалить</div>
                        </div>
                        <input type="hidden" name="form[' . $this->fieldInfo['name'] . ']" value="' . $this->value . '" />';
            } else {
                $html .= '<input type="hidden" name="form[' . $this->fieldInfo['name'] . ']" value="0" />';
            }
            $html .= '</div>';

            $button_text = (isset($this->fieldInfo['button_text'])) ? $this->fieldInfo['button_text'] : '';

            $multi = isset($this->fieldInfo['multi_load']) && $this->fieldInfo['multi_load'] ? 'true' : 'false';
            $html .= "
            <script>
            $(function() {
            	$('#loadImgButton-" . $this->fieldInfo['name'] . "').uploadify({
                    swf           : '/media/js/uploadify/uploadify.swf',
                    uploader      : '/ajax/uploadimage',
                    fileObjName   : '" . $this->fieldInfo['name'] . "',
                    formData    : {'" . session_name() . "' : '" . session_id() . "'},
                    onUploadSuccess : function(file, data, response) {
     	                  $('#" . $this->fieldInfo['name'] . "_block').html(data);
                          //console.log(data);
                    },
                    onUploadError : function(file, errorCode, errorMsg, errorString) {
                        //console.log(errorCode);
                        //console.log(errorMsg);
                        //console.log(errorString);
                    },
                    multi           : false,
                    buttonText      : 'Выбрать иконку',
                    width           : 130                        
                });
                $('.delete-image-button').click(function(){
                    if (confirm('Вы действительно хотите удалить изображение?'))
                    {
                        var parent = $(this).parent();
                        parent.remove();
                        //parent.find('input').attr('value', '');
                    } 
                });
            });    
            </script>
        ";
            return $html;
        }

        public function getViewValue()
        {
            $subdomain = substr($_SERVER['HTTP_HOST'], 0, strpos($_SERVER['HTTP_HOST'], '.'));

            if ($this->getValue()) {
                return '<img src="/uploads/images/' . $subdomain . '/' . $this->this->fieldInfo['base_dir'] . '' . $this->getValue() . '" width="50px" height="50px" />';
            } else {
                return '<img src="/media/img/person.png" width="50px" height="50px" />';
            }
        }
    }