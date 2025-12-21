<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\User;

use Vigihdev\WpCliEntityCommand\WP_CLI\User_Base_Command;
use Vigihdev\WpCliModels\Entities\UserEntity;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\UserValidator;

final class Update_User_Command extends User_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'user:update');
    }

    /**
     * Update a user by ID.
     *
     * ## OPTIONS
     *
     * <user-id>
     * : The ID of the user to update.
     * required: true
     * --
     * 
     * [--user_pass=<password>]
     * : The new password for the user.
     * --
     * 
     * [--user_nicename=<nice_name>] 
     * : The new nice name for the user.
     * --
     * 
     * [--user_url=<url>]
     * : The new URL for the user.
     * --
     * 
     * [--user_email=<email>] 
     * : The new email for the user.
     * --
     * 
     * [--display_name=<display_name>] 
     * : The new display name for the user.
     * --
     * 
     * [--nickname=<nickname>] 
     * : The new nickname for the user.
     * --
     * 
     * [--first_name=<first_name>] 
     * : The new first name for the user.
     * --
     * 
     * [--last_name=<last_name>] 
     * : The new last name for the user.
     * --
     * 
     * [--description=<description>] 
     * : The new description for the user.
     * --
     * 
     * [--rich_editing=<rich_editing>] 
     * : The new rich editing for the user.
     * --
     * 
     * [--user_registered=<yyyy-mm-dd-hh-ii-ss>] 
     * : The new user registered for the user.
     * --
     * 
     * [--role=<role>] 
     * : The new role for the user.
     * --
     * 
     * [--skip-email]
     * : Skip sending the email notification.
     * --
     * 
     * ## EXAMPLES
     *
     *     # Update a user by ID
     *     $ wp user:update 123 --email=user@example.com
     *
     *     # Update a user by email
     *     $ wp user:update user@example.com --email=user2@example.com
     *
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $user = isset($args[0]) ? (int)$args[0] : null;
        $io = new CliStyle();

        try {
            UserValidator::validateUpdate($user, $assoc_args)
                ->forUpdate();
            $this->process($io, $user, $assoc_args);
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }

    /**
     * Process the user update.
     *
     * @param CliStyle $io The CLI style instance.
     * @param int $id The user ID to update.
     * @return void
     */
    private function process(CliStyle $io, int $id, array $assoc_args): void
    {
        $user = UserEntity::get($id);
        $userData = array_merge($user->toArray(), $assoc_args);
        $updated = UserEntity::update($userData);

        if (is_wp_error($updated)) {
            $io->renderBlock($updated->get_error_message())->error();
            return;
        }
        $io->renderBlock("User ID {$user->getId()} updated successfully.")->success();
    }
}
