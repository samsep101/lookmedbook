<?php

    class CatalogShopController extends BaseController
    {
        public function index()
        {
            /**
             * @var ProductCategoryManager $product_category_manager
             * @var ProductManager         $products
             * @var ProductCategoryModel   $product_category
             * @var ProductCategoryModel[] $parent_product_category_children
             */

            $this->view->menu_active = 'shop';

            $product_category_manager = ModelManagerFactory::getByName('product_category');
            $product_category         = NULL;

            $category_alias        = $this->request('category_alias');
            $parent_category_alias = $this->request('parent_category_alias');
            $category_id           = $this->request('category_id');

            if($category_id)
            {
                $product_category = $product_category_manager->getOneById($category_id);
                if($product_category && $product_category->alias)
                {
                    RedirectManager::redirect301(ProductCategoryLinkViewHelper::getLink($product_category));
                }
            }

            if($category_alias)
            {
                $product_category = $product_category_manager->getOneByAlias($category_alias);
                if($parent_category_alias)
                {
                    if(($product_category->parent->alias != $parent_category_alias))
                    {
                        ErrorPageViewHelper::page404();
                    }
                }
            }

            if(($parent_category_alias || $category_id) && !$product_category)
            {
                ErrorPageViewHelper::page404();
            }

            // Если выбрана категория лекарств
            if($product_category)
            {
                if(!isset($this->current_account) || !$this->current_account || !$this->current_account->is_product_admin)
                {
                    if(!$product_category->is_active)
                    {
                        ErrorPageViewHelper::page404();
                    }
                }

                if (strtolower($category_alias) !== $category_alias) {
                    RedirectManager::redirect301(ProductCategoryLinkViewHelper::getLink($product_category));
                }

                $parent_product_category = $product_category->parent;

                // Если у родительской категории нет родительской категории
                if(!$parent_product_category || !$parent_product_category->parent)
                {
                    $parent_product_category = NULL;
                }

                if(!isset($this->current_account) || !$this->current_account || !$this->current_account->is_product_admin)
                {
                    $product_categories = $product_category->children;

                    if($parent_product_category)
                    {
                        $parent_product_category_children             = $parent_product_category->children;
                        $this->view->parent_product_category_children = $parent_product_category_children;
                    }
                }
                else
                {
                    $product_categories = $product_category->all_children;

                    if($parent_product_category)
                    {
                        $parent_product_category_children             = $parent_product_category->all_children;
                        $this->view->parent_product_category_children = $parent_product_category_children;
                    }

                    $this->view->show_total_count = TRUE;
                }

                $this->view->show_horizontal_banner = true;
                $this->view->product_category        = $product_category;
                $this->view->product_categories      = $product_categories;
                $this->view->parent_product_category = $parent_product_category;
                $this->view->page_title              = ProductsPageTitleGeneratorHelper::productCategoryTitle($product_category, $parent_product_category);

                $this->render('shop/catalog/index');
            }
            else
            {
                $product_category = $product_category_manager->getOneByName('Лекарства и БАДы');

                if($product_category)
                {
                    if(!isset($this->current_account) || !$this->current_account || !$this->current_account->is_product_admin)
                    {
                        $product_categories = $product_category->children;
                    }
                    else
                    {
                        $product_categories = $product_category->all_children;
                    }

                    $this->view->product_categories    = $product_categories;
                    $this->view->root_product_category = $product_category;
                    $this->view->is_leader             = 1;
                    $this->view->page_title            = 'Лекарства';
		   
		    $this->render('shop/catalog/index');
                }
            }
        }

        public function payments()
        {
            $this->view->page_title  = 'Лекарства. Оплата товара. Стоимость доставки';
            $this->view->menu_active = 'shop';

            $this->render('shop/catalog/payments');
        }

        public function delivery()
        {
            $this->view->page_title  = 'Лекарства. Оплата товара. Стоимость доставки';
            $this->view->menu_active = 'shop';

            $this->render('shop/catalog/delivery');
        }

        public function choise()
        {
            $this->view->page_title = 'Лекарства. Оплата товара. Стоимость доставки';

            $this->render('shop/catalog/choise');
        }

        // ajax запрос на получение данных о товарах
        public function ajaxSearch()
        {
            /**
             * @var ProductManager $product_manager
             */
            $this->layout = 'ajax';

            $search_params = new ProductSearchCriteria();

            if(!isset($this->current_account) || !$this->current_account || !$this->current_account->is_product_admin)
            {
                $search_params->is_active      = 1;
                $search_params->get_extra_item = TRUE;
                $search_params->is_leader      = $this->request('is_leader', 0);
                $search_params->product_itself = $this->request('product_itself');
            }

            $search_params->is_leader = $this->request('is_leader', 0);
            $product_category_id      = $this->request('product_category', 0);

            if($product_category_id)
            {
                /**
                 * @var ProductCategoryModel   $product_category
                 * @var ProductCategoryModel[] $product_categories
                 */
                $product_category_manager = ModelManagerFactory::getByName('product_category');
                $product_category         = $product_category_manager->getOneById($product_category_id);

                if($product_category && $product_category->name != 'Лекарства и БАДы')
                {
                    $product_categories = $product_category->children;

                    if($product_categories)
                    {
                        foreach($product_categories as $item)
                        {
                            $product_categories_ids[] = $item->getId();
                        }

                        if(isset($product_categories_ids) && $product_categories_ids)
                        {
                            $search_params->product_categories = $product_categories_ids;
                        }
                    }
                    else
                    {
                        $search_params->product_categories = array($product_category->getId());
                    }
                }
            }

            $search_params->by_page = $this->request('by_page', 10);
            $search_params->page    = $this->request('page', 1);

            $pattern = $this->request('pattern');
            if($pattern && $pattern != '*')
            {
                $search_params->full_name = $pattern;
            }

            //$search_params->sort_by = '_name';
/*
	    print_r($search_params);
	    
	    $search_params = new ProductSearchCriteria();
	    $search_params->is_active = 1;
	    $search_params->is_leader=1;
          $search_params->by_page = 5;
            $search_params->page    = 1;
	    $search_params->get_extra_item = 1;
	    
	    
 */
	    $search_params->sort_by = "name"; 
 	    
	    
	    $product_manager        = ModelManagerFactory::getByName('product');



	    $products               = $product_manager->getListByModelSearchCriteria($search_params);
//		print_r($products);
            $button_more_enable = '1';

            if(count($products) > $search_params->by_page)
            {
                unset($products[$search_params->by_page]);
            }
            else
            {
                $button_more_enable = '0';
            }

            $html = '';
            if(!$products)
            {
                if($pattern && $pattern != '*')
                {
                    SearchLogHelper::log($pattern);

                    $search_params = new ProductSearchCriteria();

                    if(!isset($this->current_account) || !$this->current_account || !$this->current_account->is_product_admin)
                    {
                        $search_params->is_active = 1;
                    }

                    $search_params->is_leader = 1;
                    $search_params->page      = 1;
                    $search_params->by_page   = 10;
                    $search_params->sort_by   = array('field' => 'clean_name', 'type' => 'ASC');

                    $products = $product_manager->getListByModelSearchCriteria($search_params);

                    $button_more_enable = '1';
                    if(count($products) > $search_params->by_page)
                    {
                        unset($products[$search_params->by_page]);
                    }
                    else
                    {
                        $button_more_enable = '0';
                    }

                    $this->view->products  = $products;
                    $this->view->not_found = TRUE;
                    $html                  = $this->renderInString('shop/catalog/product_list');
                }
            }
            else
            {
                if($pattern && $pattern != '*')
                {
                    SearchLogHelper::log($pattern, 1);
                }

                $this->view->products = $products;
                $html                 = $this->renderInString('shop/catalog/product_list');
            }

            $result = array('html' => $html, 'button_more_enable' => $button_more_enable, 'count' => count($products));

            JsonResponse::result($result);
        }   

        // Поиск товара
        public function search()
        {
            $pattern = $this->request('products_query', NULL);

            $this->view->menu_active = 'shop';

            if($pattern && mb_strlen($pattern, 'utf-8') > 2)
            {
                $this->view->by_page    = 16;
                $this->view->pattern    = Register::get('db')->escape($pattern);
                $this->view->page_title = 'Лекарства';

                $this->render('shop/catalog/index');
            }
            else
            {
                $this->redirect('catalog', 'shop');
            }
        }

        // Живой поиск товара
        public function ajaxLiveSearch()
        {
            /**
             * @var ProductManager $product_manager
             */
            $this->layout = 'ajax';

            $search_params = new ProductSearchCriteria();

            if(!isset($this->current_account) || !$this->current_account || !$this->current_account->is_product_admin)
            {
                $search_params->is_active = 1;
            }

            $search_params->by_page = 5;
            $search_params->page    = 1;

            $pattern = $this->request('pattern');
            if($pattern && $pattern != '*')
            {
                $search_params->full_name = $pattern;
            }

            $product_manager = ModelManagerFactory::getByName('product');
            $products        = $product_manager->getListByModelSearchCriteria($search_params);

            if(count($products) > 0)
            {
                $success = 1;
            }
            else
            {
                $success = 0;
            }

            $this->view->products = $products;
            $html                 = $this->renderInString('shop/blocks/live_search_block');

            $result = array('html' => $html, 'success' => $success);

            JsonResponse::result($result);
        }

        public function ajaxSearchLog()
        {
            $this->layout = 'ajax';

            $query = $this->request('query');

            SearchLogHelper::log($query, 1);

            JsonResponse::result(array('result' => '1'));
        }

        public function getLicenseImages()
        {
            $this->layout = 'image_slider';

            $this->render('blocks/license_images');
        }
    }
