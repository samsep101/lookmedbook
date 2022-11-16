<?php
    class DefaultModelValidator extends ModelValidator
    {
        public function validate(DynamicModel $model)
        {
            return TRUE;
        }
    }