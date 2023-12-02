<?php

class AppealManager extends ModelWAccountidManager
{

  protected $table_name = 'appeal';
  protected $model_name = 'AppealModel';

  public function beforeSave(DynamicModel $model)
  {
    /**
     * @var AppealModel $model
     */
    $model->phone_number = preg_replace('/[^0-9]/', '', $model->phone_number);

    if ($model->isNew()) {
      if (!$model->dt_create) {
        $model->dt_create = date('Y-m-d H:i:s');
      }

      /**
       * @var AccountManager $account_manager
       */
      $account_manager = ModelManagerFactory::getByName('account');

      $account = $account_manager->getOneByPhoneNumber($model->phone_number);
      if ($account) {
        $model->account_id = $account->getId();
      } else {
        if ($model->is_with_visit) {
          $account = new AccountModel();
          $account->first_name = $model->first_name;
          $account->middle_name = $model->middle_name;
          $account->last_name = $model->last_name;

          $email = $model->phone_number . '@user.ru';

          $account_password = StringGeneratorHelper::generateNumbers(6);
          $account->password = $account_password;
          $account->email = $email;

          $account->save();

          $model->account_id = $account->getId();

          $account_phone = new AccountPhoneModel();
          $account_phone->account_id = $account->getId();
          $account_phone->phone = $model->phone_number;
          $account_phone->is_confirmed = 1;
          $account_phone->save();

          // Высылаем письмо о регистрации
          $tokens = array(
            'email' => $email,
            'password' => $account_password
          );

          $code = 'sms_account_registration';
          $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
          SmsSender::sendMessage($model->phone_number, $template_data->text);

          /**
           * @var AccountPhoneManager $account_phone_manager
           */
          $account_phone_manager = ModelManagerFactory::getByName('account_phone');
          $account_phone_manager->deleteNotConfirmedByPhone($model->phone_number);
        }

      }
    }


  }

  public function sendMailAboutSave(DynamicModel $model)
  {
    $mail_sender = new EmailSenderHelper();
    $mail_data = [
      'id' => ['title' => 'Обращение', 'value' => $model->getId(),],
      'fio' => ['title' => 'Пациент', 'value' => $model->first_name . ' ' . $model->middle_name . ' ' . $model->last_name . ' ',],
      'phone' => ['title' => 'Телефон пациента', 'value' => $model->phone_number,],
      //'email'=>['title'=>'Email пациента', 'value'=>$account->email, ],
      //'clinic'=>['title'=>'Клиника', 'value'=>, ],
      'doctor' => ['title' => 'Врач', 'value' => $model->specialty->name,],
      'comment' => ['title' => 'Коментарий', 'value' => $model->title,],
//      'test' => ['title' => 'test', 'value' => $appelTest->mailed,],
    ];
    $mail_sender->sendVisitCreatedMessage($mail_data);
  }

  public function afterSave(DynamicModel $model)
  {
      /** @var AppealModel $model */
    if ($model->is_with_visit && !$model->visit) {
      $visit_information = new VisitInformation();
      $visit_information->account_id = $model->account_id;
      $visit_information->specialty_id = $model->specialty_id;
      $visit_information->comment = $model->title;
      $visit_information->full_name = $model->full_name;
      $visit_information->visit_status_id = VisitStatusModel::TO_FILL;
      $visit_information->phone = $model->phone_number;
      $visit_information->appeal_id = $model->getId();
      $visit_information->target_call_id = $model->target_call_id;
      $visit_information->schedule_id = VisitModel::RESERVED_TIME_SLOT;
      $visit_information->processed_user = Acc::accountId();

      $visit_recorder = new VisitRecorder();
      $visit_recorder->record($visit_information);
      $visit_recorder->getVisit();

    }

    //ALTER TABLE `appeal`  ADD `mailed` tinyint unsigned NULL DEFAULT '0';
    //по какой-то причине шлется сразу несколько писем. приходится извращаться
    $appelTest = array_shift($this->getListByIds([$model->getId()]));
//    print_r([$appelTest->mailed, 'qqq']);
//    print_r([$appelTest->mailed]);
    if ($appelTest->mailed != 1) {
      $this->sendMailAboutSave($model);
      $model->mailed = 1;

      //дабы не перезапускать заново afterSave
      $appeal = new Orm(DB_PREFIX . 'appeal');
      $appeal->update(['mailed' => $model->mailed], $this->id_field_name . ' = "' . $model->getId() . '"');
    }


  }

  /**
   * @param $specialty_id
   * @return AppealModel[]
   */
  public function getListBySpecialtyId($specialty_id)
  {
    $data = $this->orm_model->select()->where('specialty_id = ?', $specialty_id)->fetchAll();
    return $this->initList($data);
  }

  /**
   * @param ModelSearchCriteria $appeal_search_params
   * @return AppealModel[]
   */
  public function getListByModelSearchCriteria(ModelSearchCriteria $appeal_search_params)
  {
    /**
     * @var AppealSearchParams $appeal_search_params
     */
    $search_params = $appeal_search_params->getSearchParams();
    if (!$search_params)
      $search_params = new SearchParams();

    if ($appeal_search_params->dt_create_from) {
      $search_params->addParam('dt_create >= ', date('Y-m-d 00:00:00', strtotime(($appeal_search_params->dt_create_from))));
    }

    if ($appeal_search_params->dt_create_to) {
      $search_params->addParam('dt_create <= ', date('Y-m-d 23:59:59', strtotime($appeal_search_params->dt_create_to)));
    }

    return $this->getListBySearchParams($search_params);
  }


}



