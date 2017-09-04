<?php namespace z5internet\ReactUserFramework\App\Events;

use z5internet\ReactUserFramework\App\Subscription;

use Illuminate\Queue\SerializesModels;

class SubscriptionCreated {

    use SerializesModels;

    public $subscription;

    public function __construct(Subscription $subscription) {

        $this->subscription = $subscription;

    }

}