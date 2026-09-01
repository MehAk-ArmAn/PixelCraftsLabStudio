@extends('admin.layouts.app')
@section('title', 'Admin users')

@section('actions')
  <a class="btn small" href="{{ route('admin.users.create') }}">New admin user</a>
@endsection

@section('content')
<p class="muted" style="margin-top:0; max-width:70ch;">
  There is no public registration. Accounts are created here or with
  <span class="mono">php artisan pcl:admin</span>. Deactivating an account blocks it from signing in immediately.
</p>

<div class="card">
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>Name</th><th>Email</th><th>Role</th><th>Active</th><th>Last sign-in</th><th></th></tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
          <tr>
            <td><a href="{{ route('admin.users.edit', $user) }}"><strong>{{ $user->name }}</strong></a></td>
            <td class="small">{{ $user->email }}</td>
            <td><span class="badge {{ $user->isSuperAdmin() ? 'on' : '' }}">{{ $user->roleLabel() }}</span></td>
            <td><span class="badge {{ $user->is_active ? 'on' : 'off' }}">{{ $user->is_active ? 'Yes' : 'No' }}</span></td>
            <td class="small muted">{{ $user->last_login_at?->diffForHumans() ?? 'never' }}</td>
            <td class="actions">
              <a class="btn ghost small" href="{{ route('admin.users.edit', $user) }}">Edit</a>
              @unless (auth()->user()->is($user))
                <form class="inline" method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('Delete this admin account?');">
                  @csrf @method('DELETE')
                  <button class="btn danger small" type="submit">Delete</button>
                </form>
              @endunless
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  {{ $users->links() }}
</div>
@endsection
