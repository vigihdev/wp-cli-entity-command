<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\User;

use Vigihdev\WpCliEntityCommand\WP_CLI\User_Base_Command;
use Vigihdev\WpCliModels\Entities\UserEntity;
use Vigihdev\WpCliModels\UI\CliStyle;
use Vigihdev\WpCliModels\Validators\UserValidator;
use WP_CLI\Utils;

final class Delete_User_Command extends User_Base_Command
{

    public function __construct()
    {
        parent::__construct(name: 'user:delete');
    }

    /**
     * Delete a user by ID.
     *
     * ## OPTIONS
     *
     * <user-id>
     * : ID user yang akan dihapus.
     * required: true
     *
     * [--dry-run]
     * : Menjalankan simulasi penghapusan user tanpa benar-benar menghapus data.
     * default: false
     * --
     * 
     * ## EXAMPLES
     *
     *     # Delete a user by ID
     *     $ wp user:delete 123
     *
     * @param array $args
     * @param array $assoc_args
     */
    public function __invoke(array $args, array $assoc_args): void
    {
        $io = new CliStyle();
        $id = (int)($args[0] ?? 0);
        $dry_run = Utils\get_flag_value($assoc_args, 'dry-run', false);

        // Process delete user
        try {
            UserValidator::validateUser($id)
                ->mustExist()
                ->mustNotBeSuperAdmin();

            if ($dry_run) {
                $this->dryRun($io, $id);
                return;
            }
            $this->process($io, $id);
        } catch (\Throwable $e) {
            $this->exceptionHandler->handle($io, $e);
        }
    }


    /**
     * Menjalankan simulasi penghapusan user tanpa benar-benar menghapus data
     * 
     * @param CliStyle $io Objek untuk menangani input/output CLI
     * @param int $id ID user yang akan dihapus
     * @return void
     */
    private function dryRun(CliStyle $io, int $id): void
    {
        $user = UserEntity::get($id);
        $dryRun = $io->renderDryRunPreset(sprintf('User delete %d', $id));
        $dryRun
            ->addInfo(
                'User akan dihapus secara permanen dari database.'
            )
            ->addDefinition([
                'ID' => $user->getId(),
                'Email' => $user->getEmail(),
                'Nickname' => $user->getNicename(),
                'Role' => implode(', ', $user->getRoles()),
            ])
            ->render();
    }

    private function process(CliStyle $io, int $id): void
    {

        $user = UserEntity::get($id);
        $deleted = UserEntity::delete($id);

        if (!$deleted) {
            $io->renderBlock("User ID {$user->getId()} failed to delete.")->error();
            return;
        }
        $io->renderBlock("User ID {$user->getId()} deleted successfully.")->success();
    }
}
