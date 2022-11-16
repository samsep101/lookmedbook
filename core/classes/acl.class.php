<?php

    class Acl
    {

        private $userId = NULL;
        private $db = NULL;
        private $userTable;
        private $userRoleTable;
        private $userRights;
        private $isSuper = FALSE;

        public function __construct($userId = NULL)
        {
            $this->userId = $userId;
            $this->db = Register::get('db');

        }

        public function hasUserPermission($permissionName, $userId = NULL)
        {
            return TRUE;
        }

        public static function userGrant($grant)
        {
            if (!empty($_SESSION['__acl']['user']['grant_' . $grant]))
                return 1;
            return 0;
        }

        public function getUserGrants()
        {
            if (!empty($_SESSION['__acl']['user']))
                return $_SESSION['__acl']['user'];
            return NULL;
        }

        static function getUserControllers()
        {
            $grants = Acl::getUserGrants();
            $controllers = array();
            foreach ($grants as $key => $value) {
                if (strpos($key, '_list') && !strpos($key, '_delete_list')) {
                    $controllerCode = str_replace('grant_', '', str_replace('_list', '', $key));
                    $controllerEntry = ModelManagerFactory::getByName('controller')->getOneByCode($controllerCode);

                    if ($controllerEntry)
                        if ($controllerEntry->is_active) {
                            $controllers[$controllerCode] = $controllerEntry->name;
                        }
                }
            }
            if (count($controllers)) {
                //var_dump($controllers);
                asort($controllers);
                return $controllers;
            } else {
                return NULL;
            }
        }


        public static function userLogin()
        {
            if (!empty($_SESSION['__acl']['user']['login']))
                return $_SESSION['__acl']['user']['login'];
            return NULL;
        }

		public static function userRole()
		{
			if (!empty($_SESSION['__acl']['user_type']))
				return $_SESSION['__acl']['user_type'];
			return NULL;
		}

        public function getListIds($controller)
        {
            if ($this->isSuperAdmin())
                return 'all';
            foreach ($this->userRights as $row) {
                if ($row['type'] == $controller) {
                    if (!$row['active'])
                        return 'none';

                    if ($row['list'] == 'all')
                        return 'all';

                    if ($row['list'] == 'none')
                        return 'none';

                    if ($row['list'] == 'edit') {
                        if ($row['edit'] == 'all')
                            return 'all';
                        if ($row['edit'] == 'none')
                            return 'none';
                        if ($row['edit'] == 'category')
                            return 'category';
                        return $row['edit'];
                    }
                }
            }
            return 'none';
        }

        public static function userId()
        {
            if (!empty($_SESSION['__acl']['user']['id']))
                return $_SESSION['__acl']['user']['id'];
            return NULL;
        }

        public function hasRights($controller, $action, $itemId = '')
        {

            if ($action == 'index') {
                return Acl::userGrant($controller . '_list');
            } else if (strpos($action, 'edit') !== false) {
                return Acl::userGrant($controller . '_edit');
            } else if ($action == 'add') {
                return Acl::userGrant($controller . '_add');
            } else if ($action == 'delete') {
                return Acl::userGrant($controller . '_delete');
            } else if ($action == 'delete_list') {
                return Acl::userGrant($controller . '_delete_list');
            } else if ($action == 'save') {
                return Acl::userGrant($controller . '_save');
            } else if ($action == 'xls') {
                return Acl::userGrant($controller . '_xls');
            }
            return FALSE;
        }

        public function canViewMenuItem($url, $userId = 0)
        {
            $url = preg_replace('/admin\/?/ims', '', $url);
            if (empty($url) || $url == 'index' || $url == 'index/' || $url == 'index/index' || $url == 'index/index/')
                return TRUE;

            if (!empty($userId))
                $this->userRights = $this->getRights($userId);

            if ($this->isSuperAdmin($userId))
                return TRUE;

            if (preg_match('#^([\w\_\-]*?)/?([\w\_\-]*?)(/?)(\??)(id=)?([\d]*?)?$#', $url, $match)) {
                if (!empty($match[1])) {
                    $controller = $match[0];
                }
                if (!empty($match[2])) {
                    $action = $match[2];
                } else {
                    $action = 'index';
                }

                if ($action == 'stat') {
                    $action = 'view';
                }

                if ($action == 'delete')
                    $action = 'edit';

                if (!empty($match[6])) {
                    $itemId = $match[6];
                } else {
                    $itemId = NULL;
                }

                return $this->hasRights($controller, $action, $itemId);

            }
            return FALSE;
        }


        public function login($login, $password, $hashKey = '')
        {

            $sql = "SELECT ur.*, u.*
			FROM " . $this->userTable . " u, " . $this->userRoleTable . " ur
			WHERE
				u.login='$login'
				and u.id = ur.id ";
            if ($hashKey) {
                $sql .= " AND u.hash_key='" . $hashKey . "'";
            }
            $sql .= " AND u.password='" . $password . "'";
            $user = $this->db->get($sql);
            return $user;
        }

        public function isSuperAdmin($userId = NULL)
        {
            if (!empty($userId)) {
                $sql = "SELECT * FROM " . $this->userTable . " WHERE id='" . $userId . "'";
                $user = $this->db->get($sql);
                if (empty($user))
                    return FALSE;
                if ($user['is_super'] == 1 || $user['is_super'] == 2)
                    return TRUE;
                return FALSE;
            } else {
                return $this->isSuper;
            }
        }

        public static function hasAccessToAdminPanel()
        {
            $admin_access_roles = array(
                RoleModel::ACCOUNT_ADMIN,
                RoleModel::CALL_CENTRE_OPERATOR,
                RoleModel::ESHOP_MANAGER,
				RoleModel::ESHOP_CONTENT_MANAGER,
            );

            return in_array(Acl::userRole(), $admin_access_roles);
        }

        public static function isAuthed($type = RoleModel::ACCOUNT_ADMIN)
        {
            switch($type){
				case RoleModel::ACCOUNT_ADMIN:
                    if (!empty($_SESSION['__acl']['user']) && !empty($_SESSION['__acl']['user_type']) && $_SESSION['__acl']['user_type'] == RoleModel::ACCOUNT_ADMIN)
                        return TRUE;
                    return FALSE;
                    break;
                case RoleModel::ACCOUNT_SUPER_MANAGER:
                    if (!empty($_SESSION['__acl']['user']) && !empty($_SESSION['__acl']['user_type']) && $_SESSION['__acl']['user_type'] == RoleModel::ACCOUNT_SUPER_MANAGER)
                        return TRUE;
                    return FALSE;
                    break;
				case RoleModel::ACCOUNT_MANAGER:
					if (!empty($_SESSION['__acl']['user']) && !empty($_SESSION['__acl']['user_type']) && $_SESSION['__acl']['user_type'] == RoleModel::ACCOUNT_MANAGER)
						return TRUE;
					return FALSE;
					break;
				case RoleModel::FREELANCE_MANAGER:
					if (!empty($_SESSION['__acl']['user']) && !empty($_SESSION['__acl']['user_type']) && $_SESSION['__acl']['user_type'] == RoleModel::FREELANCE_MANAGER)
						return TRUE;
					return FALSE;
					break;
                case RoleModel::ACCOUNT_REGISTRY:
                    if (!empty($_SESSION['__acl']['user']) && !empty($_SESSION['__acl']['user_type']) && $_SESSION['__acl']['user_type'] == RoleModel::ACCOUNT_REGISTRY)
                        return TRUE;
                    return FALSE;
                    break;
            }
        }



        public static function getAuthedUserId()
        {
            if (!empty($_SESSION['__acl']['user']['id']))
                return $_SESSION['__acl']['user']['id'];
            return NULL;
        }

        public static function saveAuthData($user, array $user_grants)
        {
            $result = array();
            if ($user_grants) {
                foreach ($user_grants as $grant) {
                    $controller = ModelManagerFactory::getByName('controller')->getOneById($grant->controller_id);
                    if (count($controller)) {
                        $result['grant_' . $controller->code . '_list'] = $grant->list;
                        $result['grant_' . $controller->code . '_add'] = $grant->add;
                        $result['grant_' . $controller->code . '_edit'] = $grant->edit;
                        $result['grant_' . $controller->code . '_save'] = $grant->save;
                        $result['grant_' . $controller->code . '_delete'] = $grant->delete;
                        $result['grant_' . $controller->code . '_delete_list'] = $grant->delete_list;
                    }
                }
            }

            $_SESSION['__acl']['user'] = $result;
            $_SESSION['__acl']['user']['id'] = $user->id;
            $_SESSION['__acl']['user_type'] = $user->role_id;
        }

        public static function logout()
        {
            unset($_SESSION['__acl']['user']);
            unset($_SESSION['__acl']['user_type']);
        }

    }