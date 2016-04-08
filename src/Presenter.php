<?php

namespace Rees\Presenters;

/**
 * Class Presenter
 *
 * @package \Rees\Presenters
 */
abstract class Presenter
{
    /**
     * Implement this abstract method to build and present
     * an array as payload.
     */
    abstract public function present();

    /**
     * Start a conditional presenter value.
     *
     * @param mixed $conditions
     *
     * @return \Rees\Presenters\Embed
     */
    public function when($conditions)
    {
        return new Embed($conditions);
    }

    /**
     * Retrieve the presented payload without failed condition
     * instances.
     *
     * @return array
     */
    public function getPresentedPayload()
    {
        return $this->filterFailedConditions($this->present());
    }

    /**
     * Filter failed condition instances from the result payload.
     *
     * @param array $payload
     *
     * @return array
     */
    protected function filterFailedConditions(array $payload)
    {
        // We'll iterate every array instance looking
        // for dummy 'Blank' instances to remove.
        foreach ($payload as $key => $item) {

            // If we find an array, we'll recurse to
            // process nested values.
            if (is_array($item)) {
                $this->filterFailedConditions($item);
            }

            // If we find an instance of 'Blank' we'll
            // remove it, and it's key, directly.
            if ($item instanceof Blank) {
                unset($payload[$key]);
            }
        }

        return $payload;
    }
}
