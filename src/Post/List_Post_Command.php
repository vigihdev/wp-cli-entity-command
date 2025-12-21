<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\Post_Base_Command;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP_Post;

final class List_Post_Command extends Post_Base_Command
{


    private array $queryArgs = [];

    public function __construct()
    {
        parent::__construct(name: 'post:list');
    }

    /**
     * Menampilkan daftar post.
     *
     * ## OPTIONS
     * 
     * [--limit=<limit>]
     * : Jumlah post yang akan ditampilkan.
     * default: 20
     * ---
     *
     * [--offset=<offset>]
     * : Jumlah post yang akan dilewati.
     * default: 0
     * ---
     * [--fields=<fields>]
     * : Field yang akan ditampilkan.
     * default: id,title,type,status
     * ---
     *
     * [--post-type=<type>]
     * : Tipe post yang akan diambil.
     * default: post
     * ---
     *
     * [--status=<status>]
     * : Status post yang akan diambil.
     * default: publish
     * ---
     * 
     * [--orderby=<orderby>]
     * : Field yang akan digunakan untuk pengurutan.
     * default: date
     * ---
     *
     * [--order=<order>]
     * : Urutan pengurutan.
     * default: DESC
     * ---
     *
     * [--category=<category>]
     * : Kategori post yang akan diambil.
     * ---
     *
     * [--author=<author>]
     * : Penulis post yang akan diambil.
     * ---
     *
     * ## EXAMPLES
     *
     *  # Menampilkan 20 post pertama
     *  $ wp post:list
     * 
     *  # Menampilkan 20 post pertama dari offset 20
     *  $ wp post:list --limit=20 --offset=20
     *
     * @param array $args       Argumen posisional dari perintah CLI.
     * @param array $assoc_args Argumen asosiatif (flag) dari perintah CLI.
     */
    public function __invoke(array $args, array $assoc_args)
    {

        $io = new CliStyle();

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

        $this->queryArgs = $query_args;
        $posts = get_posts($this->queryArgs);

        if (!$posts || is_wp_error($posts) || empty($posts)) {
            $io->renderBlock("No posts found.")->warning();
            return;
        }

        $this->process(io: $io, collection: new Collection(data: $posts));
    }

    /**
     * Memproses data post dan menampilkan dalam bentuk tabel.
     *
     * @param int $limit      Jumlah post yang akan ditampilkan.
     * @param int $offset     Jumlah post yang akan dilewati.
     * @param Collection<WP_Post> $collection Koleksi data post.
     */
    private function process(CliStyle $io, Collection $collection)
    {

        $io->title('📊 List Post', '%C');

        $io->table(
            fields: ['No', 'ID', 'Title', 'Status'],
            items: $collection->map(function ($post, $i) {
                return [
                    $i + 1,
                    $post->ID,
                    $post->post_title,
                    $post->post_status,
                ];
            })->toArray(),
        );

        $io->newLine();

        $io->renderInlinePreset()
            ->add('Total', (string)$collection->count() . ' Post', '📁')
            ->add('Page', (string)($this->queryArgs['offset'] ?? 0) . '/1 (Limit: 15)', '📄')
            ->statistics();
    }
}
