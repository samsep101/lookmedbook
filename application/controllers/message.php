<?php

    class MessageController extends BaseController
    {
        public $layout = 'home';

        public function get()
        {
            $message_id = $this->request('id');

            $message_manager = new MessageManager();
            if ($message = $message_manager->getOneById($message_id))
            {
                $message_manager->readMessage($message->to_account_id, $message->id);
                $this->view->message = $message;
            } else {
                $this->error404();
            }

        }
    }