<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\User;

use Symfony\Component\Filesystem\Path;
use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\User_Base_Command;
use Vigihdev\WpCliModels\DTOs\Entities\Author\UserEntityDto;
use Vigihdev\WpCliModels\Entities\UserEntity;
use Vigihdev\WpCliModels\Formatters\JsonFormatter;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\DirectoryValidator;
use Vigihdev\WpCliModels\Validators\FileValidator;
use WP_CLI\Utils;

final class Export_User_Command extends User_Base_Command
{

    /**
     * @var Collection<UserEntityDto> Collection of user entities.
     */
    private Collection $userCollection;

    private string $format;

    private array $dataExport = [];

    public function __construct()
    {
        parent::__construct(name: 'user:export');
    }


    /**
     * Export a user store in a file.
     *
     * ## OPTIONS
     * 
     * --output=<output>
     * : File output untuk menyimpan export.
     * required: true
     * ---
     * 
     * [--limit=<limit>]
     * : Batasi jumlah user yang diekspor.
     * default: 100
     * ---
     * 
     * [--offset=<offset>]
     * : Offset user yang diekspor.
     * default: 0
     * ---
     * 
     * [--fields=<fields>]
     * : Bataskan output ke field tertentu.
     * ---
     * default: id,email,username,role
     * options: 
     *  - id
     *  - email
     *  - username
     *  - role
     * ---
     * 
     * [--format=<format>]
     * : Format output file.
     * default: json
     * options: 
     *  - json
     * ---
     * 
     * [--dry-run]
     * : Menjalankan simulasi export user tanpa benar-benar mengekspor data.
     * default: false
     * ---
     * 
     * ## EXAMPLES
     *
     *     # Export semua user ke file JSON
     *     $ wp user:export --output=json --format=json
     *
     *     # Export user by email to a JSON file
     *     $ wp user:export --output=json --fields=id,email,username
     *
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $output = Utils\get_flag_value($assoc_args, 'output', '');
        $this->format = Utils\get_flag_value($assoc_args, 'format', 'json');
        $this->fields = Utils\get_flag_value($assoc_args, 'fields', 'id,email,username,role');
        $this->instanceFields();

        $dry_run = Utils\get_flag_value($assoc_args, 'dry-run', false);
        $limit = (int)Utils\get_flag_value($assoc_args, 'limit', 100);
        $offset = (int)Utils\get_flag_value($assoc_args, 'offset', 0);

        $userCollection = UserEntity::find([
            'limit' => $limit,
            'offset' => $offset,
        ]);

        if ($userCollection->count() === 0) {
            $io->renderBlock('No user found.')->info();
            return;
        }

        $this->userCollection = $userCollection;

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

    private function processData(): void
    {
        $this->dataExport = $this->userCollection->map(fn($user) => $this->userField->transform($user->toArray()))->toArray();
    }

    private function dryRun(CliStyle $io, string $filepath): void
    {
        $user = $this->userCollection;
        $dryRun = $io->renderDryRunPreset('User Export');

        $dryRun->addInfo(
            $user->count() . ' user(s) will be exported.',
            "Output: " . $filepath,
            'Fields: ' . $this->fields,
        )
            ->addTable($this->dataExport)
            ->render();
    }

    private function process(CliStyle $io, string $filepath): void
    {

        $format = $this->format;
        switch ($format) {
            case 'json':
                $format = new JsonFormatter($this->dataExport, $this->userField->getAttributes(), $filepath);
                if ($format->save()) {
                    $io->renderBlock('User export to ' . $filepath . ' successfully.')->success();
                } else {
                    $io->renderBlock('User export to ' . $filepath . ' failed.')->error();
                }
                break;
            default:
                break;
        }
    }
}
