<?php

namespace RebelCode\Expression\Renderer\Sql\FuncTest;

use Dhii\Storage\Resource\Sql\Expression\SqlExpressionContextInterface as SqlCtx;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\Renderer\Sql\SqlReferenceTermTemplate as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class SqlReferenceTemplateTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'RebelCode\Expression\Renderer\Sql\SqlReferenceTermTemplate';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods Optional additional mock methods.
     *
     * @return TestSubject
     */
    public function createInstance(array $methods = [])
    {
        return new TestSubject();
    }

    /**
     * Creates an entity field term instance.
     *
     * @since [*next-version*]
     *
     * @return MockObject
     */
    public function createEntityFieldTerm()
    {
        return $this->mockClassAndInterfaces(
            'stdClass',
            [
                'Dhii\Expression\TermInterface',
                'Dhii\Storage\Resource\Sql\EntityFieldInterface',
            ]
        );
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string   $className      Name of the class for the mock to extend.
     * @param string[] $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockObject
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf(
            'abstract class %1$s extends %2$s implements %3$s {}',
            [
                $paddingClassName,
                $className,
                implode(', ', $interfaceNames),
            ]
        );
        eval($definition);

        return $this->getMockForAbstractClass($paddingClassName);
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'An instance of the test subject could not be created'
        );
    }

    /**
     * Tests the render method to assert whether the entity field term is correctly rendered.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $subject = $this->createInstance();

        $entity = uniqid('entity');
        $field = uniqid('field');

        $term = $this->createEntityFieldTerm();
        $term->method('getEntity')->willReturn($entity);
        $term->method('getField')->willReturn($field);

        $context = [SqlCtx::K_EXPRESSION => $term];

        $expected = "`$entity`.`$field`";
        $actual = $subject->render($context);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the render method to assert whether the entity field term is correctly aliased rendered.
     *
     * @since [*next-version*]
     */
    public function testRenderAliases()
    {
        $subject = $this->createInstance();

        $entity = uniqid('entity');
        $field = uniqid('field');

        $entityAlias = uniqid('entity');
        $fieldAlias = uniqid('field');

        $term = $this->createEntityFieldTerm();
        $term->method('getEntity')->willReturn($entity);
        $term->method('getField')->willReturn($field);

        $context = [
            SqlCtx::K_EXPRESSION  => $term,
            SqlCtx::K_ALIASES_MAP => [
                $entity => $entityAlias,
                $field  => $fieldAlias,
            ],
        ];

        $expected = "`$entityAlias`.`$fieldAlias`";
        $actual = $subject->render($context);

        $this->assertEquals($expected, $actual);
    }

    /**
     * Tests the render method to assert whether an exception is thrown when a non-entity field term is given.
     *
     * @since [*next-version*]
     */
    public function testRenderInvalidTerm()
    {
        $subject = $this->createInstance();

        $term = $this->mock('Dhii\Expression\ExpressionInterface')
                     ->getType()
                     ->getTerms()
                     ->new();
        $context = $this->getMockBuilder('Psr\Container\ContainerInterface')
                        ->getMockForAbstractClass();
        $context->method('get')->with(SqlCtx::K_EXPRESSION)->willReturn($term);

        $this->setExpectedException('Dhii\Output\Exception\TemplateRenderExceptionInterface');

        $subject->render($context);
    }
}
