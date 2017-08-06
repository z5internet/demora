<?php namespace z5internet\ReactUserFramework\App\Events;

use Illuminate\Queue\SerializesModels;

class AddedTeamMemberToPlan {

    use SerializesModels;

    public $team_id;

    public $user;

    public $product_id;

    public function __construct($team_id, $user, $product_id) {

        $this->team_id = $team_id;

        $this->user = $user;

        $this->product_id = $product_id;

    }

}