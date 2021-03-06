<?php namespace Tests\Unit;

use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\Group;
use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;
use App\Libraries\Acl\Repositories\User;
use Illuminate\Support\Collection;
use Tests\TestCase;

/**
 * @group unit
 **/
class PermissionResolverTest extends TestCase
{

    /**
     * @var PermissionResolver
     */
    protected $resolver;

    protected $userRepository;

    protected $permissionRepository;

    protected $roleRepository;

    protected $groupRepository;

    protected $faker;

    protected $user;

    protected $group;

    protected $role;

    protected $roleRevoke;

    protected $permissionFirst;

    protected $permissionSecond;

    /**
     * PermissionResolverTest constructor.
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {

        $this->userRepository       = new User();
        $this->permissionRepository = new Permission();
        $this->roleRepository       = new Role();
        $this->groupRepository      = new Group();
        $this->faker                = \Faker\Factory::create();

        parent::__construct($name, $data, $dataName);
    }

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->userRepository->create(new \App\User([
            'username' => $this->faker->userName,
            'email'    => $this->faker->safeEmail,
            'password' => $this->faker->password(),
        ]));

        $this->group = $this->groupRepository->create(new \App\Group([
            'name' => uniqid()
        ]));

        $this->role = $this->roleRepository->create(new \App\Role([
            'name'   => uniqid(),
            'filter' => 'A'
        ]));

        $this->roleRevoke = $this->roleRepository->create(new \App\Role([
            'name'   => uniqid(),
            'filter' => 'R'
        ]));

        $this->permissionFirst = $this->permissionRepository->create(new \App\Permission([
            'area'        => uniqid(),
            'permission'  => uniqid(),
            'description' => 'test'
        ]));

        $this->permissionSecond = $this->permissionRepository->create(new \App\Permission([
            'area'        => uniqid(),
            'permission'  => uniqid(),
            'description' => 'test'
        ]));

        $this->resolver = new PermissionResolver();
    }

    public function testResolveGroup()
    {
        $this->assertTrue($this->role->exists);
        $this->assertTrue($this->group->exists);
        $this->assertTrue($this->permissionFirst->exists);
        $this->assertTrue($this->permissionSecond->exists);

        $this->groupRepository->addPermission($this->group, $this->permissionFirst);
        $this->roleRepository->addPermission($this->role, $this->permissionSecond);

        $this->resolver->setGroup($this->group);
        $permissions = $this->resolver->resolve();

        $this->assertEquals(1, $permissions->count());
        $this->assertTrue($permissions->get($this->permissionFirst->getAction()), json_encode($permissions));
        $this->assertFalse($permissions->get($this->permissionSecond->getAction(), false), json_encode($permissions));
    }

    public function testResolveGroupAndRole()
    {
        $this->assertTrue($this->role->exists);
        $this->assertTrue($this->group->exists);
        $this->assertTrue($this->permissionFirst->exists);
        $this->assertTrue($this->permissionSecond->exists);

        $this->groupRepository->addPermission($this->group, $this->permissionFirst);
        $this->roleRepository->addPermission($this->role, $this->permissionSecond);
        $this->groupRepository->addRole($this->group, $this->role);

        $this->resolver->setGroup($this->group);
        $permissions = $this->resolver->resolve();

        $this->assertEquals(2, $permissions->count());
        $this->assertTrue($permissions->get($this->permissionFirst->getAction()), json_encode($permissions));
        $this->assertTrue($permissions->get($this->permissionSecond->getAction()), json_encode($permissions));
    }

    public function testResolveRole()
    {
        $this->assertTrue($this->role->exists);
        $this->assertTrue($this->group->exists);
        $this->assertTrue($this->permissionFirst->exists);
        $this->assertTrue($this->permissionSecond->exists);

        $this->roleRepository->addPermission($this->role, $this->permissionFirst);
        $this->resolver->setRoles(new Collection([$this->role]));

        $permissions = $this->resolver->resolve();
        $this->assertEquals(1, $permissions->count());
        $this->assertTrue($permissions->get($this->permissionFirst->getAction(), false), json_encode($permissions));
        $this->assertFalse($permissions->get($this->permissionSecond->getAction(), false), json_encode($permissions));
    }

    public function testResolveUser()
    {
        $this->assertTrue($this->role->exists);
        $this->assertTrue($this->user->exists);
        $this->assertTrue($this->group->exists);
        $this->assertTrue($this->permissionFirst->exists);
        $this->assertTrue($this->permissionSecond->exists);

        $this->roleRepository->addPermission($this->role, $this->permissionSecond);
        $this->groupRepository->addRole($this->group, $this->role);

        $this->userRepository->setGroup($this->user, $this->group);

        $this->resolver->setGroup($this->user->group);
        $this->resolver->setRoles($this->user->roles);
        $this->resolver->setPermissions($this->user->permissions);

        $permissions = $this->resolver->resolve();

        $this->assertEquals(1, $permissions->count());
        $this->assertTrue($permissions->get($this->permissionSecond->getAction()), json_encode($permissions));
        $this->assertFalse($permissions->get($this->permissionFirst->getAction(), false), json_encode($permissions));
    }

    public function testResolveUserGroupAndUser()
    {
        $this->assertTrue($this->role->exists);
        $this->assertTrue($this->user->exists);
        $this->assertTrue($this->group->exists);
        $this->assertTrue($this->permissionFirst->exists);
        $this->assertTrue($this->permissionSecond->exists);

        $this->groupRepository->addPermission($this->group, $this->permissionFirst);
        $this->roleRepository->addPermission($this->role, $this->permissionSecond);
        $this->groupRepository->addRole($this->group, $this->role);

        $this->userRepository->setGroup($this->user, $this->group);

        $this->userRepository->addPermission($this->user, $this->permissionFirst);

        $this->resolver->setGroup($this->user->group);
        $this->resolver->setRoles($this->user->roles);
        $this->resolver->setPermissions($this->user->permissions);
        $permissions = $this->resolver->resolve();

        $this->assertEquals(2, $permissions->count());
        $this->assertTrue($permissions->get($this->permissionFirst->getAction()), json_encode($permissions));
        $this->assertTrue($permissions->get($this->permissionSecond->getAction()), json_encode($permissions));
    }
}