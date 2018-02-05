<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\AbstractBaseDelegateExpressionTemplate;
use Dhii\Storage\Resource\Sql\Expression\SqlOperatorInterface;
use Psr\Container\ContainerInterface;

/**
 * A template for an SQL BETWEEN expression.
 *
 * @since [*next-version*]
 */
class SqlBetweenExpressionTemplate extends AbstractBaseDelegateExpressionTemplate
{
    /**
     * Constructor.
     *
     * @since [*next-version*]
     *
     * @param ContainerInterface $delegateTemplates The container of delegate templates, by key-type.
     */
    public function __construct(ContainerInterface $delegateTemplates)
    {
        $this->_setTermTypeRendererContainer($delegateTemplates);
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _compileExpressionTerms(
        ExpressionInterface $expression,
        array $renderedTerms,
        $context = null
    ) {
        $operator = SqlOperatorInterface::OP_BETWEEN;
        $glue = sprintf(' %s ', SqlOperatorInterface::OP_AND);
        $imploded = implode($glue, $renderedTerms);

        return sprintf('%1$s %2$s', $operator, $imploded);
    }
}
