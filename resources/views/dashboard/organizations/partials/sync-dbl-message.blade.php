@if(isset($user->isDBLContact))
    <div class="container mb50">
    <article class="message is-success is-medium">
        <div class="message-header">
             <p>DBL Organization Admin Email detected!</p>
            <button class="delete" aria-label="delete"></button>
        </div>
        <div class="message-body">
            We've detected that your email matches that of a DBL Organizational Admin. If you'd like to sync the permissions between your two accounts and create a new access group you can do that from here.
            <br><br>
            <a class="button" href="">Sync Permissions with the DBL</a>
        </div>
    </article>
    </div>
@endif