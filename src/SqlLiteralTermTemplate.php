<?php

namespace RebelCode\Expression\Renderer\Sql;

use Dhii\Data\ValueAwareInterface;
use Dhii\Expression\ExpressionInterface;
use Dhii\Expression\LiteralTermInterface;
use Dhii\Expression\TermInterface;

/**
 * A template for rendering SQL literal value terms.
 *
 * @since [*next-version*]
 */
class SqlLiteralTermTemplate extends AbstractBaseSqlTermTemplate
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
        if (!($expression instanceof LiteralTermInterface) && !($expression instanceof ValueAwareInterface)) {
            throw $this->_createTemplateRenderException(
                $this->__('Expression is not a literal term or a value-aware term'),
                null,
                null,
                $this,
                $context
            );
        }

        $origValue = $expression->getValue();
        $value = $this->_resolveSqlAliasFromContext($origValue, $context);
        $isPdoHash = substr($value, 0, 1) === ':';

        // Do not quote if value is a PDO hash
        return $isPdoHash
            ? $value
            : sprintf('"%s"', $value);
    }
}
