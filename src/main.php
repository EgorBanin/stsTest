<?php declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

$inputStr = $argv[1]?? '';

//*

// рациональный способ
$calc = new \stsTest\MossadalCalc();
try {
	$result = $calc->calculate($inputStr);
} catch(\stsTest\Exception $e) {
	echo 'Ошибка: ', $e->getMessage(), "\n";
	exit(1);
}

/*/

// велосипед,который демонстрирует, что я не умею писать персеры
$calc = new \stsTest\Velo\Calc();
try {
	$result = $calc->calculate($inputStr);
} catch(\stsTest\Exception $e) {
	echo 'Ошибка: ', $e->getMessage(), "\n";
	exit(1);
}

//*/

echo $result, "\n";
exit(0);
