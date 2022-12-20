<?php

namespace HooksProcessors\DataProcessor\Hooks;

use HooksProcessors\DataProcessor\DataProcessorDTO;

interface AfterCreate
{
    public function afterCreate(DataProcessorDTO $request): void;
}
