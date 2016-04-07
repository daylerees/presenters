<?php

namespace Rees\Presenters;

interface Condition
{
    /**
     * Determine whether a condition passes.
     *
     * @return boolean
     */
    public function check();
}
