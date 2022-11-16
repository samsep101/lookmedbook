<?php
class CallToUserManager extends ModelManager {

    protected $table_name = 'call_to_user';
    protected $model_name = 'CallToUserModel';

    public function afterSave(CallToUserModel $model)
    {
        $tokens = array(
            'user_name' => $model->name,
            'phone' => $model->phone
        );

        $code = 'sms_call_to_user';
        $template_data = MailTemplateDataHelper::getTemplateByCodeAndTokens($code, $tokens);
        ServiceNotificationHelper::phoneNotification($template_data->text, 'is_call_to_clinic');

        $visit_mail = new VisitMailModel();
        $visit_mail->visit_mail_type_id = VisitMailTypeModel::NEW_CALL_TO_USER;
        $visit_mail->tokens = $tokens;
        $visit_mail->send_not_visit();
    }

    public function getOneByNameAndPhone($name, $phone)
    {
        $sql = 'SELECT *
                    FROM call_to_user
                    WHERE phone = "' . $this->db->escape($phone) . '"
                    AND name = "' . $this->db->escape($name) . '"';
        $data = $this->db->query($sql);

        return (isset($data[0])) ? $this->initOne($data[0]) : null;
    }
}