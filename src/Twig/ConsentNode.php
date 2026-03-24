<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Twig;

use Override;
use Twig\Attribute\YieldReady;
use Twig\Compiler;
use Twig\Node\CaptureNode;
use Twig\Node\Expression\AbstractExpression;
use Twig\Node\Node;

use function sprintf;

#[YieldReady]
final class ConsentNode extends Node
{
    public function __construct(
        Node $body,
        AbstractExpression $consentIdExpr,
        array|null $data,
        string|null $placeholderTemplate,
        int $lineno,
    ) {
        parent::__construct(
            ['body' => $body, 'consentId' => $consentIdExpr],
            ['placeholderTemplate' => $placeholderTemplate, 'data' => $data],
            $lineno,
        );
    }

    #[Override]
    public function compile(Compiler $compiler): void
    {
        $compiler->addDebugInfo($this);

        $captureNode = new CaptureNode($this->getNode('body'), $this->getTemplateLine());
        $captureNode->setAttribute('raw', true);

        $contentVar = $compiler->getVarName();

        $compiler
            ->write(sprintf('$%s = ', $contentVar))
            ->subcompile($captureNode)
            ->raw("\n")
            ->write('yield $this->env->getRuntime(')
            ->string(ConsentWrapRuntime::class)
            ->raw(')->wrapContent(')
            ->raw(sprintf('$%s, ', $contentVar))
            ->subcompile($this->getNode('consentId'))
            ->raw(', ')
            ->repr($this->getAttribute('data'))
            ->raw(', ')
            ->repr($this->getAttribute('placeholderTemplate'))
            ->raw(");\n");
    }
}
