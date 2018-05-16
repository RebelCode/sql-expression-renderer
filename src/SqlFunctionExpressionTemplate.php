<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\Renderer\AbstractBaseFunctionExpressionTemplate;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * A template that can render SQL expressions in function-argument style, in the form `OP(a, b, ...)`.
 *
 * @since [*next-version*]
 */
class SqlFunctionExpressionTemplate extends AbstractBaseFunctionExpressionTemplate
{
    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable $operator         The operator string.
     *@param TemplateInterface  $delegateTemplate The delegate template.
     */
    public function __construct($operator, TemplateInterface $delegateTemplate)
    {
        $this->_setOperatorString($operator);
        $this->_setTemplate($delegateTemplate);
    }
}
