<?php

    class HtmlareaType extends Type
    {

        public function getFormValue($val = '')
        {
            $value = '';
            if (isset($this->value)) {
                $value = htmlspecialchars($this->value);
                $value = stripcslashes($this->value);
            } elseif (!empty($val)) {
                //$value = htmlspecialchars($val[$this->fieldName]);
                $value = stripcslashes($val);
            }

            $simple = !empty($this->fieldInfo['simple']);
            $controlHTML = '';
            if ($simple) {
                $result = <<<EOD
			<textarea id="{$this->fieldName}123" name="form[{$this->fieldName}]" style="width: 450px; height: 150px;">{$value}</textarea>

			<script type="text/javascript">
				$(document).ready(function() {
					var editor{$this->fieldName}123 = CKEDITOR.replace( '{$this->fieldName}123' );
					CKFinder.SetupCKEditor( editor{$this->fieldName}123, 'ckfinder/') ;
				});
			</script>

EOD;

            } else {
                $uid = uniqid();
                $result = <<<EOD

			<textarea class="htmlarea" id="{$this->fieldName}{$uid}" name="form[{$this->fieldName}]" style="width: 450px; height:150px;">{$value}</textarea>
			<script type="text/javascript">
				$(document).ready(function() {
					CKEDITOR.replace( '{$this->fieldName}{$uid}',
					{
						filebrowserBrowseUrl : '/media/js/ckeditor/ckfinder.html',
						filebrowserUploadUrl : '/media/js/ckeditor/core/connector/php/connector.php?command=QuickUpload&type=Files',
						
						filebrowserImageBrowseUrl : '/media/js/ckeditor/ckfinder.html?type=Images',
						filebrowserFlashBrowseUrl : '/media/js/ckeditor/ckfinder.html?type=Flash',
						
						filebrowserImageUploadUrl : '/media/js/ckeditor/core/connector/php/connector.php?command=QuickUpload&type=Images',
						filebrowserFlashUploadUrl : '/media/js/ckeditor/core/connector/php/connector.php?command=QuickUpload&type=Flash',
						
						filebrowserWindowWidth : '600',
						filebrowserWindowHeight : '600'
					});

					getValue(CKEDITOR.instances.{$this->fieldName}{$uid}.getData());

					function getValue(value) {
					    $("textarea#{$this->fieldName}{$uid}").html(value);

					    setTimeout(function() {
					        getValue(CKEDITOR.instances.{$this->fieldName}{$uid}.getData())
					    }, 1000);
					};
				});
			</script>

EOD;
            }
            return $result;
        }

        public function getViewValue($value = '')
        {
        	if ($value)
            	return mb_substr(htmlspecialchars(strip_tags($value)), 0, 30, 'UTF-8').'...';
            return '';
        }
    }