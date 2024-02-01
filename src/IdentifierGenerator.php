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

use spriebsch\filesystem\Directory;
use spriebsch\filesystem\Filesystem;

final class IdentifierGenerator
{
    public function __construct(private readonly Directory $targetDirectory) {}

    public static function run(array $specifications, Directory $targetDirectory): void
    {
        if (str_ends_with($targetDirectory->asString(), 'src')) {
            throw new Exception('Cannot make src/ the target of generated identifiers');
        }

        if (str_ends_with($targetDirectory->asString(), 'vendor')) {
            throw new Exception('Cannot make vendor/ the target of generated identifiers');
        }

        $identifierGenerator = new IdentifierGenerator($targetDirectory);

        foreach ($specifications as $class => $namespace) {
            $identifierGenerator->generateIdentifier($class, $namespace);
        };
    }

    public function generateIdentifier(string $class, ?string $namespace)
    {
        if (str_contains($class, '\\')) {
            throw new Exception(sprintf('Class name %s cannot contain \\', $class));
        }

        if (str_contains($class, '/')) {
            throw new Exception(sprintf('Class name %s cannot contain /', $class));
        }

        // @todo
        /*
         * preg_match( '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $input)
         */

        $template = Filesystem::from(__DIR__ . '/../template/IdentifierTemplate.php');

        $code = $template->load();

        $code = str_replace(
            [
                '%%%namespace%%%',
                '%%%class%%%'
            ],
            [
                $namespace,
                $class
            ],
            $code
        );

        $filename = $class . '.php';

        $this->targetDirectory->deleteFile($filename);
        $this->targetDirectory->createFile($filename, $code);

        return $namespace . '\\' . $class;
    }
}
