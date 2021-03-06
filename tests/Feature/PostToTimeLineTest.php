<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\post;

class PostToTimeLineTest extends TestCase
{
    use RefreshDatabase;
       /** @test */  
       public function a_user_can_post_a_text_post(){
        $this->withExceptionHandling();
        $this->actingAs($user = factory(User::class)->create(),'api');
       
        $response = $this->post('/api/posts',[
            'data' =>[
                'type' => 'posts',
                'attributes' =>[
                    'body' => 'testing body'
                ]
            ]
        ]);
          $post = post::first();
          $this->assertCount(1,post::all());
          $this->assertEquals($user->id,$post->user_id);
          $this->assertEquals('testing body',$post->body);

          $response->assertStatus(201)
          ->assertJson([
            'data'=>[
                'type'=>'posts',
                'post_id'=> $post->id,
                'attributes'=>[
                     'posted_by' => [
                        'data'=>[
                            'attributes' =>[
                                'name'=> $user->name,
                            ]
                        ]
                    ],
                    'body'=>'testing body'
                ],
                'links'=>[
                    'self'=> url('/posts/'.$post->id),
                ] 
            ]
          ]);
    
    }
}
