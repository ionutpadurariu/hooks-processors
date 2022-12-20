<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor\Hooks;

use HooksProcessors\DataProcessor\DataProcessorDTO;

interface BeforeRemove
{
    public function beforeRemove(DataProcessorDTO $request): void;
}
