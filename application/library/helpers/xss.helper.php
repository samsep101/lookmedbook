<?php

class XssHelper
{
    public static function check($field)
    {
        static $jevix = false;
        if ($jevix === false) {
            $jevix = new Jevix();
            $jevix->cfgAllowTags(array(
                'a',
                'i',
                'b',
                'u',
                'em',
                'strong',
                'nobr',
                'li',
                'ol',
                'ul',
                'sup',
                'abbr',
                'pre',
                'acronym',
                'h1',
                'h2',
                'h3',
                'h4',
                'h5',
                'h6',
                'br',
                'code',
                'div',
                'table',
                'td',
                'tr',
                'center',
                'p',
                'span',
                'tbody',
                'img',
                'object',
                'iframe',
                'param',
                'embed'
            ));

            // 2. Устанавливаем короткие теги. (не имеющие закрывающего тега)
            //$jevix->cfgSetTagShort(array('br','img'));
            $jevix->cfgSetTagShort(array('br', 'img', 'param', 'embed', 'iframe'));

            // 3. Устанавливаем преформатированные теги. (в них все будет заменятся на HTML сущности)
            $jevix->cfgSetTagPreformatted(array('pre'));

            // 4. Устанавливаем теги, которые необходимо вырезать из текста вместе с контентом.
//			$jevix->cfgSetTagCutWithContent(array('script', 'object', 'iframe', 'style'));
            $jevix->cfgSetTagCutWithContent(array('script', 'style'));

            $jevix->cfgAllowTagParams('ul', array('style'));
            $jevix->cfgAllowTagParams('span', array('style'));
            $jevix->cfgAllowTagParams('div', array('style'));
            $jevix->cfgAllowTagParams('a', array('href', 'target', 'rel', 'id'));
            $jevix->cfgAllowTagParams('li', array('align'));
            $jevix->cfgAllowTagParams('td', array('align'));
            $jevix->cfgAllowTagParams('table', array('width' => '#text', 'border'));
            $jevix->cfgAllowTagParams('p', array('class', 'id'));
            $jevix->cfgAllowTagParams('ul', array('class'));
            $jevix->cfgAllowTagParams('img', array('alt', 'src', 'style'));
            $jevix->cfgAllowTagParams('object',
                array('classid', 'type', 'codebase', 'height', 'width', 'hspace', 'vspace', 'data'));
            $jevix->cfgAllowTagParams('param',
                array('valuetype', 'name', 'value', 'height', 'width', 'hspace', 'vspace', 'data'));
            $jevix->cfgAllowTagParams('embed', array(
                'vspace',
                'hspace',
                'quality',
                'vspace',
                'pluginspage',
                'src',
                'type',
                'wmode',
                'allowfullscreen',
                'width',
                'height',
                'align'
            ));
            $jevix->cfgAllowTagParams('iframe', array(
                'style',
                'title',
                'longdesc',
                'scrolling',
                'class',
                'id',
                'allowfullscreen',
                'allowscriptaccess',
                'width',
                'vspace',
                'srcdoc',
                'src',
                'seamless',
                'align',
                'allowtransparency',
                'frameborder',
                'height',
                'hspace',
                'marginheight',
                'marginwidth',
                'name'
            ));
            $jevix->cfgSetAutoBrMode(false);
            $jevix->cfgSetAutoLinkMode(false);
        }

        $errors = null;
        $field = $jevix->parse($field, $errors);
        //$field = str_replace('/&gt;','',$field);

        if (!$field && $field !== 0 && $field !== '0') {
            return null;
        } else {
            return $field;
        }
    }
}