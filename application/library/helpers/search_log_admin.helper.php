<?php
    class SearchLogAdminHelper
    {
        public static function additionalData($csrf)
        {
            $data = '<form id="search_log_export_form" action="/admin/index/log/exportCSVSearchLog">';

            $data .= '<span style="display: inline-block; width: 105px">Начальная дата: </span>';
            $data .= '<input type="text" name="date_start" value="" id="date_start">';
            $data .= '<input type="button" id="date_start_picker" value="Выбрать дату">';
            $data .= ' <script type="text/javascript">
                        $(document).ready(function(){
                            Calendar.setup(
                                {
                                    ifFormat:"%d.%m.%Y",
                                    daFormat:"%d.%m.%Y",
                                    inputField: "date_start",
                                    button: "date_start_picker",
                                    eventName: \'click\',
                                    value : ""
                                });
                        });
                      </script>';
            $data .= '<br>';

            $data .= '<span style="display: inline-block; width: 105px">Конечная дата: </span>';
            $data .= '<input type="text" name="date_finish" value="" id="date_finish">';
            $data .= '<input type="button" id="date_finish_picker" value="Выбрать дату">';
            $data .= ' <script type="text/javascript">
                        $(document).ready(function(){
                            Calendar.setup(
                                {
                                    ifFormat:"%d.%m.%Y",
                                    daFormat:"%d.%m.%Y",
                                    inputField: "date_finish",
                                    button: "date_finish_picker",
                                    eventName: \'click\',
                                    value : ""
                                });
                        });
                      </script>';
            $value = isset($csrf) ? '\''.$csrf.'\'' : 'null';

            $data .= '<input type="hidden" name="csrf" value=' .$value .'>';
            $data .= '<input type="submit" value="Выгрузить в *.csv" style="float: right; margin: 0 11px 10px 0; width: 125px">';
            $data .= '</form>';

            return $data;
        }
    }