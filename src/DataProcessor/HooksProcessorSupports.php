<?php

namespace HooksProcessors\DataProcessor;

interface HooksProcessorSupports
{
    public function supports(DataProcessorDTO $dataProcessorDto): bool;
}
