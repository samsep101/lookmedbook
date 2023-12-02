<?php
class HelpController extends BaseController
{
    public $layout = 'home';

    public function index()
    {
        //AuthHelper::checkAuth();

        $rubrics = ModelManagerFactory::getByName('help_rubric')->getActiveList();
        $this->view->rubrics = $rubrics;

        $subrubrics = ModelManagerFactory::getByName('help_subrubric')->getActiveList();
        $this->view->subrubrics = $subrubrics;

        $materials = ModelManagerFactory::getByName('help_material')->getActiveList();
        $this->view->materials = $materials;

	    $this->view->page_title = 'Помощь - «'.SITE_NAME.'»';

        $this->view->label_for_counters = 'help';
    }

    public function searchResults()
    {
        $help_query = $this->request('help_query');
        $by_page = $this->request('by_page', 10);
        $page = 1;
        $get_extra_entry = 1;

        $this->view->query = $help_query;

        if ($help_query) {
            $materials = ModelManagerFactory::getByName('help_material')->getActiveListByTitleOrContent($help_query, $by_page, $page, $get_extra_entry);

            if (count($materials) == 0)
                $this->redirectUrl('/help?help_query='.$help_query);

            $next_page_flag = (isset($materials[$by_page]));
            unset($materials[$by_page]);

            $this->view->materials = $materials;

            if ($next_page_flag) {
                $next_button = '<a class="view-more"><i></i>Показать ещё 10 пунктов</a>';
                $this->view->next_button = $next_button;
            }
        }
        else
            $this->redirectUrl('/help');


	    $this->view->page_title = 'Результаты поиска';
    }

    public function ajaxMoreSearchResults()
    {
        $help_query = $this->request('help_query');
        $by_page = 10;
        $page = $this->request('page');
        $get_extra_entry = 1;

        $materials = ModelManagerFactory::getByName('help_material')->getActiveListByTitleOrContent($help_query, $by_page, $page, $get_extra_entry);

        $next_page_flag = (isset($materials[$by_page]));
        unset($materials[$by_page]);

        $materials_string = '';
        foreach ($materials as $material){
            $materials_string.='
                <li> <a class="help-result">'.$material->title.'</a>
                    <div class="drop">
                        <p>'.$material->content.'</p>
                    </div>
                </li>';
        }

        $button_string = '';
        if ($next_page_flag) {
            $button_string.= '<a class="view-more"><i class="icon-loader"></i>Показать ещё 10 пунктов</a>';
        }

        JsonResponse::result(array('materials' => $materials_string,'next_page_button' => $button_string));
    }
}