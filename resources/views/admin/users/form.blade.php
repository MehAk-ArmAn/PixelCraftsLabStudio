@extends('admin.layouts.app')
@section('title', $mode === 'create' ? 'New admin user' : 'Edit '.$user->name)

@section('actions')
  <a class="btn ghost small" href="{{ route('admin.users.index') }}">All admin users</a>
@endsection

@section('content')
@php $isSelf = $mode === 'edit' && auth()->user()->is($user); @endphp

<div class="card" style="max-width:560px;">
  <form method="POST" action="{{ $mode === 'create' ? route('admin.users.store') : route('admin.users.update', $user) }}">
    @csrf
    @if ($mode !== 'create') @method('PUT') @endif

    <label class="field"><span class="lab">Name</span><input type="text" name="name" value="{{ old('name', $user->name) }}" required></label>
    <label class="field"><span class="lab">Email</span><input type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="off"></label>

    <label class="field">
      <span class="lab">Password{{ $mode === 'create' ? '' : ' (leave blank to keep current)' }}</span>
      <input type="password" name="password" autocomplete="new-password" @required($mode === 'create')>
      <span class="help">At least 10 characters, with letters and numbers.</span>
    </label>
    <label class="field">
      <span class="lab">Confirm password</span>
      <input type="password" name="password_confirmation" autocomplete="new-password" @required($mode === 'create')>
    </label>

    <label class="field">
      <span class="lab">Role</span>
      <select name="role" @disabled($isSelf)>
        <option value="super_admin" @selected($user->role === 'super_admin')>Super admin — everything, including admin users</option>
        <option value="admin" @selected($user->role === 'admin')>Admin — all content, enquiries and media</option>
        <option value="editor" @selected($user->role === 'editor')>Editor — content and media only</option>
      </select>
      @if ($isSelf)
        <span class="help">You cannot change your own role or deactivate yourself.</span>
        <input type="hidden" name="role" value="super_admin">
      @endif
    </label>

    <label class="check">
      <input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" @checked($user->is_active) @disabled($isSelf)>
      <span>Active</span>
    </label>

    <button class="btn" type="submit">{{ $mode === 'create' ? 'Create account' : 'Save changes' }}</button>
  </form>
</div>
@endsection
