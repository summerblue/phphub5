<?php
namespace Smartisan\Follow\Test;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class FollowTraitTest extends TestCase
{
    public function test_user_can_follow_by_id()
    {
        $user1 = UserTest::find(1);
        $user2 = UserTest::find(2);

        $user1->follow($user2->id);

        $this->assertCount(1, $user1->followings);
        $this->assertCount(1, $user2->followers);
    }

    public function test_user_can_follow_multiple_users()
    {
        $user1 = UserTest::find(1);
        $user2 = UserTest::find(2);
        $user3 = UserTest::find(3);

        $user1->follow([$user2->id, $user3->id]);

        $this->assertCount(2, $user1->followings);
        $this->assertCount(1, $user2->followers);
        $this->assertCount(1, $user3->followers);
    }

    public function test_follow_not_existing_user()
    {
        $user1 = UserTest::find(1);
        $user2 = UserTest::find(4);

        try {
            $user1->follow($user2);
        } catch (\Exception $e) {
            $this->assertInstanceOf('Illuminate\Database\Eloquent\ModelNotFoundException', $e);
        }
    }

    public function test_unfollow_user()
    {
        $user1 = UserTest::find(1);
        $user2 = UserTest::find(2);

        $user1->follow($user2->id);
        $this->assertCount(1, $user2->followers);
        $user1->unfollow($user2->id);
        $this->assertCount(0, $user1->followings);
    }

    public function test_is_following()
    {
        $user1 = UserTest::find(1);
        $user2 = UserTest::find(2);

        $user1->follow($user2->id);
        $this->assertTrue($user1->isFollowing($user2->id));
    }

    public function test_is_followed_by()
    {
        $user1 = UserTest::find(1);
        $user2 = UserTest::find(2);

        $user1->follow($user2->id);
        $this->assertTrue($user2->isFollowedBy($user1->id));
    }
}