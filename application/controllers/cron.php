<?php
	class CronController extends SecureController
	{
		public $layout = '';

		/**
		 * Запускать раз в 2 часа
		 */
		public function updateDoctorsRate()
		{
			SiteTaskManager::updateDoctorsRate();
			exit();
		}

		/**
		 * Запускать раз в 2 часа
		 */
		public function updateClinicsRate()
		{
			SiteTaskManager::updateClinicsRate();
			exit();
		}

		/**
		 * Раз в час
		 */
		public function setDoctorsAvailability()
		{
			SiteTaskManager::setDoctorsAvailability();
			exit();
		}

		public function createDistributionsTasks()
		{
			SiteTaskManager::createDistributionsTasks();
			exit();
		}

		public function processDistributionsTasks()
		{
			SiteTaskManager::processDistributionsTasks();
			exit();
		}

		function processVisits()
		{
			SiteTaskManager::processVisits();
			exit();
		}

		/**
		 * Раз в день
		 */
		function replaceVisits()
		{
			SiteTaskManager::replaceVisits();
			exit();
		}

		function sendEmailForLate()
		{
			SiteTaskManager::sendEmailForLate();
			exit();
		}

		public function createVirtualDoctors()
		{
			SiteTaskManager::createVirtualDoctors();
			exit();
		}


		/**
		 * Раз в 2 минуты
		 */
		public function resetReservedStatus()
		{
			$schedule_manager = new ScheduleManager();
			$schedule_manager->resetReservedStatusByMinutes(15);
			exit();
		}


		public function updateEmailSubscribes()
		{
			set_time_limit(0);
			SiteTaskManager::updateSubscribes();
			exit();
		}

		public function processGeoIpData()
		{
			SiteTaskManager::processGeoIpData();
			exit();
		}

		public function processSpecialtiesToClinic()
		{
			SiteTaskManager::processSpecialtiesToClinic();
			exit();
		}

		public function saveSocialNetworkInfo()
		{
			SiteTaskManager::saveSocialNetworkInfo();
			exit();
		}

		public function synchSubscribes()
		{
			SiteTaskManager::synchSubscribes();
			exit();
		}

		public function checkClinicsInCities()
		{
			SiteTaskManager::checkClinicsInCities();
			exit();
		}

		public function fillDoctorsVisitsSlots()
		{
			SiteTaskManager::fillDoctorsVisitsSlots();
			exit();
		}

		public function proccessSendVisitMail()
		{
			SiteTaskManager::processSendVisitMail();
			exit();
		}

		public function calculateDoctorBalls()
		{
			SiteTaskManager::calculateDoctorBalls();
			exit();
		}

		public function calculateClinicBalls()
		{
			ModelManager::disableEntityMapGlobal();
			SiteTaskManager::calculateClinicBalls();
			exit();
		}

		public function generateSitemap()
		{
            SiteTaskManager::generateSitemap();
			exit();
		}

		public function generateStructuredSitemap()
		{
		    SiteTaskManager::generateStructuredSitemap();
			exit();
		}

		public function generateSitemapForCities()
		{
            SiteTaskManager::generateSiteMapsForCities();
			exit();
		}

		public function generateImageSitemap()
		{
            SiteTaskManager::generateImageSitemap();
			exit();
		}

		public function generateFileCache()
		{
			SiteTaskManager::generateFileCache();
			exit();
		}

		public function parseDiseasesAndDiseaseBlocks()
		{
			ini_set("memory_limit", "128M");
			SiteTaskManager::parseDiseasesAndDiseaseBlocks();
			exit();
		}

		public function setActualPurposesOfVisit()
		{
			SiteTaskManager::setDoctorsPurposesOfVisit();
			exit();
		}

		public function generateDiseaseDescriptions()
		{
			SiteTaskManager::generateDiseaseDescriptions();
			exit();
		}

		public function alertAdmin()
		{
			$system_monitor = new SystemMonitor();
			$free_disk_space = $system_monitor->getFreeDiskSpacePercent();

			if($free_disk_space < 20)
			{
				mail('d.karviga@gmail.com', 'Заканчивается место на хостинге '.SITE_NAME.'', 'Заканчивается место на хостинге');
			}
			exit();
		}

		/**
		 * запустим после появления временных зон
		 */
		public function sendVisitNotifications()
		{
			SiteTaskManager::sendVisitNotifications();
			exit();
		}

		/**
		 * Запускать каждую минуту
		 */
		public function sendVisitPushNotifications()
		{
			SiteTaskManager::sendVisitPushNotifications();
			exit();
		}

		public function fillRandomSortFields()
		{
			SiteTaskManager::fillRandomSortFields();
			exit();
		}

        public function clearDeletedDoctorBindings()
        {
            SiteTaskManager::clearDeletedDoctorBindings();
            exit();
        }

        public function checkLaboratoriesInCities()
        {
            SiteTaskManager::checkLaboratoriesInCities();
            exit();
        }

        public function generateEqualClinicsAndDoctors()
        {
            SiteTaskManager::createEqualClinicsAndDoctors();
            exit();
        }

        public function updateClinicMetroStationId()
        {
            SiteTaskManager::updateClinicMetroStationId();
            exit();
        }

        /**
         * Генерация robots.txt для поддоменов
         */
        public function generateRobots()
        {
            $generatedConfigPath = PUBLIC_ROOT . "/robots/";
            $template = file_get_contents(PUBLIC_ROOT . "/robots/template.txt");

            foreach (Register::get('SUBDOMAINS') as $subdmomain) {
                $r = strtr($template, [
                    '{{domain}}' => "$subdmomain." . SITE_DOMAIN
                ]);
                file_put_contents("{$generatedConfigPath}robots_{$subdmomain}.txt", $r);
            }
            exit();
        }
	}
