<?php
	/**
	 * @property int $id
	 * @property string $title
	 * @property string $text
	 * @property int $visit_id
	 * @property VisitModel $visit
	 * @property CallToUserModel $call_to_user
	 * @property int $visit_mail_type_id
	 * @property VisitMailTypeModel $visit_mail_type
	 * @property datetime $dt
	 * @property int $visit_mail_status_id
	 * @property VisitMailStatusModel $visit_mail_status
	 * @property int $attach_mail_id
	 * @property VisitMailModel $attach_mail
	 * @property array() $tokens
	 *
	 * @property int $order_id
	 * @property OrderModel $order
	 *
	 */
    class VisitMailModel extends DynamicModel
	{
		protected function _field_attach_mail()
		{

			$this->attach_mail = $this->getManager()->getOneById($this->attach_mail_id);

			return $this->attach_mail;
		}

		// На самом деле добавляем в очередь
		public function send($model = NULL)
		{
            if ($model)
            {
                $this->visit = $model;
            }
			if(!$this->visit_mail_type_id || !$this->visit_id)
			{
				throw new Exception('Объект не инициализирован');
			}

			$mail_content = $this->getMailContent();
			$this->title = $mail_content->title;
			$this->text = $mail_content->text;

			$this->visit_mail_status_id = VisitMailStatusModel::NOT_SEND;
			$this->save();
		}

        // На самом деле добавляем в очередь
        public function send_not_visit()
        {
            if(!$this->visit_mail_type_id)
            {
                throw new Exception('Объект не инициализирован');
            }

            $mail_content = $this->getMailContent();
            $this->title = $mail_content->title;
            $this->text = $mail_content->text;

            $this->visit_mail_status_id = VisitMailStatusModel::NOT_SEND;
            $this->save();
        }

		// тут отправка сообщений
		public function run()
		{
			switch($this->visit_mail_type_id)
			{
				case VisitMailTypeModel::NEW_VISIT_TO_ADMIN:
					ServiceNotificationHelper::emailNotification($this->text, $this->title, 'is_record');
					break;
				case VisitMailTypeModel::NEW_VISIT_TO_USER:
					EmailSenderHelper::sendMessage($this->visit->account->email, $this->title, $this->text);
					break;
				case VisitMailTypeModel::VISIT_CANCEL_TO_ADMIN:
					ServiceNotificationHelper::emailNotification($this->text, $this->title, 'is_cancel');
					break;
				case VisitMailTypeModel::VISIT_CANCEL_TO_USER:
					EmailSenderHelper::sendMessage($this->visit->account->email, $this->title, $this->text);
					break;
				case VisitMailTypeModel::VISIT_CONFIRM_TO_USER:
					EmailSenderHelper::sendMessage($this->visit->account->email, $this->title, $this->text);
					break;
                case VisitMailTypeModel::NEW_CALL_TO_USER:
                    ServiceNotificationHelper::emailNotification($this->text, $this->title, 'is_call_to_clinic');
                    break;
				case VisitMailTypeModel::SHOP_ORDER:
					EmailSenderHelper::sendMessage($this->order->email, $this->title, $this->text);
					break;
			}

			$this->visit_mail_status_id = VisitMailStatusModel::SEND;
			$this->save();
		}

		public function attachMail(VisitMailModel $visit_mail_model)
		{

		}

		public function getMailContent()
		{
			$mail_content = new MailContent();
			$visit_mail_manager = new VisitMailManager();

			$attach_mail = null;
			switch($this->visit_mail_type_id)
			{
				case VisitMailTypeModel::NEW_VISIT_TO_ADMIN:
                    $birthday = isset($this->visit->account->birthday) ? ', род. '.DateViewHelper::date($this->visit->account->birthday) : false;

                    $scheduleDtStart = isset($this->visit->schedule->dt_start) ? $this->visit->schedule->dt_start : null;
                    $scheduleDtEnd = isset($this->visit->schedule->dt_end) ? $this->visit->schedule->dt_end : null;
					if (!$this->visit->doctor_id  || $this->visit->doctor->is_virtual == 1) {
                        $tokens = array(
                            'visit_id' => $this->visit->getId(),
                            'clinic_name' => $this->visit->clinic_name,
                            'clinic_phones' => $this->visit->clinic_phone,
                            'user_name' => $this->visit->full_name,
                            'birthday' => $birthday,
                            'doctor_specialty' => $this->visit->visit_specialty,
                            'doctor_specialty_d' => $this->visit->dative_visit_specialty,
                            'doctor_specialty_g' => $this->visit->genitive_visit_specialty,
                            'doctor_specialty_pl' => $this->visit->plural_visit_specialty,
                            'visit_date' => DateViewHelper::date($this->visit->dt, 'day_and_month'),
                            'visit_time' => DateViewHelper::date($scheduleDtStart, 'time') . '-' . DateViewHelper::date($scheduleDtEnd, 'time'),
                            'clinic_address' => isset($this->visit->clinic) ? $this->visit->clinic->address : '',
                            'user_phone' => preg_replace('/[^0-9]/', '', $this->visit->phone),
                            'user_email' => isset($this->visit->account->email) ?  $this->visit->account->email : null,
                            'edit_url' => SITE_URL . '/admin/visit/edit/?id=' . $this->visit->getId()
                        );
                        $code = 'mail_record_request_without_doctor';
                    }
                    else {
                        $tokens = array(
                            'visit_id' => $this->visit->getId(),
                            'clinic_name' => $this->visit->clinic_name,
                            'clinic_phones' => $this->visit->clinic_phone,
                            'user_name' => $this->visit->full_name,
                            'birthday' => $birthday,
                            'doctor_specialty' => $this->visit->visit_specialty,
                            'doctor_specialty_d' => $this->visit->dative_visit_specialty,
                            'doctor_specialty_g' => $this->visit->genitive_visit_specialty,
                            'doctor_specialty_pl' => $this->visit->plural_visit_specialty,
                            'doctor_name' => $this->visit->doctor_name,
                            'visit_date' => DateViewHelper::date($this->visit->dt, 'day_and_month'),
                            'visit_time' => DateViewHelper::date($scheduleDtStart, 'time') . '-' . DateViewHelper::date($scheduleDtEnd, 'time'),
                            'clinic_address' => isset($this->visit->clinic->address) ? $this->visit->clinic->address : null,
                            'user_phone' => preg_replace('/[^0-9]/', '', $this->visit->phone),
                            'user_email' => isset($this->visit->account->email) ? $this->visit->account->email : null,
                            'edit_url' => SITE_URL . '/admin/visit/edit/?id=' . $this->visit->getId()
                        );
                        $code = 'mail_record_request';
                    }

					$template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
					$mail_content->title = $template_data->title;
					$mail_content->text = $template_data->text;
					break;
				case VisitMailTypeModel::NEW_VISIT_TO_USER:
					$tokens = array('visit_id' => $this->visit->getId());
					if(date('H:i') > date('H:i', strtotime('9:00')) && date('H:i') < date('H:i', strtotime('20:30')))
					{
						$code = 'user_email_record_request_day';
					}
					else
					{
						$code = 'user_email_record_request_night';
					}
					$template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);

					$mail_content->title = $template_data->title;
					$mail_content->text = $template_data->text;
					break;
				case VisitMailTypeModel::VISIT_CANCEL_TO_ADMIN:
					$attach_mail = $visit_mail_manager->getOneByVisitIdAndVisitMailTypeId($this->visit_id, VisitMailTypeModel::NEW_VISIT_TO_ADMIN);

                    if (!$this->visit->doctor_id  || $this->visit->doctor->is_virtual == 1) {
                        $tokens = array(
                            'visit_id' => $this->visit->getId(),
                            'clinic_name' => $this->visit->clinic_name,
                            'user_name' => $this->visit->full_name,
                            'doctor_specialty' => $this->visit->visit_specialty,
                            'doctor_specialty_g' => $this->visit->genitive_visit_specialty,
                            'doctor_specialty_d' => $this->visit->dative_visit_specialty,
                            'doctor_specialty_pl' => $this->visit->plural_visit_specialty,
                            'visit_date' => DateViewHelper::date($this->visit->dt, 'day_and_month'),
                            'visit_time' => DateViewHelper::date($this->visit->schedule->dt_start, 'time') . '-' . DateViewHelper::date($this->visit->schedule->dt_end, 'time'),
                        );
                        $code = 'email_record_cancel_without_doctor';
                    }
                    else {
                        $tokens = array(
                            'visit_id' => $this->visit->getId(),
                            'clinic_name' => $this->visit->clinic_name,
                            'user_name' => $this->visit->full_name,
                            'doctor_specialty' => $this->visit->visit_specialty,
                            'doctor_specialty_g' => $this->visit->genitive_visit_specialty,
                            'doctor_specialty_d' => $this->visit->dative_visit_specialty,
                            'doctor_specialty_pl' => $this->visit->plural_visit_specialty,
                            'doctor_name' => $this->visit->doctor_name,
                            'visit_date' => DateViewHelper::date($this->visit->dt, 'day_and_month'),
                            'visit_time' => DateViewHelper::date($this->visit->schedule->dt_start, 'time') . '-' . DateViewHelper::date($this->visit->schedule->dt_end, 'time'),
                        );
                        $code = 'email_record_cancel';
                    }

					$template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);

					$mail_content->title = $template_data->title;
					$mail_content->text = $template_data->text;
					break;
				case VisitMailTypeModel::VISIT_CANCEL_TO_USER:
					$attach_mail = $visit_mail_manager->getOneByVisitIdAndVisitMailTypeId($this->visit_id, VisitMailTypeModel::VISIT_CONFIRM_TO_USER);

					if(!$attach_mail)
					{
						$attach_mail = $visit_mail_manager->getOneByVisitIdAndVisitMailTypeId($this->visit_id, VisitMailTypeModel::NEW_VISIT_TO_USER);
					}

					$tokens = array('visit_id' => $this->visit->getId(), 'clinic_name' => $this->visit->clinic_name, 'visit_date' => DateViewHelper::date($this->visit->dt, 'day_and_month_and_week_day'), 'visit_time' => DateViewHelper::date($this->visit->schedule->dt_start, 'time') . '-' . DateViewHelper::date($this->visit->schedule->dt_end, 'time'),);
					$code = 'user_email_record_cancel';
					$template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);

					$mail_content->title = $template_data->title;
					$mail_content->text = $template_data->text;
					break;
				case VisitMailTypeModel::VISIT_CONFIRM_TO_USER:

                    if (!$this->visit->doctor_id  || $this->visit->doctor->is_virtual == 1) {
                        $tokens = array(
                            'visit_id' => $this->visit->getId(),
                            'clinic_name' => $this->visit->clinic_name,
                        	'clinic_address' => $this->visit->clinic_address,
                            'doctor_specialty' => $this->visit->visit_specialty,
                            'doctor_specialty_g' => $this->visit->genitive_visit_specialty,
                            'doctor_specialty_d' => $this->visit->dative_visit_specialty,
                            'doctor_specialty_pl' => $this->visit->plural_visit_specialty,
                            'visit_date' => DateViewHelper::date($this->visit->dt, 'day_and_month_and_week_day'),
                            'visit_time' => DateViewHelper::date($this->visit->visit_start_time, 'time')
                        );
                        $code = 'email_record_confirm_without_doctor';
                    }
                    else {
                        $tokens = array(
                            'visit_id' => $this->visit->getId(),
                            'clinic_name' => $this->visit->clinic_name,
                        	'clinic_address' => $this->visit->clinic_address,
                            'doctor_specialty' => $this->visit->visit_specialty,
                            'doctor_specialty_g' => $this->visit->genitive_visit_specialty,
                            'doctor_specialty_d' => $this->visit->dative_visit_specialty,
                            'doctor_specialty_pl' => $this->visit->plural_visit_specialty,
                            'doctor_name' => $this->visit->doctor_name,
                            'visit_date' => DateViewHelper::date($this->visit->dt, 'day_and_month_and_week_day'),
                            'visit_time' => DateViewHelper::date($this->visit->visit_start_time, 'time')
                        );
                        $code = 'email_record_confirm';
                    }

					$template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);

					$mail_content->text = $template_data->text;
					$mail_content->title = $template_data->title;

					$attach_mail = $visit_mail_manager->getOneByVisitIdAndVisitMailTypeId($this->visit_id, VisitMailTypeModel::NEW_VISIT_TO_USER);
					break;
                case VisitMailTypeModel::NEW_CALL_TO_USER:

                    $code = 'email_call_to_user';
                    $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $this->tokens);
                    $mail_content->title = $template_data->title;
                    $mail_content->text = $template_data->text;
                    break;
			}

			if($attach_mail)
			{
				$this->attach_mail_id = $attach_mail->getId();
			}

			if($this->attach_mail)
			{
				$attach_content = $this->attach_mail->getMailContent();

				if(($this->visit_mail_type_id == VisitMailTypeModel::VISIT_CANCEL_TO_USER) || ($this->visit_mail_type_id == VisitMailTypeModel::VISIT_CANCEL_TO_ADMIN)
				)
				{
					$mail_content->title = 'Re: ' . $attach_content->title;
					$mail_content->text .= '<blockquote style="color:silver; border-left: 1px solid silver; padding-left: 40px; margin-left: 10px;">' . $attach_content->text . '</blockquote>';
				}
			}

			return $mail_content;
		}
	}