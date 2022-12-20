<?php

namespace Unit\HooksProcessors\DataProcessor;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use HooksProcessors\DataProcessor\DataProcessorDTO;
use PHPUnit\Framework\TestCase;
use stdClass;

final class DataProcessorDTOTest extends TestCase
{
    public function testIsRemove(): void
    {
        $dataProcessorDTO = new DataProcessorDTO(
            new stdClass(),
            new Delete(),
            []
        );
        self::assertTrue($dataProcessorDTO->isRemove());

        $dataProcessorDTO = new DataProcessorDTO(
            new stdClass(),
            new DeleteMutation(),
            []
        );
        self::assertTrue($dataProcessorDTO->isRemove());

        $dataProcessorDTO = new DataProcessorDTO(
            new stdClass(),
            new Post(),
            []
        );
        self::assertFalse($dataProcessorDTO->isRemove());
    }

    public function testGetData(): void
    {
        $data = new stdClass();
        $dataProcessorDTO = new DataProcessorDTO(
            $data,
            new Delete(),
            []
        );
        self::assertSame($data, $dataProcessorDTO->getData());
    }

    public function testGetContext(): void
    {
        $context = ['context-key' => 'context-value'];
        $dataProcessorDTO = new DataProcessorDTO(
            new stdClass(),
            new Delete(),
            $context
        );
        self::assertSame($context, $dataProcessorDTO->getContext());
    }

    public function testIsCreate(): void
    {
        $dataProcessorDTO = new DataProcessorDTO(
            new stdClass(),
            new Post(),
            []
        );
        self::assertTrue($dataProcessorDTO->isCreate());

        $dataProcessorDTO = new DataProcessorDTO(
            new stdClass(),
            new Mutation(),
            []
        );
        self::assertTrue($dataProcessorDTO->isCreate());
    }

    public function testIsUpdate(): void
    {
        $dataProcessorDTO = new DataProcessorDTO(
            new stdClass(),
            new Patch(),
            []
        );
        self::assertTrue($dataProcessorDTO->isUpdate());

        $dataProcessorDTO = new DataProcessorDTO(
            new stdClass(),
            new Put(),
            []
        );
        self::assertTrue($dataProcessorDTO->isUpdate());

        $dataProcessorDTO = new DataProcessorDTO(
            new stdClass(),
            new Mutation(),
            []
        );
        self::assertTrue($dataProcessorDTO->isUpdate());
    }
}
