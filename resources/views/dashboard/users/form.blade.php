<div class="row">
    <label>{{ trans('user.name') }}
        <input type="text" name="name" value="{{ (isset($user->name)) ? $user->name : old('name') }}">
    </label>
    <label>{{ trans('user.nickname') }}
        <input type="text" name="nickname" value="{{ (isset($user->nickname)) ? $user->nickname : old('nickname') }}">
    </label>
    <label>{{ trans('user.avatar') }}
        <input type="file" name="avatar">
    </label>
    <label>{{ trans('user.email') }}
        <input type="text" name="email" value="{{ (isset($user->email)) ? $user->email : old('email') }}">
    </label>
    <input type="submit" class="button">
</div>
