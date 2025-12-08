<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Menu\Items;

use WP_CLI;
use WP_CLI\Utils;
use WP_CLI_Command;

final class Export_Menu_Item_Command extends WP_CLI_Command
{

    private $fields = [
        'title',
        'url',
        'tipe',
    ];

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
     * default: all
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
        $is_dry_run = Utils\get_flag_value($assoc_args, 'dry-run');
        $output_file = Utils\get_flag_value($assoc_args, 'out');
        $format = Utils\get_flag_value($assoc_args, 'format', 'table');

        // Cek Menu name Or ID

        // Dry run process
        if ($is_dry_run) {
            WP_CLI::line('DRY RUN: Berikut adalah data yang akan diekspor:');
            WP_CLI::line('');
            return;
        }

        // Cek Out directory

        WP_CLI::success("Data berhasil diekspor ke file '{$output_file}'");
        WP_CLI::success('Ekspor menu item berhasil dilakukan');
    }

    private function display(string $format, array $assoc_args): void {}
    private function process(string $out, array $assoc_args): void {}
}
