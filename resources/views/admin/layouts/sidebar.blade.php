@php
    $user = auth()->user();
    $unread = $user?->canManageAdministration()
        ? \App\Models\ContactSubmission::unread()->count()
        : 0;

    $link = function (string $route, string $label, ?int $badge = null, array $parameters = []) {
        $active = request()->routeIs($route) || request()->routeIs(str_replace('.index', '.*', $route));

        if ($parameters !== []) {
            $active = $active && collect($parameters)->every(
                fn ($value, $key) => (string) request()->route($key) === (string) $value,
            );
        }

        return ['route' => $route, 'label' => $label, 'badge' => $badge, 'active' => $active, 'parameters' => $parameters];
    };

    $groups = [
        'Overview' => [
            $link('admin.dashboard', 'Dashboard'),
        ],
        'Website' => [
            $link('admin.settings.edit', 'Home · Landing experience', null, ['group' => 'home']),
            $link('admin.home.featured-projects.index', 'Home · Featured projects'),
            $link('admin.pages.index', 'Pages'),
            $link('admin.projects.index', 'Projects'),
            $link('admin.services.index', 'Services'),
            $link('admin.process.index', 'Process'),
            $link('admin.team.index', 'Team'),
            $link('admin.testimonials.index', 'Testimonials'),
            $link('admin.navigation.index', 'Navigation'),
            $link('admin.socials.index', 'Social links'),
            $link('admin.media.index', 'Media'),
            $link('admin.experiences.index', 'Interactive experiences'),
        ],
        'Marketing' => [
            $link('admin.marketing.overview', 'Overview'),
            $link('admin.marketing-services.index', 'Marketing services'),
            $link('admin.growth-plans.index', 'Growth plans'),
            $link('admin.packages.index', 'Pricing'),
            $link('admin.campaigns.index', 'Campaigns'),
            $link('admin.channels.index', 'Channels'),
        ],
    ];

    if ($user?->canManageAdministration()) {
        $groups['Overview'][] = $link('admin.enquiries.index', 'Enquiries', $unread ?: null);
        $groups['Configuration'] = [
            $link('admin.contact-options.index', 'Contact options'),
            $link('admin.settings.edit', 'Settings'),
        ];
    }

    if ($user?->canManageSecurity()) {
        $groups['Security'] = [
            $link('admin.users.index', 'Admin users'),
            $link('admin.activity.index', 'Activity log'),
        ];
    }
@endphp

<aside class="sidebar">
  <a class="brand" href="{{ route('admin.dashboard') }}">
    <img src="{{ asset('assets/pcl-logo.png') }}" alt="">
    <span style="line-height:1;">
      <strong>PixelCraftsLab</strong>
      <span>Studio admin</span>
    </span>
  </a>

  @foreach ($groups as $group => $items)
    <div class="group">{{ $group }}</div>
    @foreach ($items as $item)
      <a class="nav {{ $item['active'] ? 'active' : '' }}" href="{{ route($item['route'], $item['parameters'] ?? []) }}">
        {{ $item['label'] }}
        @if ($item['badge'])<span class="pill">{{ $item['badge'] }}</span>@endif
      </a>
    @endforeach
  @endforeach

  <div class="foot">
    <div class="who">
      <b>{{ $user?->name }}</b>
      {{ $user?->roleLabel() }}
    </div>
    <form method="POST" action="{{ route('admin.logout') }}">
      @csrf
      <button class="btn ghost small" type="submit" style="color:rgba(246,244,240,0.8); border-color:rgba(246,244,240,0.24);">Sign out</button>
    </form>
  </div>
</aside>
