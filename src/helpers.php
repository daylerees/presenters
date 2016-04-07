<?php

if (!function_exists('when')) {
    /**
     * Alias to optional presentation include.
     *
     * @param mixed $conditions
     *
     * @return \Rees\Presenters\Optional
     */
    function when($conditions) {
        return new \Rees\Presenters\Optional($conditions);
    }
}
