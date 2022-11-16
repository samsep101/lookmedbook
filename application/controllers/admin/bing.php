<?php
    class BingAdminController extends Controller
    {
        public function ajaxDownloadImages()
        {
            /**
             * @var ImageManager $image_manager
             * @var ImageModel $image
             */

            $user_id = Acl::userId();
            $acl = new Acl($user_id);

            if(!$acl->hasRights('product', 'edit'))
            {
                JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
            }

            $query = $this->request('query');

            if($query)
            {
                $api = ImageSearchApiFactory::getInstance();

                $search_params = array(
                    'title' => $query,
                    'size' => 'Large',
                    'count' => 50
                );

                $images = $api->searchImage($search_params);
                $api->incrementTransactionsCount();

                if($images)
                {
                    JsonResponse::result($images);
                }
                else
                {
                    JsonResponse::error(ImageFindStatusModel::NOT_FOUND);
                }
            }
            else
            {
                JsonResponse::error(ImageFindStatusModel::ERROR);
            }
        }

        public function ajaxSaveImage()
        {
            /**
             * @var ProductManager $product_manager
             * @var ProductModel $product
             */

            $user_id = Acl::userId();
            $acl = new Acl($user_id);

            if(!$acl->hasRights('product', 'edit'))
            {
                JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
            }

            $src = $this->request('src');
            $product_id = $this->request('product_id');

            $product_manager = ModelManagerFactory::getByName('product');
            $product = $product_manager->getOneById($product_id);

            if($product)
            {
                $alias = ($product->image_alias) ? $product->image_alias :  null;
                $image_id = ImageUploader::loadImage($src, 'product/', $alias);

                $product->image_id = $image_id;
                $product->image_find_status_id = ImageFindStatusModel::OK;
                $product->save();

                JsonResponse::result(1);
            }
            else
            {
                JsonResponse::error(1);
            }
        }
    }