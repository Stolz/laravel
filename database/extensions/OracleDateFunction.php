<?php

namespace Doctrine\Extensions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

/**
 * Oracle database does not have ANSI/SQL DATE() function but
 * TRUNC() behaves similarly when applied to datetimes columns.
 *
 * This extension is for emulating DATE() using TRUNC(). This
 * way we can keep our Doctrine code using only ANSI/SQL functions.
 */
class OracleDateFunction extends FunctionNode
{
    private $date;

    /**
     * Get function SQL.
     *
     * @param  \Doctrine\ORM\Query\SqlWalker $sqlWalker
     * @return void
    */
    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return sprintf('TRUNC(%s)', $sqlWalker->walkArithmeticPrimary($this->date));
    }

    /**
     * Parse function.
     *
     * @param  \Doctrine\ORM\Query\Parser $parser
     * @return void
    */
    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->date = $parser->ArithmeticExpression();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
