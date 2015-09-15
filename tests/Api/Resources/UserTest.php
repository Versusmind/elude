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

    protected static $password;

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->faker = \Faker\Factory::create();

        if(is_null(self::$password)) {
            self::$password = $this->faker->password();
        }
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
                    'password' => \Hash::make(self::$password),
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
                'password' => \Hash::make(self::$password),
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
        $user = $this->createUserAndLogin();

        $this->addPermission($user->id, $this->permission->id);
    }

    public function testRemovePermission()
    {
        $user = $this->createUserAndLogin();

        $this->addPermission($user->id, $this->permission->id);
        $this->removePermission($user->id, $this->permission->id);
    }

    public function testAddRole()
    {
        $user = $this->createUserAndLogin();

        $this->addRole($user->id, $this->role->id);
    }

    public function testRemoveRole()
    {
        $user = $this->createUserAndLogin();

        $this->addRole($user->id, $this->role->id);
        $this->removeRole($user->id, $this->role->id);
    }

    public function testChangeGroup()
    {
        $user = $this->createUserAndLogin();

        $group = (new Group())->create(new \App\Group([
            'name' => uniqid()
        ]));

        $this->call('PUT', $this->apiPath . $this->resourceName . '/' . $user->id . '/group/' . $group->id);
        $this->seeJson([]);
        $this->seeStatusCode(202);
        $model = json_decode($this->response->getContent());
        $this->assertEquals($group->id, $model->group->id);
    }


    /**
     * @param $attributes
     *
     * @dataProvider createOkProvider
     */
    public function testCreateOk($attributes, $pattern)
    {
        parent::testCreateOk($attributes, $pattern);
    }

    /**
     * @param $id
     *
     * @dataProvider findOkProvider
     * @depends      testCreateOk
     */
    public function testFindOk($id, $pattern)
    {
        $this->login();

        parent::testFindOk($id, $pattern);
    }

    /**
     * @param $id
     * @param $data
     *
     * @dataProvider updateOkProvider
     * @depends      testFindOk
     */
    public function testUpdateOk($id, $data, $pattern)
    {
        $this->login();

        parent::testUpdateOk($id, $data, $pattern);
    }

    /**
     * @param $id
     * @param $data
     *
     * @dataProvider updateKoProvider
     */
    public function testUpdateKo($id, $data, $status)
    {
        $user = $this->createUserAndLogin();

        parent::testUpdateKo($user->id, $data, $status);
    }

    /**
     * @param $id
     *
     * @dataProvider deleteOkProvider
     * @depends      testUpdateOk
     */
    public function testDeleteOk($id)
    {
        $user = (new User())->find($id);
        $this->login($user->username, self::$password);

        parent::testDeleteOk($id);
    }


    protected function createUserAndLogin()
    {
        $username = $this->faker->userName;
        $user = (new User())->create(new \App\User([
            'username' => $username,
            'email' => $this->faker->safeEmail,
            'password' => \Hash::make(self::$password),
        ]));
        $this->login($username, self::$password);

        return $user;
    }

    public function testAllOk()
    {
        $this->createUserAndLogin();
        $this->call('GET', $this->apiPath . $this->resourceName);
        $this->seeJson([]);
        $this->seeStatusCode(200);
    }

    public function testAllPaginateOk()
    {
        $this->createUserAndLogin();
        $this->call('GET', $this->apiPath . $this->resourceName, [
            'paginate' => 1
        ]);
        $this->seeJson([]);
        $this->seeStatusCode(200);
    }
}