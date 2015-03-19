<?php

namespace Conjecto\RAL\ElasticSearch;

/**
 * Class IndexRegistry.
 */
class IndexRegistry
{
    /**
     * @var array
     */
    private $indexes = array();

    /**
     * @param $name
     * @param $index
     */
    public function registerIndex($name, $index)
    {
        $this->indexes[$name] = $index;
    }

    /**
     * @param $name
     */
    public function getIndex($name)
    {
        if (!isset($this->indexes[$name])) {
            return;
        }

        return $this->indexes[$name];
    }
}
