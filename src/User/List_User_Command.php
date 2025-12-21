<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\User;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliEntityCommand\WP_CLI\User_Base_Command;
use Vigihdev\WpCliModels\DTOs\Entities\Author\UserEntityDto;
use Vigihdev\WpCliModels\Entities\UserEntity;
use Vigihdev\WpCliModels\UI\CliStyle;
use WP_CLI\Utils;

final class List_User_Command extends User_Base_Command
{

    /**
     * @var Collection<UserEntityDto> $users
     */
    private ?Collection $users = null;

    public function __construct()
    {
        parent::__construct(name: 'user:list');
    }

    /**
     * List all users.
     *
     * ## OPTIONS
     * 
     * [--limit=<limit>]
     * : The number of users to list.
     * default: 50
     * ---
     * 
     * [--offset=<offset>]
     * : The offset to start listing users from.
     * default: 0
     * ---
     * 
     * [--fields=<fields>]  
     * : Limit the output to specific fields.
     * default: id,email,username,first_name,last_name,role
     * options:
     *   - id
     *   - email
     *   - username
     *   - first_name
     *   - last_name
     *   - role
     * ---
     * 
     * ## EXAMPLES
     *
     *     # List all users
     *     $ wp user:list
     *
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $io = new CliStyle();
        $limit = Utils\get_flag_value($assoc_args, 'limit', 50);
        $offset = Utils\get_flag_value($assoc_args, 'offset', 0);
        $this->fields = Utils\get_flag_value($assoc_args, 'fields', 'id,email,username,nickname,last_name,role');

        $this->users = UserEntity::find([
            'limit' => $limit,
            'offset' => $offset,
        ]);

        // Validate users
        if ($this->users?->isEmpty()) {
            $io->renderBlock('No users found.')->info();
            return;
        }

        $this->process($io);
    }

    public function process(CliStyle $io): void
    {

        $user = $this->users;
        $io->title("📊 List Users", '%C');

        $io->table(
            fields: ['No', 'ID', 'Email', 'Nickname'],
            items: $user->map(function ($item, $key) {
                return [
                    $key + 1,
                    $item->getId(),
                    $item->getEmail(),
                    $item->getNicename(),
                ];
            })->toArray(),
        );

        $io->renderPaginationPreset('user', $this->users?->count(), UserEntity::count())->render();
    }
}
