<?php

namespace Rees\Presenters;

/**
 * Class Optional
 *
 * @package \Rees\Presenters
 */
class Embed
{
    /**
     * Condition instances.
     *
     * @var array
     */
    protected $conditions = [];

    /**
     * Inject conditions for this embed.
     *
     * @param mixed $conditions
     */
    public function __construct($conditions)
    {
        $this->conditions = is_array($conditions) ? $conditions : [$conditions];
    }

    /**
     * Start a conditional presenter value.
     *
     * @param mixed $conditions
     *
     * @return \Rees\Presenters\Embed
     */
    public function when($conditions)
    {
        $conditions = is_array($conditions) ? $conditions : [$conditions];

        $this->conditions = array_merge($this->conditions, $conditions);

        return $this;
    }

    /**
     * Embed a value should a condition pass.
     * 
     * @param mixed  $value
     * @param string $default
     *
     * @return mixed
     */
    public function embed($value, $default = '__DEFAULT__')
    {
        // Determine whether our conditions pass and
        // whether or not we should embed the provided
        // value.
        if ($this->shouldEmbed()) {
            return $value;
        }

        // If we've not been given a default, we'll insert
        // a 'Blank' dummy class to be removed
        // from the resulting array.
        if ($default === '__DEFAULT__') {
            $default = new Blank;
        }

        return $default;
    }

    /**
     * Check conditions to see if we should embed the value.
     *
     * @return boolean
     */
    public function shouldEmbed()
    {
        // We can have multiple conditions to our embed,
        // so we'll iterate each of them, we'll need all
        // to pass for our embed to succeed.
        foreach ($this->conditions as $condition) {

            // If we've been handed a boolean as a condition
            // we'll just use it's value directly.
            if (is_bool($condition) && !$condition) {
                return false;
            }

            // If we've been handed a condition instance, we'll call the
            // check method to determine the result.
            if ($condition instanceof Condition && ! (bool) $condition->check()) {
                return false;
            }

            // If we've been passed a callable, then we'll execute the callable and
            // cast a boolean result as the condition.
            if (is_callable($condition) && ! (bool) call_user_func($condition)) {
                return false;
            }
        }

        return true;
    }
}
