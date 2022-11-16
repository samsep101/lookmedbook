<?php
    class SystemMonitor
    {
        /**
         * @var IShellCommand
         */
        private $shell_command;

        public function __construct($shell_command = null)
        {
            if($shell_command)
                $this->shell_command = $shell_command;
            else
                $this->shell_command = new ShellCommand();
        }

        public function getFreeDiskSpacePercent()
        {
            $command = 'df -h';

            $data = $this->shell_command->execute($command);
            $data = explode("\n",$data);

            $values = preg_split('/ +/', $data[1]);

            $percent = (int)$values[4];

            return (100 - $percent);
        }
    }