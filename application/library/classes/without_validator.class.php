<?php
    class WithoutValidator extends ModelValidator
    {
        public function validate(DynamicModel $model)
        {
            return TRUE;
        }
    }