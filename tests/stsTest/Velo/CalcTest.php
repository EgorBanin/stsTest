<?php

namespace stsTest\Velo;

class CalcTest extends \PHPUnit\Framework\TestCase {

	private $calc;

	public function setUp() {
		$this->calc = new \stsTest\Velo\Calc();
	}

	/**
	 * @dataProvider calculateProvider
	 */
	public function testCalculate($expression, $expected) {
		$this->assertSame($this->calc->calculate($expression), $expected);
	}

	public function calculateProvider() {
		return [
			['', 0],
			['0', 0],
			['1 + 1', 2],
			['1 + 2 * 3', 7],
			['1 / 2 * 4', 2.0],
		];
	}

	/**
	 * @dataProvider exceptionProvider
	 */
	public function testExceptions($expression) {
		$exception = null;
		try {
			$this->calc->calculate($expression);
		} catch(\Exception $exception) {
			//
		}
		$this->assertInstanceOf(\stsTest\Exception::class, $exception);
	}

	public function exceptionProvider() {
		return [
			['bad'],
			['1/0'],
		];
	}

}
