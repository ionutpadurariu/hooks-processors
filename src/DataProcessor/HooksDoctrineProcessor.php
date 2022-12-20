<?php

namespace HooksProcessors\DataProcessor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Metadata\DeleteOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use InvalidArgumentException;
use Traversable;
use function gettype;
use function is_object;
use function iterator_to_array;
use function sprintf;

final class HooksDoctrineProcessor implements ProcessorInterface
{
    /**
     * @var HooksProcessorBeforeCreate[]
     */
    protected array $handlersBeforeCreate;

    /**
     * @var HooksProcessorAfterCreate[]
     */
    protected array $handlersAfterCreate;

    /**
     * @var HooksProcessorBeforeUpdate[]
     */
    protected array $handlersBeforeUpdate;
    /**
     * @var HooksProcessorAfterUpdate[]
     */
    protected array $handlersAfterUpdate;
    /**
     * @var HooksProcessorBeforeRemove[]
     */
    protected array $handlersBeforeRemove;
    /**
     * @var HooksProcessorAfterRemove[]
     */
    protected array $handlersAfterRemove;

    /**
     * @param Traversable<int|string, HooksProcessorBeforeCreate> $handlersBeforeCreate
     * @param Traversable<int|string, HooksProcessorAfterCreate>  $handlersAfterCreate
     * @param Traversable<int|string, HooksProcessorBeforeUpdate> $handlersBeforeUpdate
     * @param Traversable<int|string, HooksProcessorAfterUpdate>  $handlersAfterUpdate
     * @param Traversable<int|string, HooksProcessorBeforeRemove> $handlersBeforeRemove
     * @param Traversable<int|string, HooksProcessorAfterRemove>  $handlersAfterRemove
     */
    public function __construct(
        private readonly ProcessorInterface $persistProcessor,
        private readonly ProcessorInterface $removeProcessor,
        Traversable                       $handlersBeforeCreate,
        Traversable                       $handlersAfterCreate,
        Traversable                       $handlersBeforeUpdate,
        Traversable                       $handlersAfterUpdate,
        Traversable                       $handlersBeforeRemove,
        Traversable                       $handlersAfterRemove,
    ) {
        $this->handlersBeforeCreate = iterator_to_array($handlersBeforeCreate);
        $this->handlersAfterCreate = iterator_to_array($handlersAfterCreate);
        $this->handlersBeforeUpdate = iterator_to_array($handlersBeforeUpdate);
        $this->handlersAfterUpdate = iterator_to_array($handlersAfterUpdate);
        $this->handlersBeforeRemove = iterator_to_array($handlersBeforeRemove);
        $this->handlersAfterRemove = iterator_to_array($handlersAfterRemove);
    }

    /**
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if (! is_object($data)) {
            throw new InvalidArgumentException(sprintf('expected object, got "%s"', gettype($data)));
        }

        if ($operation instanceof DeleteOperationInterface) {
            $this->remove($data, $operation, $uriVariables, $context);
            return;
        }

        $dto = new DataProcessorDTO($data, $operation, $context);
        $this->beforeCreate($dto);
        $this->beforeUpdate($dto);
        $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        $this->afterCreate($dto);
        $this->afterUpdate($dto);
    }

    /**
     * @param array<string, mixed> $uriVariables
     * @param array<mixed> $context
     */
    private function remove(object $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $dto = new DataProcessorDTO($data, $operation, $context);
        $this->beforeRemove($dto);
        $this->removeProcessor->process($data, $operation, $uriVariables, $context);
        $this->afterRemove($dto);
    }

    private function beforeCreate(DataProcessorDTO $dto): void
    {
        if (! $dto->isCreate()) {
            return;
        }

        foreach ($this->handlersBeforeCreate as $beforeCreate) {
            if ($beforeCreate->supports($dto)) {
                $beforeCreate->beforeCreate($dto);
            }
        }
    }

    private function afterCreate(DataProcessorDTO $dto): void
    {
        if (! $dto->isCreate()) {
            return;
        }

        foreach ($this->handlersAfterCreate as $afterCreate) {
            if ($afterCreate->supports($dto)) {
                $afterCreate->afterCreate($dto);
            }
        }
    }

    private function beforeUpdate(DataProcessorDTO $dto): void
    {
        if (! $dto->isUpdate()) {
            return;
        }

        foreach ($this->handlersBeforeUpdate as $beforeUpdate) {
            if ($beforeUpdate->supports($dto)) {
                $beforeUpdate->beforeUpdate($dto);
            }
        }
    }

    private function afterUpdate(DataProcessorDTO $dto): void
    {
        if (! $dto->isUpdate()) {
            return;
        }

        foreach ($this->handlersAfterUpdate as $afterUpdate) {
            if ($afterUpdate->supports($dto)) {
                $afterUpdate->afterUpdate($dto);
            }
        }
    }

    private function beforeRemove(DataProcessorDTO $dto): void
    {
        if (! $dto->isRemove()) {
            return;
        }

        foreach ($this->handlersBeforeRemove as $beforeRemove) {
            if ($beforeRemove->supports($dto)) {
                $beforeRemove->beforeRemove($dto);
            }
        }
    }

    private function afterRemove(DataProcessorDTO $dto): void
    {
        if (! $dto->isRemove()) {
            return;
        }

        foreach ($this->handlersAfterRemove as $afterRemove) {
            if ($afterRemove->supports($dto)) {
                $afterRemove->afterRemove($dto);
            }
        }
    }
}
