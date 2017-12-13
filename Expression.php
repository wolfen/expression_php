<?php

/**
 * Nodes, Values, Operators, BinaryOperators, UnaryOperators, and Tokens
 * are useful for storing the components of a mathematical expression.
 *
 * @author Peter Wolfenden <wolfen@gmail.com>
 */

function pDebug($str) {
  $DEBUG = false; // set this to true to see debug output
  if ($DEBUG) {
    print($str);
  }
  flush();
}

class Node { }

class Value extends Node {
  public $Val = null;

  function Value($v) {
    //pDebug(__CLASS__ . ": Constructing $v\n");
    $this->Val = $v;
  }
}

class Operator extends Node {
  public $Op = null;

  function Operator($o) {
    //pDebug(__CLASS__ . ": Constructing $o\n");
    $this->Op = $o;
  }
}

class BinaryOperator extends Operator {
  public $leftChild = null;
  public $rightChild = null;
}

class UnaryOperator extends Operator {
  public $onlyChild = null;
}

class Token {
  public $type = null;
  public $value = null;

  function Token($t, $v) {
    //pDebug(__CLASS__ . ": Constructing $t, $v\n");
    $this->type = $t;
    $this->value = $v;
  }
}
