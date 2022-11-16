<?php
    class SpecialtyToClinicLicenseManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var SpecialtyToClinicLicenseManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new SpecialtyToClinicLicenseManager();
        }

        /**
         * @covers SpecialtyToClinicLicenseManager::getListBySpecialtyId
         */
        function testGetListBySpecialtyId()
        {

            $specialties = ModelManagerFactory::getByName('specialty')->getListWithLimit(10);

            foreach ($specialties as $specialty) {
                $specialty_to_clinic_licenses = $this->object->getListBySpecialtyId($specialty->getId());
                $this->assertTrue(is_array($specialty_to_clinic_licenses));

                if ($specialty_to_clinic_licenses)
                    foreach ($specialty_to_clinic_licenses as $specialty_to_clinic_license) {
                        $this->assertTrue(is_object($specialty_to_clinic_license));
                        $this->assertEquals($specialty_to_clinic_license->specialty_id, $specialty->getId());
                    }
            }
        }

        /**
         * @covers SpecialtyToClinicLicenseManager::getListByClinicLicenseId
         */
        function testGetListByClinicLicenseId()
        {

            $clinic_licenses = ModelManagerFactory::getByName('clinic_license')->getListWithLimit(10);

            foreach ($clinic_licenses as $clinic_license) {
                $specialty_to_clinic_licenses = $this->object->getListByClinicLicenseId($clinic_license->getId());
                $this->assertTrue(is_array($specialty_to_clinic_licenses));

                if ($specialty_to_clinic_licenses)
                    foreach ($specialty_to_clinic_licenses as $specialty_to_clinic_license) {
                        $this->assertTrue(is_object($specialty_to_clinic_license));
                        $this->assertEquals($specialty_to_clinic_license->clinic_license_id, $clinic_license->getId());
                    }
            }
        }

    }
