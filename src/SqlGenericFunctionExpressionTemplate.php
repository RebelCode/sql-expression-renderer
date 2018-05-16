<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\Renderer\AbstractBaseDelegateExpressionTemplate;
use Dhii\Output\TemplateInterface;

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
     * @param TemplateInterface $delegateTemplate The delegate template.
     */
    public function __construct(TemplateInterface $delegateTemplate)
    {
        $this->_setTemplate($delegateTemplate);
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
