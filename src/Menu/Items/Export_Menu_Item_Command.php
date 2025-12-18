<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items;

use Vigihdev\WpCliEntityCommand\WP_CLI\Menu_Base_Command;
use WP_CLI;
use WP_CLI\Utils;
use WP_CLI_Command;

final class Export_Menu_Item_Command extends Menu_Base_Command
{

    private $fields = [
        'title',
        'url',
        'type',
    ];

    public function __construct()
    {
        return parent::__construct('menu-item:export');
    }

    /**
     * Mengekspor item menu berdasarkan kriteria tertentu
     *
     * Perintah ini digunakan untuk mengekspor item menu dengan berbagai atribut seperti
     * tipe, label, judul, url, dan item anak. Hasil ekspor dapat disimpan dalam format
     * yang sesuai untuk keperluan backup atau migrasi.
     *
     * ## OPTIONS
     *
     * [<name-id>]
     * : Nama atau ID menu yang akan diekspor
     *
     * [--fields=<fields>]
     * : Menentukan fields yang akan diekspor
     * ---
     * default: type,label,title,url,items
     * options:
     *   - type
     *   - label
     *   - title
     *   - url
     *   - items
     *
     * [--format=<format>]
     * : Format output ekspor
     * ---
     * default: table
     * options:
     *   - table
     *   - json
     *   - csv
     *
     * [--dry-run]
     * : Menampilkan apa yang akan diekspor tanpa benar-benar melakukan ekspor
     *
     * [--out=<file>]
     * : Menyimpan hasil ekspor ke file yang ditentukan
     *
     * ## EXAMPLES
     *
     *     # Melihat pratinjau ekspor item menu
     *     $ wp menu-item:export --dry-run
     *
     *     # Ekspor item menu berdasarkan nama atau ID
     *     $ wp menu-item:export primary-menu
     *
     *     # Ekspor item menu dengan format JSON ke file
     *     $ wp menu-item:export main-menu --format=json --out=out.json
     *
     *     # Ekspor item menu dengan format CSV ke file
     *     $ wp menu-item:export footer-menu --format=csv --out=menu-items.csv
     *
     * @param array $args Argumen posisional dari perintah CLI
     * @param array $assoc_args Argumen asosiatif (opsi) dari perintah CLI
     * 
     * @return void
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $menu_name = isset($args[0]) ? $args[0] : null;
        $is_dry_run = Utils\get_flag_value($assoc_args, 'dry-run');
        $output_file = Utils\get_flag_value($assoc_args, 'out');
        $format = Utils\get_flag_value($assoc_args, 'format', 'table');
    }

    private function display(string $format, array $assoc_args): void {}
    private function process(string $out, array $assoc_args): void {}
}
