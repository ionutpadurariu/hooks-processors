<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor;

use HooksProcessors\DataProcessor\Hooks\AfterRemove;

interface HooksProcessorAfterRemove extends HooksProcessorSupports, AfterRemove
{
}
