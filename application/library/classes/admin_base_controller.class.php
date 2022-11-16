<?php
	class AdminBaseController extends Controller
	{
		protected $csrf;

		public function getCsrf()
		{
			if(!$this->csrf)
			{
				$this->csrf = new Csrf();
			}

			return $this->csrf;
		}

		public function beforeAction()
		{
			if($this->request->isPost())
			{
				$token = $this->request->post('csrf');
				if(!$this->getCsrf()->checkToken($token))
				{
					if($this->request->isAJAX())
					{
						JsonResponse::error(ValidationErrorCodes::WRONG_CSRF_TOKEN);
					}
					else
					{
						ErrorPageViewHelper::pageCsrfError();
					}
				}
			}

			$this->view->csrf = $this->getCsrf()->getUserToken();
		}
	}