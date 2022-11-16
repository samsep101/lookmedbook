<?php
	class MobilePushNotificationSenderHelper
	{
		/**
		 * @var IApplePushNotificationService
		 */
		private $push_notification_service;

		public function __construct()
		{
			$this->push_notification_service = ApplePushNotificationServiceFactory::getService();
		}

		public function sendVisitNotification(VisitModel $visit, $type)
		{
			if (!$visit->account || !$visit->account->mobile_notification_token)
				return;

			switch($type)
			{
				case MobilePushNotificationTypeModel::NEED_TO_WRITE_A_REVIEW:
					$template_code = 'push:need_to_write_review';
					$push_type = 3;
					break;
				case MobilePushNotificationTypeModel::VISIT_CONFIRMED:
					$template_code = 'push:visit_confirmed';
					$push_type = 2;
					break;
				case MobilePushNotificationTypeModel::VISIT_IN_A_WEEK:
					$template_code = 'push:visit_in_a_week';
					$push_type = 1;
					break;
				case MobilePushNotificationTypeModel::VISIT_TODAY:
					$template_code = 'push:visit_today';
					$push_type = 1;
					break;
				case MobilePushNotificationTypeModel::VISIT_TOMORROW:
					$template_code = 'push:visit_tomorrow';
					$push_type = 1;
					break;
				default:
					throw new Exception('Передан неверный параметр');
			}

			$data = array(
				'type' => $push_type,
				'visitId' => $visit->getId()
			);

			$lookup = array(
				'doctor_full_name' => $visit->doctor ? $visit->doctor->full_name : 'не указан',
				'clinic_name' => $visit->clinic ? $visit->clinic->name : 'не указано',
				'clinic_address' => $visit->clinic ? $visit->clinic->address : 'не указан',
				'visit_time' => DateViewHelper::date($visit->visit_start_time),
				'visit_id' => $visit->getId(),
				'specialty_genitive_name' => $visit->specialty ? $visit->specialty->genitive_name : '',
				'specialty_dative_name' => $visit->specialty ? $visit->specialty->dative_name : '',
				'date' => date('d.m.Y', strtotime($visit->visit_start_time)),
				'datetime' => DateViewHelper::date($visit->visit_start_time, 'date_and_time')
			);

			$token = $visit->account->mobile_notification_token->token;
			$message = MailTemplateDataHelper::getText($template_code, $lookup);

			$params = new ApplePushNotificationParams();
			$params->alert = $message;

			$this->push_notification_service->send($token, $params, $data);
		}

		public function sendVisitsNotifications(array $visits, $type)
		{
			if($visits)
			{
				foreach($visits as $visit)
				{
					self::sendVisitNotification($visit, $type);
				}
			}
		}
	}