<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor\Hooks;

use HooksProcessors\DataProcessor\DataProcessorDTO;

interface AfterRemove
{
    public function afterRemove(DataProcessorDTO $request): void;
}
