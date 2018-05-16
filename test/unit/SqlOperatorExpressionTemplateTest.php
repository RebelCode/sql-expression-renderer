<?php

namespace RebelCode\Expression\Renderer\Sql\FuncTest;

use Dhii\Expression\Renderer\ExpressionContextInterface;
use Dhii\Expression\TermInterface;
use Dhii\Output\TemplateInterface;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use RebelCode\Expression\Renderer\Sql\SqlOperatorExpressionTemplate as TestSubject;
use Xpmock\TestCase;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class SqlOperatorExpressionTemplateTest extends TestCase
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
     * Creates a mock template instance.
     *
     * @since [*next-version*]
     *
     * @return MockObject|TemplateInterface
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
        $subject = new TestSubject('', $this->createTemplate());

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

        $dlgTemplate = $this->createTemplate();
        $dlgTemplate->expects($this->exactly(2))
                    ->method('render')
                    ->willReturnOnConsecutiveCalls($render1, $render2);

        $subject = new TestSubject($operator, $dlgTemplate);

        $expected = "($render1 $operator $render2)";
        $actual = $subject->render($ctx);

        $this->assertEquals($expected, $actual);
    }
}
