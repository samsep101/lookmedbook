<?php

class ProductManager extends AliasManager
{
  protected $table_name = "product";
  protected $model_name = "ProductModel";

  protected $transliterated_field = 'clean_name';

  protected function beforeSave(DynamicModel $model)
  {
    /**
     * @var ProductModel $model
     */
    if ($model->isImageUploaded()) {
      $model->image_id = $model->uploaded_image_id;
      $model->is_image_confirmed = 1;
      $model->image_find_status_id = ImageFindStatusModel::OK;
    }

    if ($model->image_find_status_id == ImageFindStatusModel::FIND_IN_VIDAL) {
      $model->is_image_confirmed = 1;
    }

    if (!$model->fill_information_status_id) {
      $model->fill_information_status_id = FillInformationStatusModel::IN_QUEUE;
    }

    if ($model->checkbox_set && $model->is_image_confirmed && $model->isImageConfirmedStatusChanged()) {
      $model->image_find_status_id = ImageFindStatusModel::OK;
    }

    parent::beforeSave($model);
  }

  protected function afterSave(ProductModel $model)
  {
    /**
     * @var ProductInformationManager $product_information_manager
     */
    $product_information_manager = ModelManagerFactory::getByName('product_information');
    $product_information = $product_information_manager->getOneByProductId($model->getId());

    if (!$product_information) {
      $product_information = new ProductInformationModel();
      $product_information->product_id = $model->getId();
    }

    $product_information->composition = $model->composition;
    $product_information->zip_info = $model->zip_info;
    $product_information->dosage = $model->dosage;
    $product_information->side_effects = $model->side_effects;
    $product_information->overdosage = $model->overdosage;
    $product_information->storage_condition = $model->storage_condition;
    $product_information->indications = $model->indications;
    $product_information->contra_indications = $model->contra_indications;
    $product_information->pharma_effects = $model->pharma_effects;
    $product_information->save();


    $model->extend_information->interaction = $model->interaction;
    $model->extend_information->lactation = $model->lactation;
    $model->extend_information->special_information = $model->special_information;
    $model->extend_information->pharm_delivery = $model->pharm_delivery;
    $model->extend_information->save();


    // данный код для логирования действий авторизованного пользователя
    if (Acl::userId() && ($model->isImageConfirmedStatusChanged() || $model->isFillInformationStatusChanged())) {
      // если какой-то из статусов поменялся на "Готово"
      if (($model->is_image_confirmed && $model->isImageConfirmedStatusChanged())
        || (($model->fill_information_status_id == FillInformationStatusModel::OK) && $model->isFillInformationStatusChanged())
      ) {
        $shop_content_log = new ShopContentmanagerLogModel();
        $shop_content_log->user_id = Acl::userId();
        $shop_content_log->product_id = $model->getId();
        $shop_content_log->date = date('Y-m-d');
        $shop_content_log->dt = date('Y-m-d H:i:s');

        if (($model->is_image_confirmed && $model->isImageConfirmedStatusChanged())) {
          $shop_content_log->is_image_uploaded = 1;
        }

        if ((($model->fill_information_status_id == FillInformationStatusModel::OK) && $model->isFillInformationStatusChanged())) {
          $shop_content_log->is_status_confirmed = 1;
        }

        $shop_content_log->save();
      }
    }

  }

  public function afterDelete(DynamicModel $model)
  {
    /**
     * @var ProductCategoryManager $product_category_manager
     */

    $product_category_manager = ModelManagerFactory::getByName('product_category');
    $product_category_manager->updateActiveStatusByProductCategoryId($model->product_category_id);
  }

  /**
   * @var int $manufacturer_id
   * @return ProductModel[]
   */
  public function getListByManufacturerId($manufacturer_id)
  {
    $data = $this->orm_model->select()->where('manufacturer_id = ?', $manufacturer_id)->fetchAll();
    return $this->initList($data);
  }

  /**
   * @var int $product_category_id
   * @var bool $is_active
   *
   * @return ProductModel[]
   */
  public function getListByProductCategoryId($product_category_id, $is_active = false)
  {
    $sql = 'SELECT *
                    FROM product
                    WHERE product_category_id = ' . (int)$product_category_id;

    if ($is_active) {
      $sql .= ' AND is_active = 1';
    }

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  /**
   * @param $service_code
   *
   * @return ProductModel
   */
  public function getOneByServiceCode($service_code)
  {
    $data = $this->orm_model->select()->where('service_code = ?', $service_code)->fetchOne();
    return $this->initOne($data);
  }

  public function updateActiveStatusByDtActual($dt_actual)
  {
    $sql = 'UPDATE product
					SET is_active =  0
					WHERE dt_actual < "' . $dt_actual . '"';
    $this->db->query($sql);

    $sql = 'UPDATE product
					SET is_active =  1
					WHERE dt_actual >= "' . $dt_actual . '"';
    $this->db->query($sql);
  }

  public function getListByOrderId($order_id)
  {
    $sql = 'SELECT p.*
                    FROM `order` o
                    INNER JOIN product_to_order p2o ON p2o.order_id = o.id
                    INNER JOIN product p ON p.id = p2o.product_id
                    WHERE o.id = ' . (int)$order_id;

    $data = $this->db->query($sql);

    return $this->initList($data);
  }

  /**
   * @param ModelSearchCriteria $criteria
   * @return ProductModel[]
   */
  public function getListByModelSearchCriteria(ModelSearchCriteria $criteria)
  {
    $index_control = new ElasticSearchProductIndexControl();

    try {
      $ids = $index_control->search($criteria);
    } catch (Exception $e) {
      $ids = [];
    }

    return $this->getListByIds($ids);
  }


  /**
   * @param $product_id
   * @return int
   */
  public function getQuantityByProductId($product_id)
  {
    $sql = 'SELECT quantity
					FROM product
					WHERE id = ' . (int)$product_id;

    $data = $this->db->query($sql);

    $count = (count($data)) ? $data[0]['quantity'] : null;
    return $count;
  }


  /**
   * @param $fill_information_status_id
   * @return ProductModel[]
   */
  public function getListByFillInformationStatusId($fill_information_status_id)
  {
    $data = $this->orm_model->select()->where('fill_information_status_id = ?', (int)$fill_information_status_id)->fetchAll();
    return $this->initList($data);
  }

  /**
   * @param $fill_information_status_id
   * @param $offset
   * @param $limit
   * @return ProductModel[]
   */
  public function getListByFillInformationStatusIdWithLimit($fill_information_status_id, $offset, $limit)
  {
    $sql = 'SELECT *
					FROM ' . $this->table_name . '
					WHERE  fill_information_status_id = ' . (int)$fill_information_status_id . '
					LIMIT' . (int)$offset . ', ' . (int)$limit;
    $data = $this->db->query($sql);

    return $this->initList($data);
  }
}