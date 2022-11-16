<?php
class HtmlTextViewHelper
{
    public static function getView($text)
    {
        $text = preg_replace('/<br \/>/', '', $text);
        $text = preg_replace('/<br\/>/', '', $text);
        return html_entity_decode($text, ENT_COMPAT, 'UTF-8');
    }
}