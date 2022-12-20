<?php
declare(strict_types=1);

namespace HooksProcessors\DataProcessor;

use HooksProcessors\DataProcessor\Hooks\AfterCreate;

interface HooksProcessorAfterCreate extends HooksProcessorSupports, AfterCreate
{
}
