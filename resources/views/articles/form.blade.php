

    {{ csrf_field() }}
    <div class="row">
        <div class="small-4 columns">
            <input type="text" name="title" value="{{ $article->title or old('title') }}" placeholder="Title" />
        </div>
        <div class="small-4 columns">
            <input type="text" name="subtitle" value="{{ $article->subtitle or old('subtitle') }}" placeholder="Subtitle" />
        </div>
        <div class="small-4 columns">
                <input type="text" name="subtitle" value="{{ $article->tags or old('tags') }}" placeholder="tags" />
        </div>
        <div class="small-6 columns">
            <label>Description
            <textarea name="description">{{ $article->description or old('description') }}</textarea></label>
        </div>

        <div class="small-12 columns">
            <label>Body
            <textarea name="body">{{ $article->body or old('body') }}</textarea></label>
            <input type="submit">
        </div>
    </div>