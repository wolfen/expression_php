<?php

require_once('Parse.php');

/**
 * This simply demonstrates the behavior of the infix expression parser.
 *
 * TODO:
 * - Add support for expression tree output in .dot format.
 * - Add a tokenizer.
 * - Add an evaluator.
 * - Add a command-line front end (with USAGE string describing options).
 * - Add support for unary negation, exponentiation, complex numbers, etc.
 * - Add support for variables.
 */

print "Building tokens for infix expression: (5+7)*9\n";
$tokens = array(
  new Token('LeftParen', null),
  new Token('Value', '5'),
  new Token('BinaryOperator', '+'),
  new Token('Value', '7'),
  new Token('RightParen', null),
  new Token('BinaryOperator', '*'),
  new Token('Value', '9')
);

$p = new ExpressionParser();
print "Parsing tokens into an expression tree: " . print_r($p->infix_tokens_to_tree($tokens), true) . "\n";
