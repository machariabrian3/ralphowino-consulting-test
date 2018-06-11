<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
     //1
    public function  user_can_login_with_valid_credentials()
    {
      $user = factory(User::class)->create();

      $response = $this->post('/login', [
          'email' => $user->email,
          'password' => 'secret'
      ]);

      $response->assertStatus(302);
    }
    //2
    public function  user_cannot_login_with_invalid_credentials()
    {
      $user = factory(User::class)->create();

      $response = $this->post('/login', [
          'email' => $user->email,
          'password' => 'invalid'
      ]);

      $response->assertSessionHasErrors();
    }
    //3
    public function  user_can_register_with_valid_credentials()
    {
      $user = factory(User::class)->make();

      $response = $this->post('register', [
          'name' => $user->name,
          'email' => $user->email,
          'password' => 'secret',
          'password_confirmation' => 'secret'
      ]);

      $response->assertStatus(302);
    }
    //4
    public function  user_cannot_register_with_existing_credentials()
    {
      $user = factory(User::class)->make();

      $response = $this->post('register', [
          'name' => $user->name,
          'email' => $user->email,
          'password' => 'secret',
          'password_confirmation' => 'invalid'
      ]);

      $response->assertSessionHasErrors();
    }
    //5
    public function  user_can_request_for_reset_password_code()
    {
      $response = $this->get('/password/reset/token');

      $response->assertStatus(200);
    }
    //6
    public function  user_can_reset_password_with_valid_code()
    {
      $user = factory(User::class)->create();

      $token = Password::createToken($user);

      $response = $this->post('/password/reset', [
          'token' => $token,
          'email' => $user->email,
          'password' => 'password',
          'password_confirmation' => 'password'
      ]);

      $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
