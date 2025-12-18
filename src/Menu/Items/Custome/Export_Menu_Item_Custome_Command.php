<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items\Custome;

use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliModels\Formatters\JsonFormatter;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\UI\Components\DryRunPresetExport;
use Vigihdev\WpCliModels\Validators\DirectoryValidator;
use Vigihdev\WpCliModels\Validators\FileValidator;
use WP_CLI\Utils;

final class Export_Menu_Item_Custome_Command extends Menu_Base_Command
{
    private const TAXONOMY = 'menu_item';
    public function __construct()
    {
        parent::__construct(name: 'menu-item-custome:export');
    }

    /**
     * Mengekspor item menu kustom
     * 
     * ## DESCRIPTION
     * 
     * Mengekspor item menu kustom ke dalam format JSON.
     * 
     * ## OPTIONS
     * 
     * --output=<output>
     * : File path untuk menyimpan output JSON.
     * --
     * required=true
     * 
     * [--limit=<limit>]
     * : Jumlah maksimum item menu kustom yang akan diekspor. Default adalah 100.
     * --
     * default=100
     * 
     * [--offset=<offset>]
     * : Offset item menu kustom yang akan diekspor. Default adalah 0.
     * --
     * default=0
     * 
     * [--fields=<fields>]
     * : Daftar field item menu kustom yang akan diekspor. Default adalah menu_item_id,menu_item_type,title,url,target,attr_title,classes,xfn.
     * --
     * default=menu_item_id,menu_item_type,title,url,target,attr_title,classes,xfn
     * 
     * [--format=<format>]
     * : Format output. Default adalah json.
     * --
     * default=json
     * 
     * [--dry-run]
     * : Jalankan perintah tanpa menyimpan output.
     * --
     * default=false
     * 
     * ## EXAMPLES
     * 
     *   # Mengekspor item menu kustom format json
     *   $ wp menu-item-custome:export --output=test.json --format=json --limit=100 --offset=0 --fields=menu_item_id,menu_item_type,title,url,target,attr_title,classes,xfn
     *   
     *   # Mengekspor item menu kustom format json
     *   $ wp menu-item-custome:export --output=directory/test.json
     * 
     * @param array $args
     * @param array $assoc_args
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $output = Utils\get_flag_value($assoc_args, 'output', '');
        $dry_run = Utils\get_flag_value($assoc_args, 'dry-run', false);
        $format = Utils\get_flag_value($assoc_args, 'format', 'json');
        $limit = Utils\get_flag_value($assoc_args, 'limit', 100);
        $offset = Utils\get_flag_value($assoc_args, 'offset', 0);
        $fields = Utils\get_flag_value($assoc_args, 'fields', 'menu_item_id,menu_item_type,title,url,target,attr_title,classes,xfn');

        $filepath = Path::isAbsolute($output) ? $output : Path::join(getcwd() ?? '', $output);

        // $this->dryRun($io, $filepath);
        // die();
        if ($dry_run) {
            $this->dryRun($io, $filepath);
            return;
        }

        try {
            FileValidator::validate($filepath)->mustBeJson();

            $directory = Path::getDirectory($filepath);
            if (! is_dir($directory)) {
                $directory = Path::getDirectory($directory);
            }
            DirectoryValidator::validate($directory)->mustExist()
                ->mustBeWritable();
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    protected function dryRun(CliStyle $io, string $output): void
    {

        $menuItems = $this->getMenuItemDto('test menu');
        $format = new JsonFormatter(
            items: $menuItems->map(fn($item) => $item->toArray())->toArray(),
            fields: ['title', 'url', 'type'],
        );
        $items = [];
        $fields = ['No', 'Title', 'Url', 'Type'];
        foreach ($menuItems->getIterator() as $i => $item) {
            $items[] = [$i + 1, $item->getTitle(), $item->getUrl(), $item->getType(),];
        }

        $dryRun = $io->renderDryRunPresetExport(
            format: 'json',
            name: 'Menu Item Type Custom',
            total: $menuItems->count(),
            output: $output,
        );

        $dryRun->renderTitle();
        $dryRun->renderLineInfo("Size: " . (strlen($format->display()) / 1000) . " KB");
        $io->newLine();
        $dryRun->renderTable($items, $fields);
        $dryRun->renderSummary([
            'Total items' => $menuItems->count(),
            'Output file' => basename($output),
            'Mode'        => 'DRY RUN',
        ]);
        $io->newLine();
        $dryRun->renderFooter();
    }

    private function processExport(CliStyle $io): void {}

    private function process(CliStyle $io): void {}
}
