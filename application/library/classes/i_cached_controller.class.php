<?php
    interface ICachedController
    {
        public function getCachedMethods();
        public function setETag($e_tag);
    }