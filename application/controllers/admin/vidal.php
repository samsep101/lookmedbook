<?php
	class VidalAdminController extends Controller
	{
		public function ajaxImportProduct()
		{
			$userId = Acl::userId();
			$acl = new Acl($userId);

			if(!$acl->hasRights('product', 'edit') || !$acl->hasRights('vidal_product', 'edit'))
			{
				JsonResponse::error(ValidationErrorCodes::NOT_AUTHED);
			}

			$product_id = $this->request('product_id');
			$vidal_product_id = $this->request('vidal_product_id');

			/**
			 * @var ProductManager $product_manager
			 * @var VidalProductManager $vidal_product_manager
			 * @var ProductModel $product
			 * @var VidalProductModel $vidal_product
			 */
			$product_manager = ModelManagerFactory::getByName('product');
			$vidal_product_manager = ModelManagerFactory::getByName('vidal_product');

			$product = $product_manager->getOneById($product_id);
			$vidal_product = $vidal_product_manager->getOneById($vidal_product_id);

			if(!$product || !$vidal_product)
			{
				JsonResponse::error(ValidationErrorCodes::ERROR);
			}

			$product->fill_information_status_id = FillInformationStatusModel::OK;
			$vidal_information_fill = new VidalInformationFill();
			$vidal_information_fill->fill($product, $vidal_product);

			JsonResponse::result(true);
		}
	}