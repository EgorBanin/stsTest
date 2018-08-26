<?php declare(strict_types=1);

namespace stsTest\Velo;

class EmptyStatement implements IStatement {

	public function calculate() {
		return 0;
	}

}
