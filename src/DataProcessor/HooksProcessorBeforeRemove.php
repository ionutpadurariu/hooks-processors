<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor;

use HooksProcessors\DataProcessor\Hooks\BeforeRemove;

interface HooksProcessorBeforeRemove extends HooksProcessorSupports, BeforeRemove
{
}
