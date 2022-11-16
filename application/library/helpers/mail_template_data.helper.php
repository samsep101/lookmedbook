<?php
class MailTemplateDataHelper
{
    public function sendMessage(){
        $tokens = array(
            'user_name' => 'Someone',
            'site_name' => 'lookmedbook.com',
            'link'      => 'www.asd.com'
        );
        $code = 'confirm_reg';
    }

	public static function getText($code, $tokens)
	{
		$mail_template = ModelManagerFactory::getByName('mail_template')->getOneByCodeAndTokens($code);
		$text = $mail_template['content'];
		foreach ($tokens as $key=>$value) {
			$text = str_replace('%'.$key.'%', $value, $text);
		}
		$text = preg_replace('/%[^ %]+%/ims', '', $text);

		return $text;
	}

    public static function getTemplateByCodeAndTokens($code,$tokens)
    {
        $mail_template = ModelManagerFactory::getByName('mail_template')->getOneByCodeAndTokens($code);
        $text = $mail_template['content'];
        foreach ($tokens as $key=>$value) {
            $text = str_replace('%'.$key.'%', $value, $text);
        }
        $text = preg_replace('/%[^ %]+%/ims', '', $text);

        if ($mail_template['title']) {
            foreach ($tokens as $key=>$value) {
                $mail_template['title'] = str_replace('%'.$key.'%', $value, $mail_template['title']);
            }
            $mail_template['title'] = preg_replace('/%[^ %]+%/ims', '', $mail_template['title']);
        }

        $template_data = new MailTemplate();
        $template_data->from = '';
        $template_data->to = '';
        $template_data->title = $mail_template['title'];
        $template_data->text = $text;

        return $template_data;
    }
}