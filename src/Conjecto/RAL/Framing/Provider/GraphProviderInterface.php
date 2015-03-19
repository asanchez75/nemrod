<?php
namespace Conjecto\RAL\Framing\Provider;

use EasyRdf\Graph;
use EasyRdf\Resource;

/**
 * Interface GraphProviderInterface.
 */
interface GraphProviderInterface
{
    /**
     * @param Resource $resource
     * @param array    $frame
     *
     * @return Graph
     */
    public function getGraph(Resource $resource, $frame = null);
}
