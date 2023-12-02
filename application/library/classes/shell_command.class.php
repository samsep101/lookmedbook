<?php
    class ShellCommand implements IShellCommand
    {
        public function execute($command)
        {
            return shell_exec($command);
        }
    }