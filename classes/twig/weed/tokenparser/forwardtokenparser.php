<?php

namespace Weed\Twig\Weed\TokenParser;

use Twig_Token;
use Twig_TokenParser;
use Weed\Twig\Weed\Node\ForwardNode;

class ForwardTokenParser extends Twig_TokenParser {

	public function parse(Twig_Token $token) {
		$lineno = $token->getLine();
		$destination = $this->parser->getExpressionParser()->parseExpression();
		$this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

		return new ForwardNode($destination, $lineno, $this->getTag());
	}

	public function getTag() {
		return 'forward';
	}
}