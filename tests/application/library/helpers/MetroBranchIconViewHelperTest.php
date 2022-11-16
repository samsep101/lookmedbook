<?php
class MetroBranchIconViewHelperTest extends PHPUnit_Framework_TestCase {

    /**
     * @covers MetroBranchIconViewHelper::getImage
     */
    function testGetImage(){

        $metro_branches = ModelManagerFactory::getByName('metro_branch')->getList();
        $this->assertTrue(is_array($metro_branches));
        $test_branch = new MetroBranchIconViewHelper();

        foreach ($metro_branches as $branch) {
            $image_string = $test_branch->getImage($branch);
            $this->assertTrue((bool)$image_string);
        }
    }
}
