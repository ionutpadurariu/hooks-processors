<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor;

use HooksProcessors\DataProcessor\Hooks\BeforeUpdate;

interface HooksProcessorBeforeUpdate extends HooksProcessorSupports, BeforeUpdate
{
}
