<?php declare(strict_types=1);

namespace stsTest\Velo;

abstract class Op {

	protected $left;

	protected $right;

	public function __construct(IStatement $left, IStatement $right) {
		$this->left = $left;
		$this->right = $right;
	}

}
