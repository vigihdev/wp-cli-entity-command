<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\Post_Base_Command;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\UI\Components\ListPreset;
use WP_CLI;
use WP_CLI\Iterators\Query;
use WP_CLI_Command;
use WP_Post;
use WP_Query;

final class List_Post_Command extends Post_Base_Command
{

    public function __construct()
    {
        return parent::__construct(name: 'post:list');
    }

    /**
     * Menampilkan daftar post.
     *
     * ## OPTIONS
     * 
     * [--limit=<limit>]
     * : Jumlah post yang akan ditampilkan.
     * ---
     * default: 15
     * ---
     *
     * [--offset=<offset>]
     * : Jumlah post yang akan dilewati.
     * ---
     * default: 0
     * ---
     *
     * [--post-type=<type>]
     * : Tipe post yang akan diambil.
     * ---
     * default: post
     * ---
     *
     * [--status=<status>]
     * : Status post yang akan diambil.
     * ---
     * default: publish
     * ---
     *
     * ## EXAMPLES
     *
     *     wp post:list --limit=20 --offset=5
     *
     * @param array $args       Argumen posisional dari perintah CLI.
     * @param array $assoc_args Argumen asosiatif (flag) dari perintah CLI.
     */
    public function __invoke(array $args, array $assoc_args)
    {
        $io = new CliStyle();
        WP_CLI::success(
            sprintf('Execute basic command from %s', Get_Post_Command::class)
        );

        $query_args = [
            'post_type'        => $assoc_args['post-type'] ?? 'post',
            'post_status'      => $assoc_args['status'] ?? 'publish',
            'posts_per_page'   => (int) ($assoc_args['limit'] ?? 15),
            'offset'           => (int) ($assoc_args['offset'] ?? 0),
            'orderby'          => $assoc_args['orderby'] ?? 'date',
            'order'            => $assoc_args['order'] ?? 'DESC',
            'category__in'     => isset($assoc_args['category']) ? explode(',', $assoc_args['category']) : [],
            'author__in'       => isset($assoc_args['author']) ? explode(',', $assoc_args['author']) : [],
            'post__in'         => isset($assoc_args['include']) ? explode(',', $assoc_args['include']) : [],
            'post__not_in'     => isset($assoc_args['exclude']) ? explode(',', $assoc_args['exclude']) : [],
        ];
        $posts = get_posts($query_args);

        if (!$posts) {
            WP_CLI::warning('No posts found.');
            return;
        }


        $this->process(
            io: $io,
            limit: $assoc_args['limit'] ?? 15,
            offset: $assoc_args['offset'] ?? 0,
            collection: new Collection(data: $posts),
        );
    }

    /**
     * Memproses data post dan menampilkan dalam bentuk tabel.
     *
     * @param int $limit      Jumlah post yang akan ditampilkan.
     * @param int $offset     Jumlah post yang akan dilewati.
     * @param Collection<WP_Post[]> $collection Koleksi data post.
     */
    private function process(CliStyle $io, int $limit, int $offset, Collection $collection)
    {
        $io->table(
            fields: ['ID', 'Title', 'Status'],
            items: $collection->map(function ($post) {
                return [
                    $post->ID,
                    $post->post_title,
                    $post->post_status,
                ];
            })->toArray(),
        );
    }
}
