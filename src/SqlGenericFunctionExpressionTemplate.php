<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\AbstractBaseDelegateExpressionTemplate;
use Psr\Container\ContainerInterface;

/**
 * A template that can render SQL expressions in function-argument style, in the form `OP(a, b, ...)`.
 *
 * This implementation uses the capitalized expression type as the function name, rather than being aware of an
 * operator string, ignoring any "sql_" prefixes in the type.
 *
 * @since [*next-version*]
 */
class SqlGenericFunctionExpressionTemplate extends AbstractBaseDelegateExpressionTemplate
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
        $opStr = strtoupper($expression->getType());

        if (strpos($opStr, 'SQL_') === 0) {
            $opStr = substr($opStr, 4);
        }

        $argsStr = implode(', ', $renderedTerms);

        return sprintf('%1$s(%2$s)', $opStr, $argsStr);
    }
}
