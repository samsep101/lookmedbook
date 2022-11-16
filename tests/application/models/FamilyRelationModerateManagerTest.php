<?php
    class FamilyRelationModerateManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var FamilyRelationModerateManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new FamilyRelationModerateManager();
        }

        /**
         * @covers FamilyRelationModerateManager::getListByAccountId
         */
        function testGetListByAccountId()
        {

            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

            $this->object->clearRegister();

            foreach ($accounts as $account) {
                $family_relation_moderates = $this->object->getListByAccountId($account->getId());
                $this->assertTrue(is_array($family_relation_moderates));

                if ($family_relation_moderates)
                    foreach ($family_relation_moderates as $family_relation_moderate) {
                        $this->assertTrue(is_object($family_relation_moderate));
                        $this->assertEquals($family_relation_moderate->account_id, $account->getId());
                    }
            }
        }

        /**
         * @covers FamilyRelationModerateManager::checkExistsByAccountIdAndToAccountIdAndIsConfirmed
         */
        function testCheckExistsByAccountIdAndToAccountIdAndIsConfirmed()
        {
            $family_ralations = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            foreach ($family_ralations as $family_ralation)
            {
                $test_object = $this->object->checkExistsByAccountIdAndToAccountIdAndIsConfirmed($family_ralation->account_id, $family_ralation->to_account_id);

                if($test_object)
                    $this->assertTrue($test_object);
            }
        }


    }
