<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\User;

use Vigihdev\WpCliEntityCommand\WP_CLI\User_Base_Command;
use Vigihdev\WpCliModels\Entities\UserEntity;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\UserValidator;
use WP_CLI\Utils;

final class Get_User_Command extends User_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'user:get');
    }

    /**
     * Get a user by ID Or email.
     *
     * ## OPTIONS
     *
     * <user-id-email>
     * : The ID or email of the user to get.
     *
     * [--fields=<fields>]
     * : Bataskan output ke field tertentu.
     * default: id,email,username,role
     * options: 
     *  - id
     *  - email
     *  - username
     *  - role
     * ---
     * 
     * ## EXAMPLES
     *
     *     # Get a user by ID
     *     $ wp user:get 123
     *
     *     # Get a user by email
     *     $ wp user:get user@example.com
     *
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {

        $user = isset($args[0]) ? (int)$args[0] : null;
        $this->fields = Utils\get_flag_value($assoc_args, 'fields', 'id,email,username,role');
        $this->instanceFields();
        $io = new CliStyle();

        // Validate user
        try {
            UserValidator::validateUser($user)
                ->mustExist();
            $this->process($io, $user);
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    /**
     * Process the user data and display it in a table.
     *
     * @param CliStyle $io
     * @param int $id
     * @return void
     */
    private function process(CliStyle $io, int $id): void
    {
        $user = UserEntity::get($id);
        $io->title("📊 User Details", '%C');
        $io->table([
            ['ID', $user->getId()],
            ['Email', $user->getEmail()],
            ['Username', $user->getUsername()],
            ['Role', implode(', ', $user->getRoles())],
            ['First Name', $user->getFirstName()],
            ['Last Name', $user->getLastName()],
        ], ['Field', 'Value']);

        $io->renderPaginationPreset('user', 1, UserEntity::count())->render();
    }
}
