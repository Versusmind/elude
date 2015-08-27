<?php namespace Tests\Unit;

use App\Libraries\Acl\PermissionResolver;
use App\Libraries\Acl\Repositories\Group;
use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;
use App\Libraries\Acl\Repositories\User;
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

        $this->userRepository = new User();
        $this->permissionRepository = new Permission();
        $this->roleRepository = new Role();
        $this->groupRepository = new Group();
        $this->faker = \Faker\Factory::create();

        parent::__construct($name, $data, $dataName);
    }

    public function setUp()
    {
        parent::setUp();

        $this->user = $this->userRepository->create([
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password(),
        ]);

        $this->group = $this->groupRepository->create([
            'name' => uniqid()
        ]);

        $this->role = $this->roleRepository->create([
            'name' => uniqid(),
            'filter' => 'A'
        ]);

        $this->roleRevoke = $this->roleRepository->create([
            'name' => uniqid(),
            'filter' => 'R'
        ]);

        $this->permissionFirst = $this->permissionRepository->create([
            'area'        => uniqid(),
            'permission'  => uniqid(),
            'action'      => uniqid(),
            'description' => 'test'
        ]);

        $this->permissionSecond = $this->permissionRepository->create([
            'area'        => uniqid(),
            'permission'  => uniqid(),
            'action'      => uniqid(),
            'description' => 'test'
        ]);

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

        $this->groupRepository->addRole($this->group, $this->role);

        $this->resolver->setGroup($this->group);
        $permissions = $this->resolver->resolve();

        $this->assertEquals(2, $permissions->count());
        $this->assertTrue($permissions->get($this->permissionFirst->getAction()), json_encode($permissions));
        $this->assertTrue($permissions->get($this->permissionSecond->getAction()), json_encode($permissions));
    }

    public function testResolveRole()
    {
        $this->assertTrue(false);
    }

    public function testResolveUser()
    {
        $this->assertTrue(false);
    }

    public function testResolveOrder()
    {
        $this->assertTrue(false);
    }
}