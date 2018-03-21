<form>
    @foreach($user->admin->organization->members as $user)
        <div class="medium-4 columns user shadow">
            <img class="user_avatar" src="/img/users/knight.svg" />
            <label>User Name: <input type="text" name="user_name" value="{{ $user->name or old('user_name') }}"/></label>
            @foreach($user->roles->where('organization_id',$user->admin->organization->id) as $role)
                <label>User Role:
                    <input type="text" name="user_role" value="{{ $role->role }}">
                </label>
            @endforeach
        </div>
    @endforeach
</form>