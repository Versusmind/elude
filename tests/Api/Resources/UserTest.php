<?php namespace Tests\Api\Resources;

use App\Libraries\Acl\Repositories\Group;
use App\Libraries\Acl\Repositories\User;

/**
 * @group api
 */
class UserTest extends RoleAware
{

    /**
     * @var \Faker\Generator
     */
    protected $faker;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->faker = \Faker\Factory::create();

        parent::__construct($name, $data, $dataName);
    }


    function getResourceName()
    {
        return 'users';
    }

    function createOkProvider()
    {
        return [
            [
                [
                    'username' => $this->faker->userName,
                    'email' => $this->faker->safeEmail,
                    'password' => $this->faker->password(),
                ],
                [
                    'id' => self::NUMBER,
                    'username' => self::STRING,
                    'email' => self::STRING
                ]
            ]
        ];
    }

    function createKoProvider()
    {
        return [
            [
                ['username' => null]
            ]
        ];
    }

    function updateOkProvider()
    {
        return [
            [1, [
                'username' => $this->faker->userName,
                'email' => $this->faker->safeEmail,
                'password' => $this->faker->password(),
            ], [
                'id' => self::NUMBER,
                'username' => self::STRING,
                'email' => self::STRING]
            ]
        ];
    }

    function updateKoProvider()
    {
        return [
            [1, ['username' => null], 400]
        ];
    }

    function findOkProvider()
    {
        return [
            [1, ['id' => self::NUMBER, 'username' => self::STRING, 'email' => self::STRING]]
        ];
    }

    public function testAddPermission()
    {
        $user = (new User())->create([
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password(),
        ]);

        $this->addPermission($user->id, $this->permission->id);
    }

    public function testRemovePermission()
    {
        $user = (new User())->create([
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password(),
        ]);

        $this->addPermission($user->id, $this->permission->id);
        $this->removePermission($user->id, $this->permission->id);
    }

    public function testAddRole()
    {
        $user = (new User())->create([
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password(),
        ]);

        $this->addRole($user->id, $this->role->id);
    }

    public function testRemoveRole()
    {
        $user = (new User())->create([
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password(),
        ]);

        $this->addRole($user->id, $this->role->id);
        $this->removeRole($user->id, $this->role->id);
    }

    public function testChangeGroup()
    {
        $user = (new User())->create([
            'username' => $this->faker->userName,
            'email' => $this->faker->safeEmail,
            'password' => $this->faker->password(),
        ]);

        $group = (new Group())->create([
            'name' => uniqid()
        ]);


        $this->call('PUT', $this->apiPath . $this->resourceName . '/' . $user->id . '/group/' . $group->id);
        $this->seeJson([]);
        $this->seeStatusCode(204);
        $model = json_decode($this->response->getContent());
        $this->assertEquals($group->id, $model->group->id);
    }
}