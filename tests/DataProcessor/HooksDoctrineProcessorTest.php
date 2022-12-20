<?php

namespace Tests\HooksProcessors\DataProcessor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\State\ProcessorInterface;
use Doctrine\Common\Collections\ArrayCollection;
use HooksProcessors\DataProcessor\HooksDoctrineProcessor;
use HooksProcessors\DataProcessor\HooksProcessorAfterCreate;
use HooksProcessors\DataProcessor\HooksProcessorAfterRemove;
use HooksProcessors\DataProcessor\HooksProcessorAfterUpdate;
use HooksProcessors\DataProcessor\HooksProcessorBeforeCreate;
use HooksProcessors\DataProcessor\HooksProcessorBeforeRemove;
use HooksProcessors\DataProcessor\HooksProcessorBeforeUpdate;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use stdClass;
use Traversable;

final class HooksDoctrineProcessorTest extends TestCase
{
    private ProcessorInterface&MockObject $persistProcessor;
    private ProcessorInterface&MockObject $removeProcessor;
    private HooksDoctrineProcessor $processor;

    /** @before  */
    public function setUpDependencies(): void
    {
        $this->persistProcessor = $this->createMock(ProcessorInterface::class);
        $this->removeProcessor = $this->createMock(ProcessorInterface::class);
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
        );
    }

    public function testProcessorPersistWasCalled(): void
    {
        $data = new stdClass();
        $operation = new Post();
        $this->persistProcessor
            ->expects(self::once())
            ->method('process');

        $this->removeProcessor
            ->expects(self::never())
            ->method('process');

        $this->processor->process($data, $operation);
    }

    public function testProcessorRemoveWasCalled(): void
    {
        $data = new stdClass();
        $operation = new Delete();
        $this->persistProcessor
            ->expects(self::never())
            ->method('process');

        $this->removeProcessor
            ->expects(self::once())
            ->method('process');

        $this->processor->process($data, $operation);
    }

    public function testProcessBeforeCreate(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            $this->generateBeforeCreateHandlers(1),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
        );
        $data = new stdClass();
        $operation = new Post();

        $this->processor->process($data, $operation);
    }

    public function testProcessBeforeCreateShouldNotBeCalled(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            $this->generateBeforeCreateHandlers(0),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
        );
        $data = new stdClass();
        $operation = new Patch();

        $this->processor->process($data, $operation);
    }

    public function testProcessAfterCreate(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            $this->generateAfterCreateHandlers(1),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
        );
        $data = new stdClass();
        $operation = new Post();

        $this->processor->process($data, $operation);
    }

    public function testProcessAfterCreateShouldNotBeCalled(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            $this->generateAfterCreateHandlers(0),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
        );
        $data = new stdClass();
        $operation = new Patch();

        $this->processor->process($data, $operation);
    }

    public function testProcessBeforeUpdate(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            new ArrayCollection(),
            $this->generateBeforeUpdateHandlers(1),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
        );

        $data = new stdClass();
        $operation = new Patch();

        $this->processor->process($data, $operation);
    }

    public function testProcessBeforeUpdateShouldNotBeCalled(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            new ArrayCollection(),
            $this->generateBeforeUpdateHandlers(0),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
        );

        $data = new stdClass();
        $operation = new Post();

        $this->processor->process($data, $operation);
    }

    public function testProcessAfterUpdate(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            $this->generateAfterUpdateHandlers(1),
            new ArrayCollection(),
            new ArrayCollection(),
        );
        $data = new stdClass();
        $operation = new Patch();

        $this->processor->process($data, $operation);
    }

    public function testProcessAfterUpdateShouldNotBeCalled(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            $this->generateAfterUpdateHandlers(0),
            new ArrayCollection(),
            new ArrayCollection(),
        );
        $data = new stdClass();
        $operation = new Post();

        $this->processor->process($data, $operation);
    }

    public function testProcessAfterRemove(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            $this->generateAfterRemoveHandlers(1),
        );
        $data = new stdClass();
        $operation = new Delete();

        $this->processor->process($data, $operation);
    }

    public function testProcessAfterRemoveShouldNotBeCalled(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            $this->generateAfterRemoveHandlers(0),
        );
        $data = new stdClass();
        $operation = new Post();

        $this->processor->process($data, $operation);
    }

    public function testBeforeRemove(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            $this->generateBeforeRemoveHandlers(1),
            new ArrayCollection(),
        );
        $data = new stdClass();
        $operation = new Delete();

        $this->processor->process($data, $operation);
    }

    public function testBeforeRemoveShouldNotBeCalled(): void
    {
        $this->processor = new HooksDoctrineProcessor(
            $this->persistProcessor,
            $this->removeProcessor,
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            new ArrayCollection(),
            $this->generateBeforeRemoveHandlers(0),
            new ArrayCollection(),
        );
        $data = new stdClass();
        $operation = new Post();

        $this->processor->process($data, $operation);
    }

    public function testPersistWithInvalidData(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->processor->process('invalid', new Post());
    }

    /** @return Traversable<int|string, HooksProcessorBeforeCreate> */
    private function generateBeforeCreateHandlers(int $times): Traversable
    {
        $handler = $this->createMock(HooksProcessorBeforeCreate::class);
        $handler->expects(self::exactly($times))->method('supports')->willReturn(false);
        $handler->expects(self::never())->method('beforeCreate');

        $handler1 = $this->createMock(HooksProcessorBeforeCreate::class);
        $handler1->expects(self::exactly($times))->method('supports')->willReturn(true);
        $handler1->expects(self::exactly($times))->method('beforeCreate');

        $collection = new ArrayCollection();
        $collection->add($handler);
        $collection->add($handler1);

        return $collection;
    }

    /** @return Traversable<int|string, HooksProcessorAfterCreate> */
    private function generateAfterCreateHandlers(int $times): Traversable
    {
        $handler = $this->createMock(HooksProcessorAfterCreate::class);
        $handler->expects(self::exactly($times))->method('supports')->willReturn(false);
        $handler->expects(self::never())->method('afterCreate');

        $handler1 = $this->createMock(HooksProcessorAfterCreate::class);
        $handler1->expects(self::exactly($times))->method('supports')->willReturn(true);
        $handler1->expects(self::exactly($times))->method('afterCreate');

        $collection = new ArrayCollection();
        $collection->add($handler);
        $collection->add($handler1);

        return $collection;
    }

    /** @return Traversable<int|string, HooksProcessorBeforeUpdate> */
    private function generateBeforeUpdateHandlers(int $times): Traversable
    {
        $handler = $this->createMock(HooksProcessorBeforeUpdate::class);
        $handler->expects(self::exactly($times))->method('supports')->willReturn(false);
        $handler->expects(self::never())->method('beforeUpdate');

        $handler1 = $this->createMock(HooksProcessorBeforeUpdate::class);
        $handler1->expects(self::exactly($times))->method('supports')->willReturn(true);
        $handler1->expects(self::exactly($times))->method('beforeUpdate');

        $collection = new ArrayCollection();
        $collection->add($handler);
        $collection->add($handler1);

        return $collection;
    }

    /** @return Traversable<int|string, HooksProcessorAfterUpdate> */
    private function generateAfterUpdateHandlers(int $times): Traversable
    {
        $handler = $this->createMock(HooksProcessorAfterUpdate::class);
        $handler->expects(self::exactly($times))->method('supports')->willReturn(false);
        $handler->expects(self::never())->method('afterUpdate');

        $handler1 = $this->createMock(HooksProcessorAfterUpdate::class);
        $handler1->expects(self::exactly($times))->method('supports')->willReturn(true);
        $handler1->expects(self::exactly($times))->method('afterUpdate');

        $collection = new ArrayCollection();
        $collection->add($handler);
        $collection->add($handler1);

        return $collection;
    }

    /** @return Traversable<int|string, HooksProcessorBeforeRemove> */
    private function generateBeforeRemoveHandlers(int $times): Traversable
    {
        $handler = $this->createMock(HooksProcessorBeforeRemove::class);
        $handler->expects(self::exactly($times))->method('supports')->willReturn(false);
        $handler->expects(self::never())->method('beforeRemove');

        $handler1 = $this->createMock(HooksProcessorBeforeRemove::class);
        $handler1->expects(self::exactly($times))->method('supports')->willReturn(true);
        $handler1->expects(self::exactly($times))->method('beforeRemove');

        $collection = new ArrayCollection();
        $collection->add($handler);
        $collection->add($handler1);

        return $collection;
    }

    /** @return Traversable<int|string, HooksProcessorAfterRemove> */
    private function generateAfterRemoveHandlers(int $times): Traversable
    {
        $handler = $this->createMock(HooksProcessorAfterRemove::class);
        $handler->expects(self::exactly($times))->method('supports')->willReturn(false);
        $handler->expects(self::never())->method('afterRemove');

        $handler1 = $this->createMock(HooksProcessorAfterRemove::class);
        $handler1->expects(self::exactly($times))->method('supports')->willReturn(true);
        $handler1->expects(self::exactly($times))->method('afterRemove');

        $collection = new ArrayCollection();
        $collection->add($handler);
        $collection->add($handler1);

        return $collection;
    }
}
