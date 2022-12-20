<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor;

use HooksProcessors\DataProcessor\Hooks\BeforeCreate;

interface HooksProcessorBeforeCreate extends HooksProcessorSupports, BeforeCreate
{
}
