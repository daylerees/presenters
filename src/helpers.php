<?php

if (!function_exists('when')) {
    /**
     * Alias to optional presentation include.
     *
     * @param mixed $conditions
     *
     * @return \Rees\Presenters\Embed
     */
    function when($conditions) {
        return new \Rees\Presenters\Embed($conditions);
    }
}
