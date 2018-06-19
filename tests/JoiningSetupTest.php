<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

use z5internet\ReactUserFramework\App\Http\Controllers\User\UserController;

class JoiningTest extends TestCase
{

    use DatabaseTransactions;

    public function testAddingInJoin()
    {

        $referrer = 'www';

        $first_name = 'Sally';
        $last_name = 'Diggins';
        $username = '123234123123asfdasdfsdf';
        $password = 'simplepassword';
        $email = 'dfg@dfgg.dfg';
        $team_name = $first_name.'-'.$last_name;

        /** put name in join **/

        $response = $this->call('POST', '/data/join', [

            'first_name' => $first_name,
            'email' => $email,

        ], ['sou' => $referrer]);

        $this->assertEquals(['data' => [
                'joined' => 1,
                'rufP' => 1,
            ],
        ], json_decode($response->getContent(), true));

        $this->seeInDatabase('joined', [

            'email' => $email,
            'first_name' => $first_name,
            'ref' => $referrer,

        ]);

        /** Get setup info **/

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

        /** check username **/

        /** check when username already exists **/

        $checkUsername = \App\User::first(['username'])->username;

        $response = $this->call('POST', '/data/setup/checkusername', [

            'username' => $checkUsername,

        ]);

        $this->assertEquals(['data' => [
                'setup' =>  [
                    'usernameError' =>  'That username has already been taken, please choose another.'
                ],
                'rufP' => 1,
            ],
        ], json_decode($response->getContent(), true));

        /** check when username is ok **/

        $response = $this->call('POST', '/data/setup/checkusername', [

            'username' => $username,

        ]);

        $this->assertEquals(['data' => [
                'setup' =>  [
                    'usernameOK'    =>  true,
                ],
                'rufP' => 1,
            ],
        ], json_decode($response->getContent(), true));

        /** submit setup data **/

        $response = $this->call('POST', '/data/setup', [

            "first_name" => $first_name,

            "last_name" => $last_name,

            "password1" => $password,

            "username" => $username,

            "code" => $joined->code,

            "id" => $joined->id,

            "gender" => 0,

            "teamName" => $team_name,

        ]);

        $this->seeInDatabase('users', [

            'email' => $email,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'username' => $username,

        ]);

        $this->seeInDatabase('teams', [

            'id' => json_decode($response->getContent())->data->setup->team,
            'name' => $team_name,

        ]);

    }

}
