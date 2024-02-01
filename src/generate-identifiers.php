<?php declare(strict_types=1);
/*
 * This file is part of Identifier-Generator.
 *
 * (c) Stefan Priebsch <stefan@priebsch.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spriebsch\identifiergenerator;

use spriebsch\filesystem\Filesystem;

require $_composer_autoload_path;

if ($argc !== 3) {
    throw new Exception('Usage: %s <specification source file> <target directory>');
}

$specificationSource = Filesystem::from($argv[1]);

if (!$specificationSource->isFile()) {
    throw new Exception(
        sprintf(
            '%s is not a file',
            $argv[1]
        )
    );
}

$specification = $specificationSource->require();

if (!is_array($specification)) {
    throw new Exception(
        sprintf(
            'Specification file %s does not return array',
            $argv[1]
        )
    );
}

IdentifierGenerator::run($specification, Filesystem::from($argv[2]));