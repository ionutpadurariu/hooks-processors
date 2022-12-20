<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor\Hooks;

use HooksProcessors\DataProcessor\DataProcessorDTO;

interface BeforeUpdate
{
    public function beforeUpdate(DataProcessorDTO $request): void;
}
