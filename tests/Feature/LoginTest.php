<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testLoginFalse()
    {
        $credential = [
            'email' => 'alemhar@gmail.com',
            'password' => 'WrongPassword' 

        ];
        $response = $this->post('login',$credential);
        $response->assertSessionHasErrors();
    }
}

