# API Platform Hooks Processors
This repository is to be used with API Platform.

## Configuration using symfony DI

```yaml
services:
  _instanceof:
        HooksProcessors\DataProcessor\HooksProcessorBeforeCreate:
            tags: ['hooks_processors.before_create']
        HooksProcessors\DataProcessor\HooksProcessorAfterCreate:
            tags: ['hooks_processors.after_create']
        HooksProcessors\DataProcessor\HooksProcessorBeforeUpdate:
            tags: ['hooks_processors.before_update']
        HooksProcessors\DataProcessor\HooksProcessorAfterUpdate:
            tags: ['hooks_processors.after_update']
        HooksProcessors\DataProcessor\HooksProcessorBeforeRemove:
            tags: ['hooks_processors.before_remove']
        HooksProcessors\DataProcessor\HooksProcessorAfterRemove:
            tags: ['hooks_processors.after_remove']

  HooksProcessors\DataProcessor\HooksProcessor:
    bind:
      $persistProcessor: '@api_platform.doctrine.orm.state.persist_processor'
      $removeProcessor: '@api_platform.doctrine.orm.state.remove_processor'
      $handlersBeforeCreate: !tagged_iterator 'hooks_processors.before_create'
      $handlersAfterCreate: !tagged_iterator 'hooks_processors.after_create'
      $handlersBeforeUpdate: !tagged_iterator 'hooks_processors.before_update'
      $handlersAfterUpdate: !tagged_iterator 'hooks_processors.after_update'
      $handlersBeforeRemove: !tagged_iterator 'hooks_processors.before_remove'
      $handlersAfterRemove: !tagged_iterator 'hooks_processors.after_remove'
```

### Examples of implementations

```php

<?php

use HooksProcessors\DataProcessor\DataProcessorDTO;
use HooksProcessors\DataProcessor\HooksProcessorBeforeCreate;

final class MyEntityBeforeCreateProcessor implements HooksProcessorBeforeCreate
{
    public function __construct(
        private readonly SomePreCreateProcessing $somePreCreateProcessing
    )
    public function beforeCreate(DataProcessorDTO $request): void
    {
        $myEntity = $request->getData();
        
        $this->somePreCreateProcessing->prepare($myEntity);
    }

    public function supports(DataProcessorDTO $dataProcessorDto): bool
    {
        return $dataProcessorDto->getData() instanceof MyEntity;
    }
}
```
```php
<?php

use HooksProcessors\DataProcessor\DataProcessorDTO;
use HooksProcessors\DataProcessor\HooksProcessorAfterCreate;

final class MyEntityAfterCreateProcessor implements HooksProcessorAfterCreate
{
    public function __construct(
        private readonly EmailService $emailService
    )
    public function afterCreate(DataProcessorDTO $request): void
    {
        $myEntity = $request->getData();

        $this->emailService->sendEmail($myEntity);
    }

    public function supports(DataProcessorDTO $dataProcessorDto): bool
    {
        return $dataProcessorDto->getData() instanceof MyEntity;
    }
}
```

### Example of multiple hooks of the same type.
You can use multiple hooks (afterCreate for example) using symfony DI and setting the right priority: More info [here](https://symfony.com/doc/current/service_container/tags.html#tagged-services-with-priority)
This can be handy if you don't want to have mix of logic in the processor.

Example:
```yaml

     SendEmailAfterCreateProcessor:
      tags:
        - { name: 'hooks_processors.after_create', priority: 1 }

     CreateInExternalSystemAfterCreate:
      tags:
        - { name: 'hooks_processors.after_create', priority: 2 }

```
