<?php
/*
 * This file is part of the Nemrod package.
 *
 * (c) Conjecto <contact@conjecto.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Conjecto\Nemrod\QueryBuilder\Expr;


class GroupExpr extends Base
{
    /**
     * @var string
     */
    protected $preSeparator = '';

    /**
     * @var string
     */
    protected $separator = ' . ';

    /**
     * @var string
     */
    protected $postSeparator = '';

    /**
     * @var array
     */
    protected $allowedClasses = array(
        'Conjecto\\Nemrod\\QueryBuilder\\Expr\\Filter',
        'Conjecto\\Nemrod\\QueryBuilder\\Expr\\Optional',
        'Conjecto\\Nemrod\\QueryBuilder\\Expr\\Bind',
    );

    /**
     * @return array
     */
    public function getParts()
    {
        return $this->parts;
    }
}
