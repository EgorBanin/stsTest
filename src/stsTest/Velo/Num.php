<?php declare(strict_types=1);

namespace stsTest\Velo;

class Num implements IStatement {

	private $val;

	public function __construct($val) {
		$this->val = $val;
	}

	public function calculate() {
		return $this->val;
	}

}
