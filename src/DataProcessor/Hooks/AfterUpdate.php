<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor\Hooks;

use HooksProcessors\DataProcessor\DataProcessorDTO;

interface AfterUpdate
{
    public function afterUpdate(DataProcessorDTO $request): void;
}
