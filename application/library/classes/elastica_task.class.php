<?php

class ElasticaTask
{
	public static function indexClinic($clinic)
	{
        if (!($clinic instanceof ClinicModel)) {
            /**
             * @var ClinicManager $clinic_manager
             */
            $clinic_manager = ModelManagerFactory::getByName('clinic');
            $clinic = $clinic_manager->getOneById($clinic);
        }

		$index_control = new ElasticSearchClinicIndexControl();
		$index_control->addDocument($clinic);
	}

	public static function indexDoctor($doctor)
	{
        if (!($doctor instanceof DoctorModel)) {
            /**
             * @var DoctorManager $doctor_manager
             */
            $doctor_manager = ModelManagerFactory::getByName('doctor');
            $doctor = $doctor_manager->getOneById($doctor);
        }

        $index_control = new ElasticSearchDoctorIndexControl();
		$index_control->addDocument($doctor);
	}

    /**
     * @param DoctorModel[] $doctors
     */
    public static function indexDoctors($doctors)
    {
        $index = new ElasticSearchDoctorIndexControl();
        $index->addDocuments($doctors);
    }

    public static function deleteDoctor($id)
    {
        $index_control = new ElasticSearchDoctorIndexControl();
        $index_control->deleteById($id);
    }

    public static function deleteClinic($id)
    {
        $index_control = new ElasticSearchClinicIndexControl();
        $index_control->deleteById($id);
    }

	public static function indexDisease($disease_id)
    {
        if (!$disease_id instanceof DiseaseModel) {
            $diseaseManager = ModelManagerFactory::getByName('disease');
            $disease_id = $diseaseManager->getOneById($disease_id);
        }

        $index_control = new ElasticSearchDiseaseIndexControl();
        $index_control->addDocument($disease_id);
    }


    public static function elasticIsActive(){

        ob_start();
        $ch =  curl_init("localhost:9200");
        $res = curl_exec($ch);
        curl_close($ch);
        ob_get_clean();

        return ($res === false);


    }

}
