<?php

namespace RebelCode\Expression\Renderer\Sql\FuncTest;

use Dhii\Data\Container\Exception\NotFoundException;
use Dhii\Expression\Renderer\ExpressionContextInterface;
use Dhii\Expression\TermInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\Renderer\Sql\SqlFunctionExpressionTemplate as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class SqlFunctionExpressionTemplateTest extends TestCase
{
    /**
     * Creates an expression mock instance.
     *
     * @since [*next-version*]
     *
     * @param string          $type  The expression type.
     * @param TermInterface[] $terms The expression terms.
     *
     * @return MockObject
     */
    public function createExpression($type, $terms = [])
    {
        $mock = $this->getMockBuilder('Dhii\Expression\ExpressionInterface')
                     ->setMethods(['getType', 'getTerms'])
                     ->getMockForAbstractClass();

        $mock->method('getType')->willReturn($type);
        $mock->method('getTerms')->willReturn($terms);

        return $mock;
    }

    /**
     * Creates an expression mock instance.
     *
     * @since [*next-version*]
     *
     * @param string $type The expression type.
     *
     * @return MockObject
     */
    public function createLiteralTerm($type)
    {
        $mock = $this->mockClassAndInterfaces(
            'stdClass',
            [
                'Dhii\Expression\TermInterface',
                'Dhii\Data\ValueAwareInterface',
            ]
        );

        $mock->method('getType')->willReturn($type);

        return $mock;
    }

    /**
     * Creates a mock container instance.
     *
     * @since [*next-version*]
     *
     * @param array $data The data.
     *
     * @return MockObject
     */
    public function createContainer($data = [])
    {
        $mock = $this->mockClassAndInterfaces(
            'ArrayObject',
            [
                'Psr\Container\ContainerInterface',
            ]
        );

        $mock->method('get')->willReturnCallback(
            function($key) use ($mock) {
                if ($mock->offsetExists($key)) {
                    return $mock->offsetGet($key);
                }
                throw new NotFoundException();
            }
        );

        $mock->method('has')->willReturnCallback(
            function($key) use ($mock) {
                return $mock->offsetExists($key);
            }
        );

        $mock->exchangeArray($data);

        return $mock;
    }

    /**
     * Creates a mock template instance.
     *
     * @since [*next-version*]
     *
     * @return MockObject
     */
    public function createTemplate()
    {
        return $this->getMockBuilder('Dhii\Output\TemplateInterface')
                    ->setMethods(['render'])
                    ->getMockForAbstractClass();
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
     * @return MockObject The object that extends and implements the specified class and interfaces.
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
        $subject = new TestSubject('', $this->createContainer());

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests the render method to ensure that the expression is correctly rendered.
     *
     * @since [*next-version*]
     */
    public function testRender()
    {
        $operator = uniqid('op');

        $type1 = uniqid('type-');
        $value1 = uniqid('value');
        $render1 = uniqid('render');

        $type2 = uniqid('type-');
        $value2 = uniqid('value');
        $render2 = uniqid('render');

        $terms = [
            $this->createLiteralTerm($type1, $value1),
            $this->createLiteralTerm($type2, $value2),
        ];
        $expression = $this->createExpression(uniqid('type'), $terms);
        $ctx = [
            ExpressionContextInterface::K_EXPRESSION => $expression,
        ];

        $template1 = $this->createTemplate();
        $template1->method('render')->willReturn($render1);

        $template2 = $this->createTemplate();
        $template2->method('render')->willReturn($render2);

        $container = $this->createContainer(
            [
                $type1 => $template1,
                $type2 => $template2,
            ]
        );
        $subject = new TestSubject($operator, $container);

        $expected = "$operator($render1, $render2)";
        $actual = $subject->render($ctx);

        $this->assertEquals($expected, $actual);
    }
}
