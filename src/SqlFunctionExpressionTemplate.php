<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\Renderer\AbstractBaseFunctionExpressionTemplate;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;

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
     * @param string|Stringable  $operator          The operator string.
     * @param ContainerInterface $delegateTemplates The container of delegate templates, by key-type.
     */
    public function __construct($operator, ContainerInterface $delegateTemplates)
    {
        $this->_setOperatorString($operator);
        $this->_setTermTypeRendererContainer($delegateTemplates);
    }
}
