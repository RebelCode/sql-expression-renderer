<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\Renderer\AbstractBaseOperatorExpressionTemplate;
use Dhii\Output\TemplateInterface;
use Dhii\Util\String\StringableInterface as Stringable;

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
     * @param string|Stringable $operator         The operator string.
     * @param TemplateInterface $delegateTemplate The delegate template.
     */
    public function __construct($operator, TemplateInterface $delegateTemplate)
    {
        $this->_setOperatorString($operator);
        $this->_setTemplate($delegateTemplate);
    }
}
