<?php
    class FamilyRelationManagerTest extends PHPUnit_Framework_TestCase
    {

        /**
         * @var FamilyRelationManager
         */
        protected $object;

        protected function setUp()
        {
            $this->object = new FamilyRelationManager();
        }

        /**
         * @covers FamilyRelationManager::getConfirmedListByAccountId
         */
        function testGetConfirmedListByAccountId()
        {
            $accounts = ModelManagerFactory::getByName('account')->getListWithLimit(10);

            $this->object->clearRegister();

            foreach ($accounts as $account) {
                $family_relations = $this->object->getConfirmedListByAccountId($account->getId());
                $this->assertTrue(is_array($family_relations));

                if ($family_relations)
                    foreach ($family_relations as $family_relation) {
                        $this->assertTrue(is_object($family_relation));
                        $this->assertEquals($family_relation->account_id, $account->getId());
                    }
            }
        }


        /**
         * @covers FamilyRelationManager::checkExistsByAccount1IdAndAccount2IdAndIsConfirmed
         */
        function testCheckExistsByAccount1IdAndAccount2IdAndIsConfirmed()
        {
            $family_relations = $this->object->getListWithLimit(20);

            $this->object->clearRegister();

            if ($family_relations)
                foreach ($family_relations as $family_relation)
                {
                    $test_object = $this->object->checkExistsByDiseaseIdAndDiseaseTagId($family_relation->account1_id, $family_relation->account2_id);

                    if ($test_object)
                        $this->assertTrue($test_object);
                }
        }


    }
