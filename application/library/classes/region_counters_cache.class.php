<?php

	class RegionCountersCache extends BaseController
	{
		public static function getRegionsCountersCache($registry_user_id, $freelancer_id, $city_id = null, $name = '')
		{
			$clinic_search_params = new ClinicSearchParams();
			$clinic_search_params->registry_user_id = $registry_user_id;
			$clinic_search_params->is_region = 1;
			$clinic_search_params->freelancer_id = $freelancer_id;
			$clinic_search_params->city_id = $city_id;

			$clinic_search_params->clinic_name = $name;

			$cache_name = 'region_counters_cache' . $clinic_search_params->registry_user_id . 'freelancer=' . $freelancer_id . 'city=' . $city_id;

			if(!$clinic_search_params->clinic_name)
			{
				$cached_data = MemcacheAdapter::get($cache_name);
			}
			else
			{
				$cached_data = array();
			}

			$status_id = $clinic_search_params->status;

			Environment::set('get_total_count', 1);

			if(!$cached_data)
			{
				$clinic_manager = new ClinicManager();

				$clinic_search_params->status = null;
				$cached_data['regions'] = $clinic_manager->getCountByModelSearchCriteria($clinic_search_params);

				$clinic_search_params->status = ClinicStatusModel::PUBLISHED;
				$cached_data['region_published'] = $clinic_manager->getCountByModelSearchCriteria($clinic_search_params);

				$clinic_search_params->status = ClinicStatusModel::RAW;
				$cached_data['region_raw'] = $clinic_manager->getCountByModelSearchCriteria($clinic_search_params);

				$clinic_search_params->status = ClinicStatusModel::PROBLEM;
				$cached_data['region_problem'] = $clinic_manager->getCountByModelSearchCriteria($clinic_search_params);

				if(!$clinic_search_params->clinic_name)
				{
					MemcacheAdapter::set($cache_name, $cached_data);
				}
			}

			$clinic_search_params->status = $status_id;


			return $cached_data;
		}
	}