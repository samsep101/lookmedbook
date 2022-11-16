<?php
    class JsonResponse
    {
        public static function result($data = true, $visit_id = 0)
        {
            echo json_encode(array('status' => 0, 'result' => $data, 'visit_id' => $visit_id));
            $profiler = Profiler::getInstance();
            $profiler->stopTime('page');
            $profiler->logdata();
            exit(); 
        }

        public static function rawResult($data)
        {
            echo json_encode($data);
            $profiler = Profiler::getInstance();
            $profiler->stopTime('page');
            $profiler->logdata();
            exit();
        }
        
        public static function error($status_code, $data = array())
        {
            echo json_encode(array(
				'status' => $status_code,
				'data' => $data
			 ));
            $profiler = Profiler::getInstance();
            $profiler->stopTime('page');
            $profiler->logdata();
            exit();
        }
    }