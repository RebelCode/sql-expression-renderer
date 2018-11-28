<?php

namespace RebelCode\Expression\Renderer\Sql;

use ArrayAccess;
use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\TermInterface;
use Dhii\Expression\VariableTermInterface;
use Dhii\Output\Exception\TemplateRenderException;
use Dhii\Output\Exception\TemplateRenderExceptionInterface;
use Dhii\Storage\Resource\Sql\EntityFieldInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;
use stdClass;

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
     *
     * @throws TemplateRenderExceptionInterface If failed to render the expression.
     */
    protected function _renderExpression(TermInterface $expression, $context = null)
    {
        $name  = $this->_reduceReferenceToString($expression);
        $alias = $this->_resolveSqlAliasFromContext($name, $context);

        $render = ($alias instanceof ExpressionInterface)
            ? $name
            : sprintf('`%s`', $this->_reduceReferenceToString($alias));

        if ($expression instanceof EntityFieldInterface) {
            $entity = $this->_resolveSqlAliasFromContext($expression->getEntity(), $context);
            $entity = $this->_reduceReferenceToString($entity);

            $render = sprintf('`%1$s`.%2$s', $entity, $render);
        }

        return $render;
    }

    /**
     * Reduces an SQL reference term to a string, if needed.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|TermInterface                    $reference The reference to reduce to a string.
     * @param array|stdClass|ArrayAccess|ContainerInterface|null $context   The render context, if any.
     *
     * @return string|Stringable A string containing the name of the reference.
     *
     * @throws TemplateRenderException
     */
    protected function _reduceReferenceToString($reference, $context = null)
    {
        if (is_string($reference) || $reference instanceof Stringable) {
            return $reference;
        }

        $isVariable    = $reference instanceof VariableTermInterface;
        $isEntityField = $reference instanceof EntityFieldInterface;

        if (!$isVariable && !$isEntityField) {
            throw $this->_createTemplateRenderException(
                $this->__('Expression is not a valid SQL reference'),
                null,
                null,
                $this,
                $context
            );
        }

        $name = $isEntityField
            ? $reference->getField()
            : $reference->getKey();

        return $name;
    }
}
