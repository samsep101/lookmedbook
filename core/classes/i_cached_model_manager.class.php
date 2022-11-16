<?php
    interface ICachedModelManager {
        public function getGroupName();
        public function getNotCachedMethods();
        public function getCachedMethods();
        public function getSortedListByIdList(array $id_list);
        public function isCached();
    }