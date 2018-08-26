<?php declare(strict_types=1);

namespace stsTest\Velo;

class Div extends Op implements IStatement {

	public function calculate() {
		$left = $this->left->calculate();
		$right = $this->right->calculate();

		if ($right == 0) {
			throw new Exception('Деление на 0');
		}

		return $left / $right;
	}

}
