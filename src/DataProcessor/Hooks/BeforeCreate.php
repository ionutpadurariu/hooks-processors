<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor\Hooks;

use HooksProcessors\DataProcessor\DataProcessorDTO;

interface BeforeCreate
{
    public function beforeCreate(DataProcessorDTO $request): void;
}
