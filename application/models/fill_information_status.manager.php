<?php
	class FillInformationStatusManager extends StaticDataModelManager
	{
		protected $model_data = array(
			FillInformationStatusModel::BY_NAME => array(
				'id' =>FillInformationStatusModel::BY_NAME,
				'name' => 'По имени',
			),
			FillInformationStatusModel::BY_NAME_AND_DOSAGE_FORM => array(
				'id' =>FillInformationStatusModel::BY_NAME_AND_DOSAGE_FORM,
				'name' => 'По имени и форме выпуска',
			),
			FillInformationStatusModel::BY_NAME_PART => array(
				'id' =>FillInformationStatusModel::BY_NAME_PART,
				'name' => 'По части имени',
			),
			FillInformationStatusModel::NOT_FOUND => array(
				'id' =>FillInformationStatusModel::NOT_FOUND,
				'name' => 'Не найдены',
			),
			FillInformationStatusModel::OK => array(
				'id' =>FillInformationStatusModel::OK,
				'name' => 'Готово',
			),
			FillInformationStatusModel::IN_QUEUE => array(
				'id' => FillInformationStatusModel::IN_QUEUE,
				'name' => 'В очереди'
			),
			FillInformationStatusModel::BY_NAME_AND_UNIT_SIZE => array(
				'id' => FillInformationStatusModel::BY_NAME_AND_UNIT_SIZE,
				'name' => 'По названию и единице'
			),
            FillInformationStatusModel::NOT_IN_VIDAL => array(
                'id' => FillInformationStatusModel::NOT_IN_VIDAL,
                'name' => 'Нет в базе Видаль'
            )
		);

		protected $model_name = 'FillInformationStatusModel';
	}