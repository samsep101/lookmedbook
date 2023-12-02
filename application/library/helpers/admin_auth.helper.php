<?php
    class AdminAuthHelper {
        public static function redirectToDefaultPage()
        {
            if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY))
            {
                RedirectManager::redirect(REGISTRY_FOLDER);
            }

            if (Acl::isAuthed(RoleModel::ACCOUNT_ADMIN))
            {
                RedirectManager::redirect(ADMIN_FOLDER);
            }

            if (Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
				|| Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
				|| Acl::isAuthed(RoleModel::FREELANCE_MANAGER)
			)
            {
                RedirectManager::redirect(MANAGE_FOLDER);
            }
        }

		public static function checkRegistryAuth()
		{
			if (!Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY))
			{
				RedirectManager::redirect('/admin');
			}
		}
    }