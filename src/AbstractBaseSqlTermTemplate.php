<?php

namespace RebelCode\Expression\Renderer\Sql;

use ArrayAccess;
use Dhii\Util\String\StringableInterface as Stringable;
use Dhii\Data\Container\ContainerHasCapableTrait;
use Dhii\Expression\Renderer\AbstractBaseExpressionTemplate;
use Dhii\Storage\Resource\Sql\Expression\SqlExpressionContextInterface as SqlCtx;
use Psr\Container\ContainerInterface;
use stdClass;

/**
 * Base functionality for templates that render SQL terms.
 *
 * @since [*next-version*]
 */
abstract class AbstractBaseSqlTermTemplate extends AbstractBaseExpressionTemplate
{
    /*
     * Provides functionality for checking if a container has a specific key.
     *
     * @since [*next-version*]
     */
    use ContainerHasCapableTrait;

    /**
     * Resolves an SQL alias from the render context.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable                             $subject The subject for which to resolve the alias.
     * @param array|ArrayAccess|stdClass|ContainerInterface $context The render context.
     *
     * @throws \Psr\Container\ContainerExceptionInterface If an error occurred while reading from the container.
     *
     * @return string|Stringable The resolved alias, or the subject if no alias was found.
     */
    protected function _resolveSqlAliasFromContext($subject, $context)
    {
        if (!$this->_containerHas($context, SqlCtx::K_ALIASES_MAP)) {
            return $subject;
        }

        $aliases = $this->_containerGet($context, SqlCtx::K_ALIASES_MAP);
        $key = $this->_normalizeString($subject);

        return $this->_containerHas($aliases, $key)
            ? $this->_containerGet($aliases, $key)
            : $subject;
    }
}
