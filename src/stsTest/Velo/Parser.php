<?php declare(strict_types=1);

namespace stsTest\Velo;

class Parser {

	const TOKEN_NUM = 'num';
	const TOKEN_PLUS = 'plus';
	const TOKEN_MINUS = 'minus';
	const TOKEN_MULT = 'mult';
	const TOKEN_DIV = 'div';
	const TOKEN_SP = 'sp';

	private $tokenPatterns = [
		self::TOKEN_NUM => '[0-9]+',
		self::TOKEN_PLUS => '\+',
		self::TOKEN_MINUS => '\-',
		self::TOKEN_MULT => '\*',
		self::TOKEN_DIV => '\/',
		self::TOKEN_SP => '\s+',
	];

	private $opTokens = [
		self::TOKEN_PLUS,
		self::TOKEN_MINUS,
		self::TOKEN_MULT,
		self::TOKEN_DIV,
	];

	private $priority = [
		self::TOKEN_MULT => 1,
		self::TOKEN_DIV => 1,
	];

	private $opClasses = [
		self::TOKEN_PLUS => Plus::class,
		self::TOKEN_MINUS => Minus::class,
		self::TOKEN_MULT => Mult::class,
		self::TOKEN_DIV => Div::class,
	];

	public function parse(string $expression): IStatement {
		// получаем токены
		$tokens = $this->tokenize($expression);
		// собираем все операции (все операторы и числа слева и справа от них)
		$allOps = $this->allOps($tokens);
		// сортируем операции по приоритету
		usort($allOps, function($a, $b) {
			$aPriopity = $this->priority[$a['op'][0]]?? 0;
			$bPriopity = $this->priority[$b['op'][0]]?? 0;

			return $bPriopity <=> $aPriopity;
		});

		// строим дерево-выражение
		$index = [];
		if ($allOps) {
			foreach ($allOps as $op) {
				$opClass = $this->opClasses[$op['op'][0]]?? null;
				if ($opClass) {
					$left = $index[$op['left'][2]]?? new Num((int) $op['left'][1]);
					$right = $index[$op['right'][2]]?? new Num((int) $op['right'][1]);
					$statement = new $opClass($left, $right);

					if ( ! isset($index[$op['left'][2]])) {
						$index[$op['left'][2]] = $statement;
						$index[$op['right'][2]] = $statement;
					}
				}
			}
		} else {
			$statement = new EmptyStatement();
		}

		return $statement;

	}

	private function tokenize(string $expression): array {
		$expression .= ' '; // техника мастера Шифу :-)
		$tokens = [];
		$buffer = '';
		for ($i = 0; $i < strlen($expression); ++$i) {
			$ch = $expression[$i];
			if ($this->getTokenId($buffer . $ch) === null) {
				$id = $this->getTokenId($buffer);
				if ($id !== null) {
					$tokens[] = [$id, $buffer];
					$buffer = '';
				} else {
					throw new Exception('Не корректное выражение, а говно');
				}
			}

			$buffer .= $ch;
		}

		return $tokens;
	}

	private function getTokenId(string $seq) {
		$id = null;
		foreach ($this->tokenPatterns as $tokenId => $pattern) {
			$regex = '~^' . $pattern . '$~';
			if (preg_match($regex, $seq) === 1) {
				$id = $tokenId;
				break;
			}
		}

		return $id;
	}

	private function allOps(array $tokens) {
		// вырезаем пробелы
		$tokens = array_filter($tokens, function($token) {
			return $token[0] !== self::TOKEN_SP;
		});
		// собираем операторы
		$ops = [];
		$op = [
			'op' => null,
			'left' => null,
			'right' => null,
		];
		$id = 0;
		foreach ($tokens as $i => $token) {
			// каждому токену добавляем уникальный id
			$token[] = $id++;
			if (in_array($token[0], $this->opTokens)) {
				if ($op['op'] === null) {
					$op['op'] = $token;
				} else {
					throw new Exception('Не корректное выражение, а говно');
				}
			} else {
				if ($op['op'] === null && $op['left'] === null) {
					$op['left'] = $token;
				} elseif ($op['right'] === null) {
					$op['right'] = $token;
				} else {
					throw new Exception('Не корректное выражение, а говно');
				}
			}

			if (
				$op['op'] !== null
				&& $op['left'] !== null
				&& $op['right'] !== null
			) {
				$ops[] = $op;
				$op = [
					'op' => null,
					'left' => $token,
					'right' => null,
				];
			}
		}

		return $ops;
	}

}
