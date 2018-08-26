<?php declare(strict_types=1);

namespace stsTest\Velo;

class Plus extends Op implements IStatement {

	public function calculate() {
		$left = $this->left->calculate();
		$right = $this->right->calculate();

		return $left + $right;
	}

}
