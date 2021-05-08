<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

class ForgotPassword
{
    use SerializesModels;

    /**
     * @var User
     */
    public User $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
