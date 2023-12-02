<?php
    class RegistryAccessHelper
    {
        public static function checkAuth()
        {
            if (!Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
                && !Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY)
                && !Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
                && !Acl::isAuthed(RoleModel::FREELANCE_MANAGER)
            ) {
                RedirectManager::redirect(ADMIN_FOLDER);
            }

            return TRUE;
        }

        public static function checkManagerAuth($redirect = true)
        {
            return true;

            if (!Acl::isAuthed(RoleModel::ACCOUNT_MANAGER)
                && !Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)
                && !Acl::isAuthed(RoleModel::FREELANCE_MANAGER)
            ) {
                if ($redirect)
                    RedirectManager::redirect(ADMIN_FOLDER);
                else
                    return false;
            }

            return TRUE;
        }

        public static function determineClinicIdByDoctorId($doctor_id)
        {
            $doctor_manager = new DoctorManager();
            $doctor = $doctor_manager->getOneById($doctor_id);

            if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY))
                return ClinicUserHelper::getClinicIdByUserId(Acl::userId());

            if (Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)) {
                return $doctor->clinics_for_all[0]->getId();
            }

            if (Acl::isAuthed(RoleModel::ACCOUNT_MANAGER) || Acl::isAuthed(RoleModel::FREELANCE_MANAGER)) {
                $clinic_manager = new ClinicManager();
                $clinic = $clinic_manager->getOneFirstByUserIdAndDoctorId(Acl::userId(), $doctor_id);

                return $clinic->getId();
            }

            return null;
        }

        public static function checkAccessAndDetermineClinicId($redirect_when_not_has_access = true)
        {
            self::checkAuth();

            $clinic_id = NULL;

            if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY)) {
                $clinic_manager = new ClinicManager();
                $clinic = $clinic_manager->getOneByRegistryUserId(Acl::userId());
                $clinic_id = $clinic->getId();
            } else {
                $clinic_id = isset($_REQUEST['clinic_id']) ? $_REQUEST['clinic_id'] : NULL;
            }

            if (self::checkAccessToClinic($clinic_id))
                return $clinic_id;
            else {
                if ($redirect_when_not_has_access) {
                    RedirectManager::redirect(ADMIN_FOLDER);
                } else {
                    return FALSE;
                }
            }

            return FALSE;
        }

		public static function checkAccessToClinic($clinic_id, $additional_access = false)
        {
            self::checkAuth();

			if (Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER) || $additional_access)
                return TRUE;

            if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY)) {

                $clinic_manager = new ClinicManager();
                $registry_clinic = $clinic_manager->getOneByRegistryUserId(Acl::userId());

                if ($registry_clinic && $registry_clinic->getId() == $clinic_id) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }

            if (Acl::isAuthed(RoleModel::ACCOUNT_MANAGER) || Acl::isAuthed(RoleModel::FREELANCE_MANAGER)) {
                $clinic_manager = new ClinicManager();
                $manager_clinics = $clinic_manager->getListByManagerUserId(Acl::userId());

                foreach ($manager_clinics as $manager_clinic) {
                    if ($manager_clinic->getId() == $clinic_id) {
                        return TRUE;
                    }
                }
            }

            return FALSE;
        }

        public static function checkAccessToDoctor($doctor_id, $redirect = false)
        {
            self::checkAuth();

            if (Acl::isAuthed(RoleModel::ACCOUNT_SUPER_MANAGER)) {
                return TRUE;
            } else {
                $registry_access_manager = new RegistryAccessManager();
                if ($registry_access_manager->checkAccessToDoctorByManagerUserIdAndDoctorId(Acl::userId(), $doctor_id)) {
                    return TRUE;
                } else {
                    if ($redirect) {
                        RedirectManager::redirect(ADMIN_FOLDER);
                    } else {
                        return FALSE;
                    }
                }
            }

            return FALSE;
        }

        public static function hasAccessToEdit($moderate_status_id)
        {
            if (Acl::isAuthed(RoleModel::ACCOUNT_REGISTRY)) {
                if ($moderate_status_id == ModerateStatusModel::MODERATE) {
                    return false;
                } else {
                    return true;
                }
            }

            return true;
        }

		public static function checkAccessToUser($user_id, $additional_access = false)
        {
			if (Acl::userRole() == RoleModel::ACCOUNT_SUPER_MANAGER || $additional_access)
                return TRUE;

            $registry_access_manager = new RegistryAccessManager();
            return $registry_access_manager->checkAccessToUserByUserIdAndManagerUserId($user_id, Acl::userId());
        }

        public static function getAdditionalAccessToAccountManager($user_id)
        {
            if ($user_id == Acl::userId() && Acl::userRole() == RoleModel::ACCOUNT_MANAGER)
                return true;
            else
                return false;
        }

        public static function checkSuperManagerAccess()
        {
            if (Acl::userRole() == RoleModel::ACCOUNT_SUPER_MANAGER)
                return true;
            else
                return false;
        }
    }