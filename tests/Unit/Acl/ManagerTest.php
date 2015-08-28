<?php namespace Tests\Unit;

use App\Libraries\Acl\Manager\Manager;
use App\Libraries\Acl\Repositories\Group;
use App\Libraries\Acl\Repositories\Permission;
use App\Libraries\Acl\Repositories\Role;
use App\Libraries\Acl\Repositories\User;
use Tests\TestCase;

/**
 * @group unit
 **/
abstract class ManagerTest extends TestCase
{
    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var User
     */
    protected $userRepository;

    /**
     * @var Permission
     */
    protected $permissionRepository;

    /**
     * @var Role
     */
    protected $roleRepository;

    /**
     * @var Group
     */
    protected $groupRepository;

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * @var \App\User
     */
    protected $user;

    /**
     * @var \App\Group
     */
    protected $group;

    /**
     * @var \App\Role
     */
    protected $role;

    /**
     * @var \App\Role
     */
    protected $roleRevoke;

    /**
     * @var \App\Permission
     */
    protected $permissionFirst;

    /**
     * @var \App\Permission
     */
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

    /**
     * @return Manager
     */
    public abstract function getManager();

    public function setUp()
    {
        $this->manager = $this->getManager();

        parent::setUp();

        $this->user = $this->userRepository->create([
            'username' => $this->faker->userName,
            'email'    => $this->faker->safeEmail,
            'password' => $this->faker->password(),
        ]);

        $this->group = $this->groupRepository->create([
            'name' => uniqid()
        ]);

        $this->role = $this->roleRepository->create([
            'name'   => uniqid(),
            'filter' => 'A'
        ]);

        $this->roleRevoke = $this->roleRepository->create([
            'name'   => uniqid(),
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

        $this->initiate();
    }

    public abstract function testIsAllowOk();
    public abstract function testIsAllowKo();

    public abstract function testGrantOk();
    public abstract function testGrantKo();

    public abstract function testDenyOk();
    public abstract function testDenyKo();

    public abstract function testAll();

    /**
     * You can use this method to seed the db
     * after database refresh
     *
     * @return bool
     */
    public function initiate()
    {

        return true;
    }
}