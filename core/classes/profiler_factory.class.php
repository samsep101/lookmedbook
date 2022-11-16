<?php
	class ProfilerFactory
	{
		private static $profiler;

		public static  function getProfiler()
		{

			if(!self::$profiler)
			{
				self::$profiler = Profiler::getInstance();
                if(PROFILE_LOG_TYPE == 'file')
                {
                    self::$profiler->setLogger(new ProfilerFileLogger(ABS_ROOT . '/media/logs/profiler/'));
                } else {
                    self::$profiler->setLogger(new ProfilerDbLogger());
                }

			}

			return self::$profiler;
		}
	}