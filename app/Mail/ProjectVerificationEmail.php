<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($connection, $project)
    {
        $this->connection = $connection;
        $this->project    = $project;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $content = [
            'title'       => trans('api.projects_would_like_to_connect_title'),
            'description' => trans('api.projects_would_like_to_connect_description')
        ];
        $actions = [
            'title'       => trans('api.projects_connect_action_title'),
            'url'         => route('projects.connect', ['token' => $this->connection->token], false)
        ];

        return $this->view('emails.transaction')->from('info@dbp4.org')->with([
            'project' => $this->project,
            'content' => $content,
            'actions' => $actions
        ]);
    }
}
