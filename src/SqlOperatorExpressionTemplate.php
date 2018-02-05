<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\Renderer\AbstractBaseOperatorExpressionTemplate;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;

/**
 * A template that can render standard SQL expressions, in the form `a OP b OP c ...`.
 *
 * @since [*next-version*]
 */
class SqlOperatorExpressionTemplate extends AbstractBaseOperatorExpressionTemplate
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
