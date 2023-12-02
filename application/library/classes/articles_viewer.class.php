<?php

class Articles_Viewer extends View {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    const RND = RELEASE__NUMBER;

    public function showCatalogArticle($category_id) {

        // проверяем шаблон для отображения перед текстом инструкции
        // если он есть - подключаем его
        $path = implode('/', [
            Application::getTemplatesDir(true),
            'shop',
            'catalog',
            'articles',
            $category_id . $this->getExtension()
        ]);

        if(file_exists($path)){
            return $this->renderInString('shop/catalog/articles/'.$category_id, false);
        }

        return '';
    }
}

/* END CLASS: Articles_Spoilers */