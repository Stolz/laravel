## Stubs already implemented by `artisan make:stub`

Domain model

    ✓ A app/Models/Dummy.php

Database migration and mapping

    ✓ A database/migrations/0000_00_00_000000_create_dummies_table.php
    ✓ M database/mappings/all.php
    ✓ A database/mappings/Dummy.php

Repository contract, implementation and binding

    ✓ A app/Repositories/Contracts/DummyRepository.php
    ✓ A app/Repositories/Doctrine/DummyRepository.php
    ✓ M app/Providers/RepositoryServiceProvider.php

Permissions seeder, policy implementation and binding

    ✓ M database/seeds/PermissionsSeeder.php
    M app/Policies/ModulePolicy.php (Only if its a new module)
    ✓ A app/Policies/DummyPolicy.php
    ✓ M app/Providers/AuthServiceProvider.php

Route and route parameter binding

    ✓ M routes/web.php
    ✓ M app/Providers/RouteServiceProvider.php

Controller

    ✓ A app/Http/Controllers/Bogus/DummyController.php

Form requests

    ✓ A app/Http/Requests/Dummy/Create.php
    ✓ A app/Http/Requests/Dummy/Delete.php
    ✓ A app/Http/Requests/Dummy/Index.php
    ✓ A app/Http/Requests/Dummy/Update.php
    ✓ A app/Http/Requests/Dummy/View.php

Seeder and factory

    A database/factories/DummyFactory.php
    M database/seeds/DatabaseSeeder.php
    A database/seeds/DummiesSeeder.php

Navigation views

    M resources/views/top.blade.php (Only if its a new module)
    M resources/views/modules/bogus.blade.php

CRUD views

    ✓ A resources/views/modules/bogus/dummy/create.blade.php
    ✓ A resources/views/modules/bogus/dummy/form.blade.php
    ✓ A resources/views/modules/bogus/dummy/index.blade.php
    ✓ A resources/views/modules/bogus/dummy/show.blade.php
    ✓ A resources/views/modules/bogus/dummy/update.blade.php

TODO

    Api stuff, tests, ...
