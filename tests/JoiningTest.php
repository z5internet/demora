<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

class JoiningTest extends TestCase
{

    use DatabaseTransactions;

    public function testAddingInJoin()
    {

        $cookie = 'www';

        $first_name = 'Sally';
        $email = 'dfg@dfgg.dfg';

        /** put name in join **/

        $response = $this->call('POST', '/data/join', [

            'first_name' => $first_name,
            'email' => $email,

        ], ['sou' => $cookie]);

        $this->assertEquals(['data' => [
                'joined' => 1,
                'rufP' => 1,
            ],
        ], json_decode($response->getContent(), true));

        $this->seeInDatabase('joined', [

            'email' => $email,
            'first_name' => $first_name,
            'ref' => $cookie,

        ]);

        $joined = \z5internet\ReactUserFramework\App\Joined::where('email', $email)
            ->first(['id', 'code']);

        $response = $this->call('GET', '/data/setup', [

            'id' => $joined->id,
            'code' => $joined->code,

        ]);

        $this->assertEquals(['data' => [
                'setup' => [
                    'existingUser' => false,
                    'invited' => false,
                    'first_name' => $first_name,
                    'teamToJoin' => null,
                    'uploadProfilePic' => config('react-user-framework.setup.upload_profile_pic'),
                    'usernameRequired' => config('react-user-framework.setup.username_required'),
                ],
                'rufP' => 1,
            ],
        ], json_decode($response->getContent(), true));

    }

}
