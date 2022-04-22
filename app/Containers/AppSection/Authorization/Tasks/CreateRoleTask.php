<?php

namespace App\Containers\AppSection\Authorization\Tasks;

use App\Containers\AppSection\Authorization\Data\Repositories\RoleRepository;
use App\Containers\AppSection\Authorization\Models\Role;
use App\Ship\Exceptions\CreateResourceFailedException;
use App\Ship\Parents\Tasks\Task;
use Exception;

class CreateRoleTask extends Task
{
    public function __construct(
        protected RoleRepository $repository
    ) {
    }

    /**
     * @throws CreateResourceFailedException
     */
    public function run(string $name, string $description = null, string $displayName = null): Role
    {
        app()['cache']->forget('spatie.permission.cache');

        try {
            $role = $this->repository->create([
                'name' => strtolower($name),
                'description' => $description,
                'display_name' => $displayName,
                'guard_name' => 'api',
            ]);
        } catch (Exception) {
            throw new CreateResourceFailedException();
        }

        return $role;
    }
}
