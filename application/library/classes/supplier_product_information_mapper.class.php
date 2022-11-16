<?php
	class SupplierProductInformationMapper implements IDataMapper {

		public function getMappedObject($data)
		{
			$model = new SupplierProductInformation();
			$model->code = $data['code'];
			$model->is_vital = $data['is_vital'];
			$model->manufacturer = $data['manufacturer'];
			$model->name = isset($data['name']) ? $data['name'] : '';
			$model->price = $data['price'];
			$model->quantity = $data['quantity'];
			$model->vat = isset($data['vat']) ? $data['vat'] : '';
			$model->dosage_form = isset($data['dosage_form']) ? $data['dosage_form'] : '';
			$model->dosage_form_size = isset($data['dosage_form_size']) ? $data['dosage_form_size'] : '';
			$model->size_of_a_unit = isset($data['size_of_a_unit']) ? $data['size_of_a_unit'] : '';
			$model->clean_name = (isset($data['clean_name'])) ? $data['clean_name'] : '';

			return $model;
		}

	}