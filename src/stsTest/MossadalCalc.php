<?php declare(strict_types=1);

namespace stsTest;

use MathParser\StdMathParser;
use MathParser\Interpreting\Evaluator;
use MathParser\Exceptions\SyntaxErrorException;
use MathParser\Exceptions\DivisionByZeroException;

class MossadalCalc implements ICalc {

	private $parser;

	private $evaluator;

	public function __construct() {
		$this->parser = new StdMathParser();
		$this->evaluator = new Evaluator();
	}

	public function calculate(string $expression) {
		try {
			$AST = $this->parser->parse($expression);
		} catch(SyntaxErrorException $e) {
			throw new Exception('Некорректное выражение');
		} catch(DivisionByZeroException $e) {
			throw new Exception('Деление на 0');
		} catch(\Exception $e) {
			throw new Exception('Некая фигня', 1, $e);
		}

		if ( ! $AST) {
			return 0;
		}

		try {
			$value = $AST->accept($this->evaluator);
		} catch(\Exception $e) {
			throw new Exception('Некая фигня', 1, $e);
		}

		return $value;
	}

}
