<?php
/**
 * Created by PhpStorm.
 * User: Erwan
 * Date: 06/01/2015
 * Time: 11:45
 */

namespace Devyn\QueryBuilder\Expr;

use Doctrine\ORM\Query\Expr\Base;

class Where extends Base
{
    /**
     * @var string
     */
    protected $preSeparator = 'WHERE { ';

    /**
     * @var string
     */
    protected $separator = ' . ';

    /**
     * @var string
     */
    protected $postSeparator = ' } ';

    /**
     * @var array
     */
    protected $allowedClasses = array(
        'Devyn\\QueryBuilder\\Expr\\Union',
        'Devyn\\QueryBuilder\\Expr\\Filter',
        'Devyn\\QueryBuilder\\Expr\\Optional',
        'Devyn\\QueryBuilder\\Expr\\GroupExpr',
    );

    /**
     * @return array
     */
    public function getParts()
    {
        return $this->parts;
    }
}