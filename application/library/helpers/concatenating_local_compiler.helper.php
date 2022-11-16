<?php

use Closure\AbstractCompiler;

class ConcatenatingLocalCompilerHelper extends AbstractCompiler
{
    public function compile()
    {
        return $this->getCompilerResponse()
            ->setCompiledCode(implode("\n", $this->scripts));
    }
}
