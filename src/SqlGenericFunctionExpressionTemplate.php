<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\AbstractBaseDelegateExpressionTemplate;
use Psr\Container\ContainerInterface;

/**
 * A template that can render SQL expressions in function-argument style, in the form `OP(a, b, ...)`.
 *
 * This implementation uses the capitalized expression type as the function name, rather than being aware of an
 * operator string, ignoring any "sql_fn_" prefixes in the type.
 *
 * @since [*next-version*]
 */
class SqlGenericFunctionExpressionTemplate extends AbstractBaseDelegateExpressionTemplate
{
    /**
     * The prefix to trim.
     *
     * @since [*next-version*]
     */
    const PREFIX = 'sql_fn_';

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
        $opStr = $expression->getType();

        if (stripos($opStr, static::PREFIX) === 0) {
            $opStr = substr($opStr, strlen(static::PREFIX));
        }

        $fnName  = strtoupper($opStr);
        $argsStr = implode(', ', $renderedTerms);

        return sprintf('%1$s(%2$s)', $fnName, $argsStr);
    }
}
