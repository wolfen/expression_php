<?php

require_once('Expression.php');
require_once('Stack.php');

/**
 * The ExpressionParser builds an Expression tree from a sequence of (tokenized)
 * arithetic values and operators.
 *
 * @author Peter Wolfenden <wolfen@gmail.com>
 */

class ExpressionParser {

  /**
   * Return the precedence of the specified operator; Higher precedence
   * corresponds to larger numerical value.
   *
   * @param string $op (TODO: Replace this with an enumerated type)
   * @return int
   */
  public function oPrecedence($op) {
    pDebug("oPrecedence for [$op]\n");
    if (($op == '+') || ($op == '-')) {
      return 1;
    }
    if (($op == '*') || ($op == '/')) {
      return 2;
    }

    throw new Exception('Unknown/unsupported operator: [' . $op . "]\n");
  }

  public function popAndPush($opStack, $valStack) {
    pDebug('Popping operator [' . print_r($opStack->top(), true) . "]\n");
    $o = $opStack->pop();

    if (is_a($o, 'BinaryOperator')) {
      if ($valStack->count() < 2) {
        throw new Exception("Binary operator requires two values!\n");
      }

      pDebug('Popping value [' . print_r($valStack->top(), true) . "]\n");
      $o->leftChild = $valStack->pop();

      pDebug('Popping value [' . print_r($valStack->top(), true) . "]\n");
      $o->rightChild = $valStack->pop();

      pDebug('Pushing operator [' . print_r($o, true) . "]\n");
      $valStack->push($o);
    } else {
      throw new Exception("Parsing currently supported only for BinaryOperators!");
    }
  }

  public function infix_tokens_to_tree($toks) {
    //pDebug(__CLASS__ . " toks=[" . print_r($toks, true) . "]\n");

    $opStack = new Stack();
    $valStack = new Stack();

    $parenDepth = 0;
    $subToks = array();

    foreach ($toks as $t) {
      //pDebug("parenDepth=[$parenDepth]\n");

      if ($t->type == 'LeftParen') {
        $parenDepth += 1;
      } else if ($t->type == 'RightParen') {
        $parenDepth -= 1;
        if ($parenDepth < 0) {
          throw new Exception("Right paren with no corresponding left paren!");
        }
        if ($parenDepth == 0) {
          pDebug("drop the leading LeftParen\n");
          array_shift($subToks);
          pDebug('recursing on subToks: ' . print_r($subToks, true) . "]\n");
          $subTree = $this->infix_tokens_to_tree($subToks);
          pDebug('got subTree: ' . print_r($subTree, true) . "]\n");

          if ($valStack->count() == 0) {
            pDebug('Pushing subTree [' . $subTree . "]\n");
            $valStack->push($subTree);
          } else {
            if ($opStack->count() == 0) {
              throw new Exception("Two values require an interposed Binary Operator!\n");
            } else {
              pDebug('Pushing subTree [' . $subTree . "]\n");
              $valStack->push($subTree);
            }
          }
          continue;
        }
      }
      if ($parenDepth > 0) { 
        $subToks[] = $t;
      } else if ($t->type == 'Value') {
        if ($valStack->count() == 0) {
          pDebug('Pushing value [' . $t->value . "]\n");
          $valStack->push(new Value($t->value));
        } else {
          if ($opStack->count() == 0) {
            throw new Exception("Two values require an interposed Binary Operator!\n");
          } else {
            pDebug('Pushing value [' . $t->value . "]\n");
            $valStack->push(new Value($t->value));
          }
        }
      } else if ($t->type == 'BinaryOperator') {
        if ($valStack->count() == 0) {
          throw new Exception("Binary Operator requires a preceding Value!\n");
        } else {
          while (
            ($opStack->count() > 0) &&
            (oPrecedence($opStack->top()->Op) > oPrecedence($t->value))
          ) {
            popAndPush($opStack, $valStack);
          }
          pDebug('Pushing operator [' . print_r($t, true) . "]\n");
          $opStack->push(new BinaryOperator($t->value));
        }
      } else {
        throw new Exception('Unknown/unsupported Token type: [' . $t->type . "]\n");
      }
    }

    if ($parenDepth > 0) {
      throw new Exception("Left paren with no corresponding right paren!");
    }

    pDebug("Unwind the stack of operators...\n");
    while ($opStack->count() > 0) {
      $this->popAndPush($opStack, $valStack);
    }

    pDebug("Returning opStack [" . print_r($opStack, true) . "]\n");
    pDebug("Returning valStack: [" . print_r($valStack, true) . "]\n");

    return $valStack;
  }

}
