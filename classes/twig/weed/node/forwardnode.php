<?php

namespace Weed\Twig\Weed\Node;

use Twig_Compiler;
use Twig_Node;

class ForwardNode extends Twig_Node {

	public function __construct($destination, $lineno, $tag = null) {
		parent::__construct(array('destination' => $destination), array(), $lineno, $tag);
	}

	public function compile(Twig_Compiler $compiler) {
		$compiler
			->addDebugInfo($this)
			->write("echo \\Controller::call(")
			->subcompile($this->getNode('destination'))
			->raw(")->render()	;\n");
	}
}