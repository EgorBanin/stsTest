<?php declare(strict_types=1);

namespace stsTest\Velo;

use stsTest\ICalc;
use stsTest\Exception as BaseException;

class Calc implements ICalc {

	private $parser;

	public function __construct() {
		$this->parser = new Parser();
	}

	public function calculate(string $expression) {
		try {
			$statement = $this->parser->parse($expression);
		} catch(Exception $e) {
			throw new BaseException('Некорректное выражение');
		}

		try {
			$result = $statement->calculate();
		} catch(Exception $e) {
			throw new BaseException('Деление на 0');
		}

		return $result;
	}

}
