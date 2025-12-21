<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items;

use Symfony\Component\Filesystem\Path;
use WP_CLI;
use WP_CLI\Utils;
use WP_CLI_Command;
use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Vigihdev\WpCliModels\Entities\MenuEntity;
use Vigihdev\WpCliModels\DTOs\Entities\Menu\{MenuEntityDto, MenuItemEntityDto};
use Vigihdev\WpCliModels\Entities\MenuItemEntity;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\DirectoryValidator;
use Vigihdev\WpCliModels\Validators\FileValidator;

final class Export_Menu_Item_Command extends Menu_Base_Command
{

    private array $dataExport = [];

    public function __construct()
    {
        return parent::__construct('menu-item:export');
    }

    /**
     * Mengekspor item menu berdasarkan kriteria tertentu
     *
     * ## OPTIONS
     *
     * [--filter=<filter>]
     * : Kriteria filter untuk menentukan item menu yang akan diekspor
     * jika tidak dispesifikasikan, semua item menu akan diekspor.
     *
     * [--fields=<fields>]
     * : Menentukan fields yang akan diekspor
     * jika tidak dispesifikasikan, semua fields akan diekspor.
     * default: type,title,url,items
     * options:
     *   - type
     *   - title
     *   - url
     *   - items
     * ---
     *
     * [--format=<format>]
     * : Format output ekspor
     * default: json
     * options:
     *   - json
     * ---
     * 
     * [--dry-run]
     * : Menampilkan apa yang akan diekspor tanpa benar-benar melakukan ekspor
     *
     * [--output=<file>]
     * : Menyimpan hasil ekspor ke file yang ditentukan
     *
     * ## EXAMPLES
     *
     *     # Melihat pratinjau ekspor item menu
     *     $ wp menu-item:export --dry-run
     * 
     *     # Ekspor item menu All
     *     $ wp menu-item:export
     *
     *     # Ekspor item menu berdasarkan nama atau ID
     *     $ wp menu-item:export --filter=primary-menu
     *
     *     # Ekspor item menu dengan format JSON ke file
     *     $ wp menu-item:export --filter=main-menu --format=json --output=out.json
     *
     *
     * @param array $args Argumen posisional dari perintah CLI
     * @param array $assoc_args Argumen asosiatif (opsi) dari perintah CLI
     * 
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $io = new CliStyle();

        $dry_run = Utils\get_flag_value($assoc_args, 'dry-run');
        $output = Utils\get_flag_value($assoc_args, 'output');
        $format = Utils\get_flag_value($assoc_args, 'format', 'json');

        // validate output
        try {
            $filepath = Path::isAbsolute($output) ? $output : Path::join(getcwd() ?? '', $output);
            FileValidator::validate($filepath)
                ->mustBeJson();
            $directory = Path::getDirectory($filepath);
            DirectoryValidator::validate($directory)
                ->mustExist()
                ->mustBeWritable();

            // Process data export 
            $this->processData();
            if ($dry_run) {
                $this->dryRun($io, $filepath);
                return;
            }
            $this->process($io, $filepath);
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    private function processData(): void {}

    private function dryRun(CliStyle $io, string $filepath): void {}
    private function process(CliStyle $io, string $filepath): void {}
}
