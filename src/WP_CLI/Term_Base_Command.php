<?php

declare(strict_types=1);

namespace Vigihdev\WpCliEntityCommand\WP_CLI;

use Vigihdev\Support\Collection;
use Vigihdev\WpCliModels\DTOs\Entities\Terms\TermEntityDto;
use Vigihdev\WpCliModels\Entities\TermEntity;
use Vigihdev\WpCliModels\Exceptions\Handler\{HandlerExceptionInterface, WpCliExceptionHandler};
use WP_CLI_Command;

abstract class Term_Base_Command extends WP_CLI_Command
{

    protected const DEFAULT_LIMIT = 20;
    protected int $limit = 0;
    protected int $offset = 0;
    protected ?string $filter = null;

    protected HandlerExceptionInterface $exceptionHandler;

    public function __construct(
        protected string $name
    ) {
        parent::__construct();
        $this->exceptionHandler = new WpCliExceptionHandler();
    }

    /**
     * Mendapatkan koleksi semua term
     *
     * @return Collection<TermEntityDto>
     */
    protected function getTermsCollection(): Collection
    {
        return TermEntity::findAll();
    }

    protected function getTermDto(string|int $term): ?TermEntityDto
    {
        if (is_numeric($term)) {
            $term = (int)$term;
            return TermEntity::getId($term);
        }

        $collection = TermEntity::getName($term);
        if ($collection->isEmpty()) {
            return TermEntity::getSlug($term)?->first();
        }
        return $collection->first();
    }
}
