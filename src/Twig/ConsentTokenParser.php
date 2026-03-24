<?php

declare(strict_types=1);

namespace Hofff\Contao\Consent\Bridge\Twig;

use Override;
use Twig\Error\SyntaxError;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser;

use function is_array;

final class ConsentTokenParser extends AbstractTokenParser
{
    #[Override]
    public function parse(Token $token): Node
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();

        $consentIdExpr = $this->parser->parseExpression();

        $model = null;
        if ($stream->nextIf(Token::PUNCTUATION_TYPE, ',')) {
            $modelExpr = $this->parser->parseExpression();
            if (! $modelExpr instanceof ConstantExpression) {
                throw new SyntaxError(
                    'The second argument to "hofff_consent" must be a string literal.',
                    $modelExpr->getTemplateLine(),
                    $stream->getSourceContext(),
                );
            }

            /** @psalm-suppress MixedAssignment */
            $model = $modelExpr->getAttribute('value');
            if (! is_array($model)) {
                throw new SyntaxError(
                    'The second argument to "hofff_consent" must be an array.',
                    $modelExpr->getTemplateLine(),
                );
            }
        }

        $placeholderTemplate = null;
        if ($stream->nextIf(Token::PUNCTUATION_TYPE, ',')) {
            $placeholderExpr = $this->parser->parseExpression();
            if (! $placeholderExpr instanceof ConstantExpression) {
                throw new SyntaxError(
                    'The third argument to "hofff_consent" must be a string literal.',
                    $placeholderExpr->getTemplateLine(),
                    $stream->getSourceContext(),
                );
            }

            $placeholderTemplate = (string) $placeholderExpr->getAttribute('value');
        }

        $stream->expect(Token::BLOCK_END_TYPE);

        $body = $this->parser->subparse([$this, 'decideEnd'], true);

        $stream->expect(Token::BLOCK_END_TYPE);

        return new ConsentNode($body, $consentIdExpr, $model, $placeholderTemplate, $lineno);
    }

    public function decideEnd(Token $token): bool
    {
        return $token->test('endhofff_consent');
    }

    #[Override]
    public function getTag(): string
    {
        return 'hofff_consent';
    }
}
