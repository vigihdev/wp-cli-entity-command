<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\Post;

use Symfony\Component\Filesystem\Path;
use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\Post_Base_Command;
use Vigihdev\WpCliModels\Entities\PostEntity;
use Vigihdev\WpCliModels\DTOs\Entities\Post\PostEntityDto;
use Vigihdev\WpCliModels\Formatters\JsonFormatter;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\UI\Components\DryRunPresetExport;
use Vigihdev\WpCliModels\UI\Components\FileInfoPreset;
use Vigihdev\WpCliModels\UI\Components\ProcessExportPreset;
use WP_CLI\Utils;

final class Export_Post_Command extends Post_Base_Command
{

    /**
     * @var Collection<PostEntityDto> $collection
     */
    private ?Collection $collection = null;
    public function __construct()
    {
        parent::__construct(name: 'post:export');
    }

    /**
     * Export post with specified fields
     *
     * ## OPTIONS
     *
     * [--limit=<limit>]
     * : Number of posts to export
     * ---
     * default: 50
     * ---
     *
     * [--offset=<offset>]
     * : Number of posts to skip
     * ---
     * default: 0
     * ---
     *
     * [--output=<output>]
     * : Write to file instead of STDOUT
     * ---
     * required: true
     * ---
     *
     * [--fields=<fields>]
     * : Comma-separated list of fields to export
     * ---
     * default: ID,post_title,post_content
     * ---
     * 
     * [--dry-run]
     * : Show preview of export without writing to file
     * 
     * [--format=<format>]
     * : Format of export file (json, txt, yaml)
     * ---
     * default: json
     * ---
     * 
     * ## EXAMPLES
     *      
     *      # Export post with ID 123 to JSON file
     *      wp post:export --format=json --fields=ID,post_title,post_content --dry-run
     * 
     *      # Export 20 posts to JSON file with specified fields
     *      wp post:export --limit=20 --offset=0 --output=post-123.json --fields=ID,post_title,post_content
     * 
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $limit = (int) Utils\get_flag_value($assoc_args, 'limit', 50);
        $offset = (int) Utils\get_flag_value($assoc_args, 'offset', 0);
        $output = Utils\get_flag_value($assoc_args, 'output', null);
        $fields = Utils\get_flag_value($assoc_args, 'fields', 'ID,post_title,post_content');
        $dryRun = Utils\get_flag_value($assoc_args, 'dry-run', false);
        $format = Utils\get_flag_value($assoc_args, 'format', 'json');

        if (!$dryRun && $output === null) {
            $io->errorWithIcon('Output file is required');
        }

        $this->collection = PostEntity::filter($limit, $offset);

        if ($dryRun) {
            $this->dryRunProcess($io, $limit, $format, $output);
            return;
        }

        $fields = explode(',', $fields);
        $this->preProcess($io, $limit, $format, $output, $fields);
    }

    private function preProcess(CliStyle $io, int $limit, string $format, string $output, array $fields)
    {

        $process = new ProcessExportPreset(
            io: $io,
            name: 'Post',
            total: $limit,
            output: $output,
            format: $format,
            startTime: microtime(true)
        );
        $process->startRender();
        if ($process->getSuccessAsk()) {
            $this->exportProcess($process, $io, $fields, $output, $format);
        }
    }

    private function process() {}
    private function exportProcess(ProcessExportPreset $process, CliStyle $io, array $fields, string $output, string $format)
    {
        $output = Path::isAbsolute($output) ? $output : Path::join(getcwd() ?? '', $output);
        $data = $this->collection->map(fn($dto) => $dto->toArray())->toArray();
        $model = new JsonFormatter($data, $fields, $output);
        if ($model->save()) {

            $io->log("");
            $io->hr('-', 75);
            $io->success(
                sprintf("{$io->textGreen('Exported posts data berhasil')} %s detik", $io->highlightText($process->countingInSeconds()))
            );
            $io->hr('-', 75);

            $fileInfo = new FileInfoPreset(io: $io, filepath: $output);
            $fileInfo->renderList();
            $io->log("");
        }
    }

    private function dryRunProcess(CliStyle $io, int $limit, string $format, string $output = null)
    {

        $dryRun = new DryRunPresetExport(
            io: $io,
            name: 'Post',
            total: $this->collection->count(),
            output: $output,
            format: $format
        );

        $items = [];
        foreach ($this->collection->getIterator() as $index => $post) {
            $items[] = [
                $index + 1,
                $post->getId(),
                $post->getTitle(),
            ];
        }
        $dryRun->renderCompact($items, ['No', 'ID', 'title']);
    }
}
