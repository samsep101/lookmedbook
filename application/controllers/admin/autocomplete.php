<?php

class autocompleteAdminController extends AdminBaseController {
    /** @author Playmore 2017 (playmoredevelop@gmail.com) */

    public function clinic() {

        $search = $this->request('search', false);
        header('Content-Type: application/json', true);

        if(!empty($search)){

            $manager = ModelManagerFactory::getByName('clinic');

            $list = $manager->getAutocomplete($search);

            exit(json_encode(['suggestions' => $list]));
        }

        exit(json_encode(['suggestions' => []]));
    }

    public function beforeAction() {
        return true;
    }
}

/* END CLASS: Autocomplete extends AdminBaseController */