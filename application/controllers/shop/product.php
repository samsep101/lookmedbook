<?php
	class ProductShopController extends BaseController
	{
        /**
         * @var ProductModel $product
         */
        private $_product = false;

		public function get()
		{
            $this->view->menu_active = 'shop';
			//Test::dump($_SESSION);
            $product_id = $this->request('id');

            /**
             * @var ProductManager $product_manager
             * @var ProductCategoryModel $product_category
             * @var ProductCategoryModel $parent_product_category
             */
            $product_manager = ModelManagerFactory::getByName('product');

            $this->_product = $product_manager->getOneByIdOrAlias($product_id);

            if(!isset($this->current_account) || !$this->current_account || !$this->current_account->is_product_admin)
            {
                if(!$this->_product || !$this->_product->is_active)
                {
                    ErrorPageViewHelper::page404();
                }
            }
            else
            {
                if(!$this->_product)
                {
                    ErrorPageViewHelper::page404();
                }
            }

            if (is_numeric($product_id) && $this->_product->alias)
            {
                RedirectManager::redirect301(ProductLinkViewHelper::getLink($this->_product));
            }

            if (strtolower($product_id) !== $product_id) {
                RedirectManager::redirect301(ProductLinkViewHelper::getLink($this->_product));
            }

            $product_category = $this->_product->product_category;
            $parent_product_category = null;

            if ($product_category) {
                $this->view->category_id = $this->_product->product_category->getId();
                $this->view->product_category = $product_category;

                $parent_product_category = $product_category->parent;

                if(!$parent_product_category || !$parent_product_category->parent)
                {
                    $parent_product_category = null;
                }
            }

            $this->view->product = $this->_product;
            $this->view->product_category = $product_category;
            $this->view->parent_product_category = $parent_product_category;
            $this->view->page_title = ProductsPageTitleGeneratorHelper::productTitle($this->_product);

            $this->_getBeforeArticle();

            $this->render('shop/product/index');
		}

        /**
         * Шаблон статьи перед текстом инструкции, выводим если есть
         */
        protected function _getBeforeArticle() {

            $alias = $this->_product->alias;

            // проверяем шаблон для отображения перед текстом инструкции
            // если он есть - подключаем его
            $path = implode('/', [
                Application::getTemplatesDir(true),
                'shop',
                'product',
                'articles',
                $alias . $this->view->getExtension()
            ]);
            if(file_exists($path)){
                $this->view->before_article = $this->view->renderInString('shop/product/articles/'.$alias, false);
            }
        }

	}
