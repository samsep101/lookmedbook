<?php
    class SearchLogHelper
    {
        public static function log($query, $is_successful = null)
        {
            $search_log = new SearchLogModel();
            $search_log->query = '"' .$query .'"';
            $search_log->is_successful = $is_successful;
            $search_log->time = date('Y-m-d H:i:s');
            $search_log->save();
        }
    }