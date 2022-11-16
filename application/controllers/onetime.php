<?php
class OnetimeController extends SecureController
{
    public $layout = '';

    public function actualizeDiseaseDraft()
    {
        global $argc, $argv;
        if ($argc < 3) {
            die("id is required\n");
        }
        $id = $argv[2];
        echo "STARTING...\n";
        /** @var DiseaseManager $manager */
        $manager = ModelManagerFactory::getByName('disease');

        /** @var DiseaseDraftManager $draftManager */
        $draftManager = ModelManagerFactory::getByName('disease_draft');

        /** @var DiseaseModel $disease */
        $disease = $manager->getOneById($id);
        if ($disease) {
            $draftManager->saveOrUpdateRelatedDraftByDisease($disease);
        } else {
            echo "NOT FOUND\n";
        }
        echo "DONE\n";
    }

    public function actualizeDiseaseDrafts()
    {
        echo "STARTING...\n";
        /** @var DiseaseManager $manager */
        $manager = ModelManagerFactory::getByName('disease');

        /** @var DiseaseDraftManager $draftManager */
        $draftManager = ModelManagerFactory::getByName('disease_draft');

        /** @var DiseaseModel[] $list */
        $list = $manager->getList();
        $i = 0;
        $total = count($list);

        foreach ($list as $disease) {
            $draftManager->saveOrUpdateRelatedDraftByDisease($disease);
            $i++;
            echo "$i/$total\n";
        }
        echo "DONE\n";
    }

    public function beforeRender()
    {
        exit(0);
    }
}