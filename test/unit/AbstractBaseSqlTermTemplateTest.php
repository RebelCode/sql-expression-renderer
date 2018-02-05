<?php

namespace RebelCode\Expression\Renderer\UnitTest;

use Dhii\Storage\Resource\Sql\Expression\SqlExpressionContextInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Xpmock\TestCase;
use RebelCode\Expression\Renderer\Sql as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractBaseSqlTermTemplateTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\Renderer\Sql\AbstractBaseSqlTermTemplate';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return MockObject
     */
    public function createInstance()
    {
        return $this->getMockForAbstractClass(static::TEST_SUBJECT_CLASSNAME);
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME,
            $subject,
            'A valid instance of the test subject could not be created.'
        );

        $this->assertInstanceOf(
            'Dhii\Expression\Renderer\AbstractBaseExpressionTemplate',
            $subject,
            'Test subject does not extend expected parent class.'
        );
    }

    /**
     * Tests the SQL alias resolve method to assert whether the correct alias is retrieved.
     *
     * @since [*next-version*]
     */
    public function testResolveSqlAliasFromContext()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $context = [
            SqlExpressionContextInterface::K_ALIASES_MAP => [
                $k1 = uniqid('key-') => $a1 = uniqid('alias-'),
                $k2 = uniqid('key-') => $a2 = uniqid('alias-'),
                $k3 = uniqid('key-') => $a3 = uniqid('alias-'),
            ],
        ];

        $expected = $a2;
        $actual = $reflect->_resolveSqlAliasFromContext($k2, $context);

        $this->assertEquals($expected, $actual, 'Retrieved value is not the expected alias.');
    }

    /**
     * Tests the SQL alias resolve method to assert whether the original value is retrieved when no alias is found.
     *
     * @since [*next-version*]
     */
    public function testResolveSqlAliasFromContextNoAlias()
    {
        $subject = $this->createInstance();
        $reflect = $this->reflect($subject);

        $context = [
            SqlExpressionContextInterface::K_ALIASES_MAP => [
                $k1 = uniqid('key-') => $a1 = uniqid('alias-'),
                $k2 = uniqid('key-') => $a2 = uniqid('alias-'),
                $k3 = uniqid('key-') => $a3 = uniqid('alias-'),
            ],
        ];

        $arg = uniqid('arg');
        $expected = $arg;
        $actual = $reflect->_resolveSqlAliasFromContext($arg, $context);

        $this->assertEquals($expected, $actual, 'Retrieved value is the not the original value.');
    }
}
