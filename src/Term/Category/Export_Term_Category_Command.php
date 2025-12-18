<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Term\Category;

use Symfony\Component\Filesystem\Path;
use Vigihdev\WpCliEntityCommand\WP_CLI\Term_Base_Command;
use Vigihdev\WpCliModels\Enums\Taxonomy;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\DirectoryValidator;
use Vigihdev\WpCliModels\Validators\FileValidator;
use WP_CLI\Utils;

final class Export_Term_Category_Command extends Term_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'term-category:export');
    }

    /**
     * Mengekspor kategori term
     * 
     * ## DESCRIPTION
     * 
     * Mengekspor kategori term ke dalam format JSON.
     * 
     * ## OPTIONS
     * 
     * --output=<output>
     * : File path untuk menyimpan output JSON.
     * --
     * required=true
     * 
     * [--limit=<limit>]
     * : Jumlah maksimum term yang akan diekspor. Default adalah 100.
     * --
     * default=100
     * 
     * [--format=<format>]
     * : Format output. Default adalah json.
     * --
     * default=json
     * 
     * [--offset=<offset>]
     * : Offset term yang akan diekspor. Default adalah 0.
     * --
     * default=0
     * 
     * [--fields=<fields>]
     * : Daftar field term yang akan diekspor. Default adalah term_id,parent,name,slug,description,taxonomy.
     * --
     * default=term_id,parent,name,slug,description,taxonomy
     * 
     * [--dry-run]
     * : Jalankan perintah tanpa menyimpan output.
     * --
     * default=false
     * 
     * ## EXAMPLES
     * 
     *   # Mengekspor default kategori term format json
     *   wp term-category:export --output=categories.json
     * 
     *   # Mengekspor 100 kategori term pertama
     *   wp term-category:export --output=categories.json --limit=100 --offset=0 --fields=term_id,parent,name,slug,description,taxonomy --format=json
     * 
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $output = Utils\get_flag_value($assoc_args, 'output', '');
        $limit = Utils\get_flag_value($assoc_args, 'limit', 100);
        $offset = Utils\get_flag_value($assoc_args, 'offset', 0);
        $fields = Utils\get_flag_value($assoc_args, 'fields', 'term_id,parent,name,slug,description,taxonomy');
        $fields = explode(',', $fields);
        $format = Utils\get_flag_value($assoc_args, 'format', 'json');

        // array_column(Taxonomy::CATEGORY->cases(), 'value');
        $filepath = Path::isAbsolute($output) ? $output : Path::join(getcwd() ?? '', $output);
        try {
            FileValidator::validate($filepath)->mustBeJson();
            $dir = Path::getDirectory($filepath);
            DirectoryValidator::validate($dir)->mustExist();
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    private function preProcess(CliStyle $io): void {}

    /**
     * @param CliStyle $io
     * @param array $terms
     */
    private function process(CliStyle $io, array $terms): void {}
}
