<?php
    class EmailApi
    {

        private $unisender_api;

        public function __construct()
        {
            $this->unisender_api = EmailApiFactory::getApi();
        }

        public function createList($name)
        {

            $data = $this->unisender_api->createList(array(
                'title' => $name
            ));

            if (isset($data['error']))
                return FALSE;
            else
                return $data['result']['id'];
        }


        public function subscribe($email, $list_name)
        {
            $list_id = $this->getListIdByListName($list_name);

            if ($list_id) {
                $result = $this->unisender_api->subscribe(
                    array(
                        'fields'       => array(
                            'email' => $email
                        ),
                        'list_ids'     => $list_id,
                        'double_optin' => 3
                    )
                );
                return TRUE;
            }

            return FALSE;
        }

        public function getListIdByListName($list_name)
        {
            $lists = $this->unisender_api->getLists();
            if (count($lists))
                foreach ($lists['result'] as $list) {
                    if ($list['title'] == $list_name)
                        return $list['id'];
                }
            return FALSE;
        }

        public function unsubscribe($email)
        {
            $data = $this->unisender_api->unsubscribe(
                array(
                    'contact_type' => 'email',
                    'contact'      => $email
                )
            );

            if (isset($data['result']))
                return TRUE;
            else
                return FALSE;
        }

        public function export_contacts()
        {
            $flag = TRUE;
            $offset = 0;

            $result = array();

            while ($flag) {
                $contacts = $this->unisender_api->export_contacts(array(
                    'limit'  => 1000,
                    'offset' => $offset
                ));


                $offset += 1000;

                $result = array_merge($result, $contacts['result']['data']);

                if (!count($contacts['result']['data']))
                    $flag = FALSE;
            }

            return $result;
        }
    }