<?php namespace Tests\Unit\Acl\Repositories;

use App\Libraries\Acl\Repositories\User;
use App\Libraries\Repository;
use Faker\Generator;
use Tests\Unit\RepositoryTest;

/**
 * @group unit
 **/
class UserTest extends RepositoryTest
{

    /**
     * @var Generator
     */
    protected $faker;

    /**
     * UserTest constructor.
     *
     */
    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        $this->faker = \Faker\Factory::create();

        parent::__construct($name, $data, $dataName);
    }

    /**
     * @return Repository
     */
    public function getRepository()
    {
        return new User();
    }

    public function createOkProvider()
    {
        return [
            [
                [
                    'username' => $this->faker->userName,
                    'email' => $this->faker->safeEmail,
                    'password' => $this->faker->password(),
                    'group_id' => 1,
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function findKoProvider()
    {
        return [
            [-1]
        ];
    }

    /**
     * @return array
     */
    public function updateOkProvider()
    {
        return [
            [
                1, ['username' => $this->faker->userName]
            ]
        ];
    }

    /**
     * @return array
     */
    public function whereOkProvider()
    {
        return [
            [
                [
                    'group_id' => 1
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function deleteKoProvider()
    {
        return [
            [-1]
        ];
    }

    /**
     * @return array
     */
    public function updateKoProvider()
    {
        return [
            [
                1, [uniqid() => $this->faker->safeEmail]
            ],
            [
                1, ['email' => 'notavalidemail']
            ],
            [
                1, ['password' => '']
            ]
        ];
    }

    /**
     * @return array
     */
    public function findOkProvider()
    {
        return [
            [1]
        ];
    }

    /**
     * @return array
     */
    public function createKoProvider()
    {
        return [
            [
                [
                    'username' => $this->faker->userName,
                    'email' => $this->faker->safeEmail,
                    'password' => '',
                    'group_id' => 1,
                ],
                [
                    'username' => $this->faker->userName,
                    'email' => 'notemail',
                    'password' => $this->faker->password(),
                    'group_id' => 1,
                ],
                [
                    'username' => '',
                    'email' => $this->faker->safeEmail,
                    'password' => $this->faker->password(),
                    'group_id' => 1,
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function deleteOkProvider()
    {
        return [
            [1]
        ];
    }

    /**
     * @expectedException \App\Libraries\Acl\Exceptions\ModelNotValid
     */
    public function testDuplicate()
    {
        $attributes = [
            'username' => $this->faker->userName,
            'email' => $this->faker->email,
            'password' => $this->faker->password(),
            'group_id' => 1,
        ];

        $model = $this->repository->create($attributes);
        $this->assertModel($model);

        $model = $this->repository->create($attributes);
        $this->assertModel($model, false);
    }

    /**
     * @param $attributes
     *
     * @dataProvider createOkProvider
     */
    public function testCreateOk($attributes)
    {
        \App\Group::create([
            'name' => 'test'
        ]);

        parent::testCreateKo($attributes);
    }


}