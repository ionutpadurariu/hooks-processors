<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

final class DataProcessorDTO
{
    /**
     * @param array{item_operation_name?: ?string, collection_operation_name?: ?string, graphql_operation_name?: ?string}|array<string,mixed> $context
     */
    public function __construct(
        private readonly object $data,
        private readonly Operation $operation,
        private readonly array $context,
    ) {
    }

    public function getData(): object
    {
        return $this->data;
    }

    public function isCreate(): bool
    {
        return $this->operation instanceof Post
            || $this->operation instanceof Mutation;
    }

    public function isUpdate(): bool
    {
        return $this->operation instanceof Patch
            || $this->operation instanceof Put
            || $this->operation instanceof Mutation
        ;
    }

    public function isRemove(): bool
    {
        return $this->operation instanceof Delete
            || $this->operation instanceof DeleteMutation;
    }

    /**
     * @return array{item_operation_name?: ?string, collection_operation_name?: ?string, graphql_operation_name?: ?string, previous_data?: mixed}|array<string,mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }
}
