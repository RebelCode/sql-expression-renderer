<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Expression\TermInterface;
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
        if (!($expression instanceof EntityFieldInterface)) {
            throw $this->_createTemplateRenderException(
                $this->__('Expression is not a valid SQL reference'),
                null,
                null,
                $this,
                $context
            );
        }

        $entity = $this->_resolveSqlAliasFromContext($expression->getEntity(), $context);
        $field = $this->_resolveSqlAliasFromContext($expression->getField(), $context);

        return sprintf('`%1$s`.`%2$s`', $entity, $field);
    }
}
