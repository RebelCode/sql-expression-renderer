<?php

namespace RebelCode\Expression\Renderer\Sql;

use ArrayAccess;
use Dhii\Data\ValueAwareInterface;
use Dhii\Expression\LiteralTermInterface;
use Dhii\Expression\TermInterface;
use Dhii\Output\Exception\TemplateRenderException;
use Dhii\Util\String\StringableInterface as Stringable;
use Psr\Container\ContainerInterface;
use stdClass;

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
     *
     * @throws TemplateRenderException If an error occurred while rendering.
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

        if (is_array($origValue)) {
            $values = array_map(function($value) use ($context) {
                return $this->_renderScalarTerm($value, $context);
            }, $origValue);

            return sprintf('(%s)', implode(', ', $values));
        }

        if ($origValue === null) {
            return 'NULL';
        }

        return $this->_renderScalarTerm($origValue, $context);
    }

    /**
     * Renders a scalar term value.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|int|float|bool                   $value   The value.
     * @param array|ArrayAccess|stdClass|ContainerInterface|null $context The context.
     *
     * @return string|Stringable The rendered scalar term.
     */
    protected function _renderScalarTerm($value, $context = null)
    {
        $value     = $this->_normalizeString($value);
        $value     = $this->_resolveSqlAliasFromContext($value, $context);
        $isPdoHash = substr($value, 0, 1) === ':';

        return $isPdoHash ? $value : sprintf('"%s"', $value);
    }
}
