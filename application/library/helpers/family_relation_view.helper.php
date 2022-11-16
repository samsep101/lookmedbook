<?php

class FamilyRelationViewHelper {

    public function getReversedRelation($status, $from_account_id)
    {
        $from_account = ModelManagerFactory::getByName('account')->getOneById($from_account_id);
        switch ($status) {
            case 'Муж':
                return 'Жена';
                break;
            case 'Жена':
                return 'Муж';
                break;
            case 'Брат':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Брат';
                else if ($from_account->sex_id == 2)
                    return 'Сестра';
                break;
            case 'Сестра':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Брат';
                else if ($from_account->sex_id == 2)
                    return 'Сестра';
                break;
            case 'Бабушка':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Внук';
                else if ($from_account->sex_id == 2)
                    return 'Внучка';
                break;
            case 'Дедушка':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Внук';
                else if ($from_account->sex_id == 2)
                    return 'Внучка';
                break;
            case 'Мать':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Сын';
                else if ($from_account->sex_id == 2)
                    return 'Дочь';
                break;
            case 'Отец':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Сын';
                else if ($from_account->sex_id == 2)
                    return 'Дочь';
                break;
            case 'Сын':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Отец';
                else if ($from_account->sex_id == 2)
                    return 'Мать';
                break;
            case 'Дочь':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Отец';
                else if ($from_account->sex_id == 2)
                    return 'Дочь';
                break;
            case 'Внук':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Дедушка';
                else if ($from_account->sex_id == 2)
                    return 'Бабушка';
                break;
            case 'Внучка':
                if ($from_account->sex_id == null)
                    return '';
                else if ($from_account->sex_id == 1)
                    return 'Дедушка';
                else if ($from_account->sex_id == 2)
                    return 'Бабушка';
                break;
        }
    }
}