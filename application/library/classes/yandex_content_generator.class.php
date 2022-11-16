<?php
class YandexContentGenerator
{
    public function getCode($client_id)
    {
        header('Location: https://oauth.yandex.ru/authorize?response_type=code&client_id='.$client_id);
    }

    public function getToken($client_id)
    {
        header('Location: https://oauth.yandex.ru/authorize?response_type=token&client_id='.$client_id);
    }

    public function getTokenByCode($code, $client_id, $client_secret)
    {
        $url = 'https://oauth.yandex.ru/token';
        $post_data = 'grant_type=authorization_code&code='.$code.'&client_id='.$client_id.'&client_secret='.$client_secret;
        $result = CurlRequestSender::post($url, $post_data);
        $result = json_decode($result, TRUE);

        return $result;
    }

    public function generateOriginalText(DiseaseModel $disease)
    {
        $main_tab = null;
        $original_text_content = $disease->title.'.';

        $content = str_replace(chr(13), '', $disease->content);
        $content = str_replace(chr(9), '', $content);
        $content = str_replace(chr(10), '', $content);
        $content = strip_tags(html_entity_decode($content));
        $content =  html_entity_decode($content);
        $content = str_replace('<', ' ', $content);
        $content = str_replace('>', ' ', $content);
        $original_text_content.= ' '.$content;

        $disease_block_manager = new DiseaseBlockManager();
        $disease_flags = $disease_block_manager->getActiveDiseaseTabsFlagsByDiseaseId($disease->getId());
        foreach ($disease_flags as $tab => $flag) {
            if ($flag == 1) {
                $main_tab = $tab;
                break;
            }
        }
        if ($main_tab) {
            $disease_blocks_content = $disease_block_manager->getActiveListByDiseaseIdAndFlag($disease->getId(), $main_tab);

            if ($disease_blocks_content) {
                foreach ($disease_blocks_content as $disease_block) {
                    $content = str_replace(chr(13), '', $disease_block->content);
                    $content = str_replace(chr(9), '', $content);
                    $content = str_replace(chr(10), '', $content);
                    $content = strip_tags(html_entity_decode($content));
                    $content = html_entity_decode($content);
                    $content = str_replace('<', ' ', $content);
                    $content = str_replace('>', ' ', $content);
                    $original_text_content.= ' '.$disease_block->disease_block_type->name.'.';
                    $original_text_content.= ' '.$content;
                }
            }
        }
        $original = '<original-text><content>'.$original_text_content.'</content></original-text>';
        return $original;
    }

    public function sendTexts()
    {
        $host = SettingsManager::get('yandex_host_id');
        $token = SettingsManager::get('yandex_text_token');

        /**
         * @var DiseaseManager $disease_manager
         * @var DiseaseModel $disease
         */
        $disease_manager = ModelManagerFactory::getByName('disease');
        $diseases = $disease_manager->getActiveListWithLimitForYandex(100);

        $counter_send = 0;
        $counter_update = 0;
        $counter_error = 0;

        if ($diseases) {
            foreach ($diseases as $disease) {
                $result = null;
                $error = null;
                $original = self::generateOriginalText($disease);

                $url = 'https://webmaster.yandex.ru/api/v2/hosts/'.$host.'/original-texts/';
                $header = array('Authorization: OAuth '.$token, "Content-length: ".strlen(urlencode($original)));

                $result = CurlRequestSender::post($url, urlencode($original), $header);

                $xml_parser = new XmlConverter();
                $data = $xml_parser->toArray($result);

                if (isset($data['original-text'])) {
                    if ($disease->date_yandex_send == null)
                        $counter_send++;
                    else
                        $counter_update++;

                    $disease->date_yandex_send = date('Y-m-d H:i:s');
                    $disease->save();
                }
                else {
                    $error = $data['error']['_c']['message']['_v'];
                    $counter_error++;

                    $yandex_content_log = new YandexContentErrorLogModel();
                    $yandex_content_log->disease_id = $disease->getId();
                    $yandex_content_log->error = $error;
                    $yandex_content_log->date = date('Y-m-d H:i:s');
                    $yandex_content_log->save();
                }
            }
        }
        $yandex_content_log = new YandexContentLogModel();
        $yandex_content_log->content_send = $counter_send;
        $yandex_content_log->content_updated = $counter_update;
        $yandex_content_log->content_error = $counter_error;
        $yandex_content_log->date_send = date('Y-m-d H:i:s');
        $yandex_content_log->save();
    }
}