<?php

/*
 * This file is part of the Nemrod package.
 *
 * (c) Conjecto <contact@conjecto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Conjecto\Nemrod\ResourceManager\Registry;

use EasyRdf\RdfNamespace;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Class RdfNamespaceRegistry.
 */
class RdfNamespaceRegistry
{
    /**
     * @return array
     */
    public function namespaces()
    {
        return RdfNamespace::namespaces();
    }

    /**
     *
     */
    public function resetNamespaces()
    {
        RdfNamespace::resetNamespaces();
    }

    /**
     * @param $prefix
     *
     * @return string
     */
    public function get($prefix)
    {
        return RdfNamespace::get($prefix);
    }

    /**
     * @param $prefix
     * @param $long
     */
    public function set($prefix, $long)
    {
        RdfNamespace::set($prefix, $long);
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return RdfNamespace::getDefault();
    }

    /**
     * @param $namespace
     */
    public function setDefault($namespace)
    {
        RdfNamespace::setDefault($namespace);
    }

    /**
     * @param $prefix
     */
    public function delete($prefix)
    {
        RdfNamespace::delete($prefix);
    }

    /**
     *
     */
    public function reset()
    {
        RdfNamespace::reset();
    }

    /**
     * @param $uri
     * @param bool $createNamespace
     *
     * @return array
     */
    public function splitUri($uri, $createNamespace = false)
    {
        return RdfNamespace::splitUri($uri, $createNamespace);
    }

    /**
     * @param $uri
     *
     * @return string
     */
    public function prefixOfUri($uri)
    {
        return RdfNamespace::prefixOfUri($uri);
    }

    /**
     * @param $uri
     * @param bool $createNamespace
     *
     * @return string
     */
    public function shorten($uri, $createNamespace = false)
    {
        return RdfNamespace::shorten($uri, $createNamespace);
    }

    /**
     * @param $shortUri
     *
     * @return string
     */
    public function expand($shortUri)
    {
        return RdfNamespace::expand($shortUri);
    }

    /**
     * @param GetResponseEvent $request
     */
    public function onKernelRequest(GetResponseEvent $request)
    {
        // empty method to allow kernel request event in service definition
    }

    /**
     * Dummy method to be called on commands start.
     */
    public function onConsoleCommand()
    {
    }
}
