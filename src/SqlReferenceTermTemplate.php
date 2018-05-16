<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\TermInterface;
use Dhii\Expression\VariableTermInterface;
use Dhii\Storage\Resource\Sql\EntityFieldInterface;

/**
 * A template for rendering SQL reference terms, in the form `table`.`column`.
 *
 * @since [*next-version*]
 */
class SqlReferenceTermTemplate extends AbstractBaseSqlTermTemplate
{
    /**
     * Constructor.
     *
     * @since [*next-version*]
     */
    public function __construct()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @since [*next-version*]
     */
    protected function _renderExpression(TermInterface $expression, $context = null)
    {
        $isVariable = $expression instanceof VariableTermInterface;
        $isEntityField = $expression instanceof EntityFieldInterface;

        if (!$isVariable && !$isEntityField) {
            throw $this->_createTemplateRenderException(
                $this->__('Expression is not a valid SQL reference'),
                null,
                null,
                $this,
                $context
            );
        }

        $field = $isEntityField
            ? $expression->getField()
            : $expression->getKey();
        $fieldAliased = $this->_resolveSqlAliasFromContext($field, $context);

        $render = sprintf('`%s`', $fieldAliased);

        if ($isEntityField) {
            $entity = $this->_resolveSqlAliasFromContext($expression->getEntity(), $context);

            $render = sprintf('`%1$s`.%2$s', $entity, $render);
        }

        return $render;
    }
}
