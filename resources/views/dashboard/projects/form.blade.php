{{ csrf_field() }}

<div class="mt30 columns is-multiline is-centered box">

    <div class="column is-6">
        <label class="label">{{ __('dashboard.projects.form.name') }}
            <input class="input" type="text" name="name" placeholder="name" value="{{ $project->name ?? old('name') }}">
        </label>
    </div>

    <div class="column is-6">
        <label class="label">{{ __('dashboard.projects.form.url_site') }}
            <input class="input" type="text" name="url_site" placeholder="Site" value="{{ $project->url_site ?? old('url_site') }}">
        </label>
    </div>

    <div class="column is-4">
        <label class="label">{{ __('dashboard.projects.form.logo') }}
            <div class="file">
                <label class="file-label">
                    <input class="file-input" type="file" name="url_avatar">
                    <span class="file-cta"><span class="file-icon"><i class="fas fa-upload"></i></span><span class="file-label">{{ __('dashboard.form.choose_file') }}</span></span>
                    <span class="file-name">{{ $project->url_avatar ?? old('url_avatar') }}</span>
                </label>
            </div>
        </label>
    </div>

    <div class="column is-4">
        <label class="label">{{ __('dashboard.projects.form.icon') }}
            <div class="file">
                <label class="file-label">
                    <input class="file-input" type="file" name="url_avatar_icon">
                    <span class="file-cta"><span class="file-icon"><i class="fas fa-upload"></i></span><span class="file-label">{{ __('dashboard.form.choose_file') }}</span></span>
                    <span class="file-name">{{ $project->url_avatar_icon ?? old('url_avatar_icon') }}</span>
                </label>
            </div>
        </label>
    </div>

    <div class="column is-12">
        <label class="label">{{ __('dashboard.form.description') }}
            <textarea class="textarea" name="description">
                {{ $project->description ?? old('description') }}
            </textarea>
        </label>
    </div>

    <div class="column is-12">
        <input class="button is-primary" type="submit">
        <label class="checkbox">
            <input type="checkbox" name="sensitive" @if($project->sensitive) checked @endif>{{ __('dashboard.projects.form.sensitive_description') }}
        </label>
    </div>

</div>