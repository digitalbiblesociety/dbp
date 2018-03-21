@if(count($user->roles) > 0)
    <div class="row organizations">
        @foreach($user->roles as $connection)
            <a href="{{ route('dashboard_organizations.show',['id' => $connection->organization->id]) }}" class="medium-2 columns">
                <img src="{{ $connection->organization->logo->url }}" title="{{ $connection->organization->translations("eng")->first()->name }}" />
                <small class="subtitle">{{ $connection->role }}</small>
            </a>
        @endforeach
        <a href="{{ route('dashboard_organization_roles.create') }}" class="medium-2 columns">
            <img src="/img/icons/add.svg" />
            <small class="subtitle">Add an Organization</small>
        </a>
    </div>

    <div class="row">

        <div class="medium-4 columns">
            <h2>Create new Resources</h2>
            <ul>
                @if($user->roles->where('organization', '!=', null)->where('role','!=','requesting-access')->first())
                    <li><a href="/bibles/create">Create Bible</a></li>
                    <li><a href="/resources/create">Create Resource</a></li>
                @else
                    <li><span class="disabled">Create Bible</span></li>
                    <li><span class="disabled">Create Resource</span></li>
                    <small class="text-center">You need Organization Permissions to Edit Bible or Resource Data</small>
                @endif
                @if($user->archivist)
                    <li><a href="/languages/create">Create Language</a></li>
                    <li><a href="/alphabets/create">Create Alphabet</a></li>
                    <li><a href="/countries/create">Create Country</a></li>
                @else
                    <li><span class="disabled">Create Language</span></li>
                    <li><span class="disabled">Create Alphabet</span></li>
                    <li><span class="disabled">Create Country</span></li>
                    <small class="text-center">You need Archivist Permissions to Edit Language, Country, or Alphabet Data</small>
                @endif
            </ul>
        </div>
    </div>

@else
    <div class="row">
        <p>Your API Key is <h5><a href="{{ route('view_bible_filesets_permissions.user') }}"><code>{{ $user->id }}</code></a></h5></p>
    </div>
    <div class="row">
        <div class="medium-6 columns">
            <h2>Your account is not associated with an organization</h2>
            <div class="medium-8 columns centered">
                <p>If you want to do things like add new Bibles or edit meta data information you'll need to join one</p>
                <a href="{{ route('dashboard_organization_roles.create') }}" class="button expanded">Request to join one now!</a>
            </div>
        </div>
        <div class="medium-6 columns">
            <h2>You can query the API or Read Documentation</h2>
            <p>You don't need an official organization to query information about Bibles.</p>
            <a href="{{ route('swagger_v4') }}" class="button expanded">v4 Docs!</a>
            <a href="{{ route('swagger_v2') }}" class="button expanded">v2 Docs</a>
        </div>
    </div>
@endif

<div class="row">
    <h5>User Settings</h5>
    <form action="{{ route('users.update', ['id' => $user->id ]) }}" method="POST" enctype="multipart/form-data" data-abide novalidate>
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        @if($errors->any())
            <div data-abide-error class="alert callout">
                <p><i class="fi-alert"></i> There are some errors in your form:</p>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @include('dashboard.users.form')
    </form>
</div>


<div class="row">
    <h5>Social Account Connections</h5>
    <?php $providers = $user->accounts->pluck('provider')->ToArray(); ?>
    <nav class="social-auth">
        <a @if(!in_array('google',$providers)) class="disabled"  @endif href="{{ route('login.social_redirect', ['provider' => 'google']) }}"><svg class="icon"><use xlink:href="/img/icons/icons-social.svg#google"></use></svg></a>
        <a @if(!in_array('facebook',$providers)) class="disabled"  @endif href="{{ route('login.social_redirect', ['provider' => 'facebook']) }}"><svg class="icon"><use xlink:href="/img/icons/icons-social.svg#facebook"></use></svg></a>
        <a @if(!in_array('twitter',$providers)) class="disabled"  @endif href="{{ route('login.social_redirect', ['provider' => 'twitter']) }}"><svg class="icon"><use xlink:href="/img/icons/icons-social.svg#twitter"></use></svg></a>
        <a @if(!in_array('github',$providers)) class="disabled" @endif href="{{ route('login.social_redirect', ['provider' => 'github']) }}" href=""><svg class="icon"><use xlink:href="/img/icons/icons-social.svg#github"></use></svg></a>
    </nav>
</div>
