<?php
    class SitemapGenerator
    {
        private $SITEMAP_NS = 'http://www.sitemaps.org/schemas/sitemap/0.9';
        private $IMAGE_SITEMAP_NS = 'http://www.google.com/schemas/sitemap-images/0.9';
        private $SITEMAP_NS_XSD = 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd';

        private $max_count_links = 50000;
        private $filename = 'sitemap';

        public function generate(array $links)
        {
            if (count($links) < $this->max_count_links)
            {
                $map = $this->newDOMDocument();
                $url_set = $this->getUrlSetNode($map);
                foreach ($links as $link){
                    $this->getUrlNode($map, $url_set, $link);
                }

                $xml = $map->saveXML();
                file_put_contents(PUBLIC_ROOT . '/' . $this->filename.'.xml', $xml);

            } else {
                $links = array_chunk($links, $this->max_count_links);

                for ($i = 0; $i < count($links); $i++){
                    $map = $this->newDOMDocument();
                    $url_set = $this->getUrlSetNode($map);
                    foreach($links[$i] as $link){
                        $this->getUrlNode($map, $url_set, $link);
                    }

                    $xml = $map->saveXML();

					$gz = gzopen(PUBLIC_ROOT . '/' . $this->filename.$i.'.xml.gz', 'w9');
					gzwrite($gz, $xml);
					fclose($gz);
                }

				$base_node = $this->newDOMDocument();
				$sitemap_index_node = $this->getSitemapIndexNode($base_node);
				for ($i = 0; $i < count($links); $i++)
				{
					$this->getSitemapNode($base_node, $sitemap_index_node, SITE_URL.'/sitemap'.$i.'.xml.gz');
				}

				$xml = $base_node->saveXML();
				file_put_contents(PUBLIC_ROOT . '/sitemap.xml', $xml);
            }
        }

        public function generateSiteMapFile($fileName, array $links)
        {
            $sitePath = PUBLIC_ROOT;
            $sitemapsPath = 'sitemaps';

            if(!file_exists($sitePath . '/' . $sitemapsPath)) {
                FileHelper::createFolder($sitemapsPath);
            }

            $filePath = $sitePath . '/' . $sitemapsPath . '/' . $fileName . '.xml';

            if (count($links) < $this->max_count_links)
            {

                $map = $this->newDOMDocument();
                $url_set = $this->getUrlSetNode($map);
                foreach ($links as $link){
                    $this->getUrlNode($map, $url_set, $link);
                }

                $xml = $map->saveXML();
                file_put_contents($filePath, $xml);
            } else {

                $links = array_chunk($links, $this->max_count_links);

                for ($i = 0; $i < count($links); $i++){
                    $gzFilePath = $sitePath . '/' . $sitemapsPath . '/' . $fileName.$i.'.xml.gz';

                    $map = $this->newDOMDocument();
                    $url_set = $this->getUrlSetNode($map);
                    foreach($links[$i] as $link){
                        $this->getUrlNode($map, $url_set, $link);
                    }

                    $xml = $map->saveXML();

					$gz = gzopen($gzFilePath, 'w9');
					gzwrite($gz, $xml);
					fclose($gz);
                }

				$base_node = $this->newDOMDocument();
				$sitemap_index_node = $this->getSitemapIndexNode($base_node);
				for ($i = 0; $i < count($links); $i++)
				{
					$this->getSitemapNode($base_node, $sitemap_index_node, SITE_URL . '/' . $sitemapsPath . '/' . $fileName.$i.'.xml.gz');
				}

				$xml = $base_node->saveXML();
				file_put_contents($filePath, $xml);
            }
        }

        public function generateImageSiteMap(array $links)
        {
            $xml  = '<?xml version="1.0" encoding="UTF-8"?>';
            $xml .= '<urlset xmlns="' . $this->IMAGE_SITEMAP_NS . '" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
            foreach($links as $link) {
                $xml .=     '<url>';
                $xml .=         '<loc>' . $link['location'] . '</loc>';

                foreach($link['images'] AS $iValue) {
                    $xml .=         '<image:image>';
                    $xml .=             '<image:loc>' . $iValue . '</image:loc>';
                    $xml .=         '</image:image>';
                }

                $xml .=     '</url>';

            }

            $xml .= '</urlset>';

            file_put_contents(PUBLIC_ROOT . '/image_sitemap.xml', $xml);

        }

		public function getSitemapIndexNode(DOMDocument $base_node)
		{
			$sitemap_index = $base_node->createElementNS($this->SITEMAP_NS, 'sitemapindex');
			$base_node->appendChild($sitemap_index);

			return $sitemap_index;
		}

		public function getSitemapNode(DOMDocument $base_node, DOMElement $sitemap_index_node, $sitemap_link)
		{
			$sitemap_node = $base_node->createElement('sitemap');
			$sitemap_index_node->appendChild($sitemap_node);

			$sitemap_node->appendChild($base_node->createElement('loc', $sitemap_link));
			$sitemap_node->appendChild($base_node->createElement('lastmod', date(DATE_ATOM)));
		}

        public function getUrlSetNode(DOMDocument $map){
            $url_set = $map->createElementNS($this->SITEMAP_NS, 'urlset');
            $map->appendChild($url_set);
            $url_set->setAttributeNS('http://www.w3.org/2000/xmlns/' ,
                'xmlns:xsi',
                'http://www.w3.org/2001/XMLSchema-instance');
            $url_set->setAttributeNS('http://www.w3.org/2001/XMLSchema-instance',
                'schemaLocation',
                $this->SITEMAP_NS . ' ' . $this->SITEMAP_NS_XSD);
            return $url_set;
        }

        public function getUrlNode(DOMDocument $map, DOMElement $url_set, SitemapLink $link){
            $url_node = $map->createElementNS($this->SITEMAP_NS, 'url');
            $url_set->appendChild($url_node);
            $url_node->appendChild($map->createElementNS($this->SITEMAP_NS, 'loc', $link->url));
            $url_node->appendChild($map->createElementNS($this->SITEMAP_NS, 'changefreq', $link->changefreq));
            $url_node->appendChild($map->createElementNS($this->SITEMAP_NS, 'priority', $link->priority));
            $url_node->appendChild($map->createElementNS($this->SITEMAP_NS, 'lastmod', SitemapLink::getLastmod(PUBLIC_ROOT . '/' . $this->filename.'.xml')));
        }

        public function newDOMDocument(){
            $map = new DOMDocument('1.0', 'UTF-8');
            $map->formatOutput = true;
            return $map;
        }

        public function generateClinicsSitemap()
        {
            $link = new SitemapLink();
            $link->url = SITE_URL.'/clinic';
            $links[] = $link;

            $clinicManager = new ClinicManager();
            $clinics = $clinicManager->getActiveList();

            if ($clinics) {
                foreach ($clinics as $clinic) {
                    $link = new SitemapLink();
                    $link->url = ClinicPageLinkViewHelper::getLink($clinic);
                    if ($link->url) {
                        $links[] = $link;
                    }
                }
            }
            unset($clinics);

            $cityManager = new CityManager();
            $specManager = new SpecializationManager();

            $cities = $cityManager->getActiveList();

            $districtManager = new DistrictManager();
            $regionManager = new RegionManager();
            $metroStationManager = new MetroStationManager();

            foreach($cities as $city)
            {
                if ($this->isValidCity($city)) {
                    $citySpecialties = $specManager->getSpecializationForCityIDInWhichHaveDoctors($city->getId());

                    if(count($citySpecialties) > 0)
                    {
                        foreach($citySpecialties as $citySpecialty)
                        {
                            $link = new SitemapLink();
                            $link->url = SeoLinkViewHelper::getSpecialtyPageLink($citySpecialty, $city, 'clinic');
                            $links[] = $link;

                            $districts = $districtManager->getHavingClinicListBySpecializationIdAndCityId($citySpecialty->getId(), $city->getId());
                            foreach($districts as $district)
                            {
                                $link = new SitemapLink();
                                $link->url = SeoLinkViewHelper::getSpecialtyPageLink($citySpecialty, $district, 'clinic');
                                $links[] = $link;

                                $regions = $regionManager->getHavingClinicListBySpecializationIdAndDistrictId($citySpecialty->getId(), $district->getId());
                                foreach($regions as $region)
                                {
                                    $link = new SitemapLink();
                                    $link->url = SeoLinkViewHelper::getSpecialtyPageLink($citySpecialty, $region, 'clinic');
                                    $links[] = $link;

                                    $metroStations = $metroStationManager->getHavingClinicListBySpecializationIdAndRegionId($citySpecialty->getId(), $region->getId());
                                    foreach($metroStations as $metroStation)
                                    {
                                        $link = new SitemapLink();
                                        $link->url = SeoLinkViewHelper::getSpecialtyPageLink($citySpecialty, $metroStation, 'clinic');
                                        $links[] = $link;
                                    }
                                }
                            }
                        }
                        unset($citySpecialties);
                    }
                }
            }

            $this->generateSiteMapFile('common-clinics', $links);
        }

        public function generateDoctorsSitemap()
        {
            $link = new SitemapLink();
            $link->url = SITE_URL.'/doctor';
            $links[] = $link;

            $doctor_manager = new DoctorManager();
            $doctors = $doctor_manager->getActiveList();

            if ($doctors) {
                foreach ($doctors as $doctor) {
                    $link = new SitemapLink();
                    $link->url = DoctorPageLinkViewHelper::getLink($doctor);
                    if ($link->url) {
                        $links[] = $link;
                    }
                }
            }
            unset($doctors);

            $cityManager = new CityManager();
            $specialtyManager = new SpecialtyManager();

            $cities = $cityManager->getActiveList();

            $districtManager = new DistrictManager();
            $regionManager = new RegionManager();
            $metroStationManager = new MetroStationManager();

            foreach($cities as $city)
            {
                if ($this->isValidCity($city)) {
                    $citySpecialties = $specialtyManager->getHavingDoctorsListByAddressObject($city);

                    if(count($citySpecialties) > 0)
                    {
                        foreach($citySpecialties as $citySpecialty)
                        {
                            $link = new SitemapLink();
                            $link->url = SeoLinkViewHelper::getSpecialtyPageLink($citySpecialty, $city);
                            $links[] = $link;

                            $districts = $districtManager->getHavingDoctorsListBySpecialtyIdAndCityId($citySpecialty->getId(), $city->getId());
                            foreach($districts as $district)
                            {
                                $link = new SitemapLink();
                                $link->url = SeoLinkViewHelper::getSpecialtyPageLink($citySpecialty, $district);
                                $links[] = $link;

                                $regions = $regionManager->getHavingDoctorsListBySpecialtyIdAndDistrictId($citySpecialty->getId(), $district->getId());
                                foreach($regions as $region)
                                {
                                    $link = new SitemapLink();
                                    $link->url = SeoLinkViewHelper::getSpecialtyPageLink($citySpecialty, $region);
                                    $links[] = $link;

                                    $metroStations = $metroStationManager->getHavingDoctorsListBySpecialtyIdAndRegionId($citySpecialty->getId(), $region->getId());
                                    foreach($metroStations as $metroStation)
                                    {
                                        $link = new SitemapLink();
                                        $link->url = SeoLinkViewHelper::getSpecialtyPageLink($citySpecialty, $metroStation);
                                        $links[] = $link;
                                    }
                                }
                            }
                        }
                        unset($citySpecialties);
                    }
                }
            }

            $this->generateSiteMapFile('common-doctors', $links);
        }

        public function generateServicesSitemap()
        {
            $links = [];

            $link = new SitemapLink();
            $link->url = SITE_URL.'/uslugi';
            $links[] = $link;

            require_once ABS_ROOT.'/application/models/service.category.simplemodel.php';
            $model = new ServiceCategorySimpleModel();
            $model->setCityID(1);
            $tree = $model->getTree();
            $this->processServicesTree($tree, $links);

            $this->generateSiteMapFile('common-services', $links);
        }

        public function generateDiseasesSitemap()
        {
            $links = [];

            $link = new SitemapLink();
            $link->url = SITE_URL.'/disease';
            $links[] = $link;

            $manager = new DiseaseManager();
            $allDiseases = $manager->getActiveList();
            foreach ($allDiseases as $disease) {
                $link = new SitemapLink();
                $link->url = SITE_URL."/disease/{$disease->alias}";
                $links[] = $link;
            }

            $this->generateSiteMapFile('common-diseases', $links);
        }

        public function generateCommonSitemap()
        {
            $links = [];

            $link = new SitemapLink();
            $link->url = SITE_URL;
            $link->changefreq = 'daily';
            $link->priority = '1.00';
            $links[] = $link;

            $link = new SitemapLink();
            $link->url = SITE_URL . '/help';
            $links[] = $link;

            $link = new SitemapLink();
            $link->url = SITE_URL . '/about';
            $links[] = $link;

            $this->generateSiteMapFile('common-static', $links);
        }

        public function generateIndex($name = 'sitemap')
        {
            $sitemapFiles = [
                'common-static',
                'common-doctors',
                'common-clinics',
                'common-services',
                'common-diseases',
            ];

            $baseNode = $this->newDOMDocument();
            $sitemapIndexNode = $this->getSitemapIndexNode($baseNode);
            foreach ($sitemapFiles as $file)
            {
                $this->getSitemapNode($baseNode, $sitemapIndexNode, SITE_URL."/sitemaps/$file.xml");
            }

            $xml = $baseNode->saveXML();
            file_put_contents(PUBLIC_ROOT . "/$name.xml", $xml);
        }

        /**
         * @param CityModel $city
         *
         * @return bool
         * @throws Exception
         */
        protected function isValidCity(CityModel $city)
        {
            return $city->name === 'Москва' || in_array($city->alias, Register::get('SUBDOMAINS'));
        }

        private function processServicesTree(array $tree, array &$links)
        {
            foreach ($tree as $branch) {
                $slug = !empty($branch['full_slug']) ? $branch['full_slug'] : $branch['alias'];
                $link = new SitemapLink();
                $link->url = SITE_URL.'/uslugi/'.$slug;
                $links[] = $link;
                if (!empty($branch['subslugs'])) {
                    $this->processServicesTree($branch['subslugs'], $links);
                }
            }
        }
    }
