<?php
    abstract class Params
    {
        public function __set($param, $value)
        {
            throw new Exception('Параметра ' . $param . ' нет в классе ' . get_class());
        }
    }