{{--
    PixelCraftsLab error shell.

    Deliberately self-sufficient: no CMS query, no database read, no Vite
    manifest, no Google Fonts blocking, no Claude Design runtime. Everything
    needed to render is inline, so this page still works when the thing that
    broke is the thing that would normally draw the site.

    JS is optional decoration only — the page is complete without it.

    Variables (all optional except $code/$title):
      $code, $title, $message, $accent, $tone, $motif, $actions[], $note
--}}
@php
    $code    = $code    ?? 500;
    $accent  = $accent  ?? '#5B2394';
    $accent2 = $accent2 ?? '#8B45FF';
    $tone    = $tone    ?? 'calm';
    $motif   = $motif   ?? 'blocks';
    $isAdmin = request()->is('admin') || request()->is('admin/*');
    $signedIn = auth()->hasUser() && auth()->check();

    $actions = $actions ?? [];
    if (! $actions) {
        $actions = $isAdmin && $signedIn
            ? [['label' => 'Back to dashboard', 'href' => url('/admin'), 'primary' => true],
               ['label' => 'View website',      'href' => url('/')]]
            : [['label' => 'Back home',   'href' => url('/'), 'primary' => true],
               ['label' => 'View our work', 'href' => url('/') . '#work']];
    }
@endphp
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>{{ $code }} · {{ $title }} — PixelCraftsLab</title>
<style>
  :root{
    --ink:#0D0B12; --paper:#F6F4F0; --accent:{{ $accent }}; --accent2:{{ $accent2 }};
    --orange:#FF5F1F; --muted:rgba(13,11,18,.62); --line:rgba(13,11,18,.12);
  }
  *{box-sizing:border-box}
  html,body{margin:0;padding:0}
  body{
    min-height:100vh; background:var(--paper); color:var(--ink);
    font-family:Figtree,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
    -webkit-font-smoothing:antialiased; overflow-x:hidden;
    display:flex; flex-direction:column;
  }
  a{color:var(--accent); text-decoration:none}

  /* Ambient pixel field — pure CSS, no images required. */
  .field{position:fixed; inset:0; z-index:0; pointer-events:none;
    background-image:radial-gradient(circle,rgba(91,35,148,.16) 1.1px,transparent 1.1px);
    background-size:44px 44px}
  .glow{position:fixed; z-index:0; pointer-events:none; border-radius:50%;
    filter:blur(16px); opacity:.5}
  .glow.a{width:52vw; max-width:640px; aspect-ratio:1; right:-12vw; top:-16vh;
    background:radial-gradient(circle,var(--accent2),transparent 66%); animation:breathe 12s ease-in-out infinite}
  .glow.b{width:38vw; max-width:460px; aspect-ratio:1; left:-10vw; bottom:-18vh;
    background:radial-gradient(circle,rgba(255,95,31,.5),transparent 68%); animation:breathe 15s ease-in-out infinite 3s}

  header{position:relative; z-index:2; padding:22px clamp(18px,4vw,44px)}
  .brand{display:inline-flex; align-items:center; gap:11px; color:var(--ink)}
  .brand .mark{display:block; width:36px; height:36px; flex:0 0 auto; object-fit:contain}
  .brand .lockup{display:block; line-height:1}
  .brand .name{font-size:15px; font-weight:800; letter-spacing:-.02em; display:block; line-height:1}
  .brand .tag{display:block; margin-top:5px; font-family:ui-monospace,SFMono-Regular,Menlo,monospace;
    font-size:9px; letter-spacing:.18em; text-transform:uppercase; color:var(--muted)}

  main{position:relative; z-index:2; flex:1 1 auto; display:flex; align-items:center;
    padding:clamp(20px,5vw,60px) clamp(18px,4vw,44px) clamp(40px,7vw,80px)}
  .wrap{width:100%; max-width:1180px; margin:0 auto; display:flex; flex-wrap:wrap-reverse;
    gap:clamp(28px,5vw,64px); align-items:center}
  .copy{flex:1 1 420px; min-width:min(100%,300px)}

  .eyebrow{display:flex; align-items:center; gap:10px; font-family:ui-monospace,SFMono-Regular,Menlo,monospace;
    font-size:10px; letter-spacing:.26em; text-transform:uppercase; color:var(--muted)}
  .eyebrow i{width:7px; height:7px; background:var(--orange); display:block; animation:blink 1.7s infinite}

  h1{margin:18px 0 0; font-family:"Bricolage Grotesque",Figtree,sans-serif; font-weight:800;
    font-size:clamp(30px,5.2vw,60px); line-height:1.02; letter-spacing:-.045em; text-wrap:balance}
  h1 em{font-style:italic; font-weight:500; color:var(--accent)}
  p.lead{margin:18px 0 0; max-width:56ch; font-size:clamp(15px,1.3vw,17.5px); line-height:1.68; color:var(--muted); text-wrap:pretty}
  .note{margin-top:16px; padding:11px 15px; border-left:3px solid var(--orange);
    background:rgba(255,95,31,.07); font-family:ui-monospace,SFMono-Regular,Menlo,monospace;
    font-size:11.5px; line-height:1.75; color:rgba(13,11,18,.7)}

  .actions{display:flex; flex-wrap:wrap; gap:11px; margin-top:30px}
  .btn{display:inline-flex; align-items:center; gap:9px; padding:15px 23px; border-radius:12px;
    font-size:15px; font-weight:700; border:1px solid var(--line); color:var(--ink); background:transparent;
    font-family:inherit; cursor:pointer; transition:transform .28s cubic-bezier(.2,.8,.2,1),background .25s,border-color .25s,box-shadow .3s}
  .btn:hover{transform:translateY(-2px); border-color:var(--orange); background:rgba(255,95,31,.07)}
  .btn.p{background:linear-gradient(96deg,var(--accent),var(--accent2)); border-color:transparent; color:#fff;
    box-shadow:0 20px 44px -26px var(--accent2)}
  .btn.p:hover{box-shadow:0 26px 56px -22px var(--orange)}

  /* ---- status visual ---- */
  .visual{flex:1 1 380px; min-width:min(100%,280px); position:relative; display:flex;
    align-items:center; justify-content:center; min-height:clamp(200px,30vw,330px)}
  .code{font-family:"Bricolage Grotesque",Figtree,sans-serif; font-weight:800;
    font-size:clamp(110px,20vw,240px); line-height:.82; letter-spacing:-.06em;
    background-image:linear-gradient(140deg,var(--accent),var(--accent2) 52%,var(--orange));
    -webkit-background-clip:text; background-clip:text; color:transparent; user-select:none}

  /* Motif blocks: the "unfinished build" idea, drawn in CSS only. */
  .bits{position:absolute; inset:0; pointer-events:none}
  .bits i{position:absolute; display:block; background:var(--accent); opacity:.85}
  .bits i:nth-child(1){width:20px;height:20px; left:6%;  top:14%; background:var(--accent);  animation:drift 9s ease-in-out infinite}
  .bits i:nth-child(2){width:14px;height:14px; right:9%; top:22%; background:var(--orange);  animation:drift 11s ease-in-out infinite 1s}
  .bits i:nth-child(3){width:26px;height:26px; left:12%; bottom:16%; background:var(--accent2); animation:drift 13s ease-in-out infinite 2s}
  .bits i:nth-child(4){width:11px;height:11px; right:14%;bottom:12%; background:var(--ink);   animation:drift 10s ease-in-out infinite 3s}
  /* "escaping" pieces read as something coming apart — used by 404/5xx */
  .motif-broken .bits i:nth-child(2){animation:escape 6s cubic-bezier(.4,0,.2,1) infinite}
  .motif-broken .bits i:nth-child(4){animation:escape 7.5s cubic-bezier(.4,0,.2,1) infinite 1.6s}
  /* a slow sweep reads as waiting — used by 408/429/503/504 */
  .motif-wait .code{position:relative}
  .motif-wait .code::after{content:""; position:absolute; left:0; right:0; bottom:-14px; height:4px;
    border-radius:99px; background:linear-gradient(90deg,var(--accent),var(--orange));
    transform-origin:left; animation:sweep 2.6s ease-in-out infinite}
  /* a firm bar reads as a boundary — used by 401/403 */
  .motif-locked .code{position:relative}
  .motif-locked .code::after{content:""; position:absolute; left:-4%; right:-4%; top:50%; height:8px;
    background:var(--ink); transform:translateY(-50%) rotate(-8deg)}

  footer{position:relative; z-index:2; padding:0 clamp(18px,4vw,44px) 26px;
    font-family:ui-monospace,SFMono-Regular,Menlo,monospace; font-size:10px;
    letter-spacing:.14em; text-transform:uppercase; color:rgba(13,11,18,.42)}

  @keyframes blink{0%,49%{opacity:1}50%,100%{opacity:.15}}
  @keyframes drift{0%,100%{transform:translate(0,0)}50%{transform:translate(7px,-11px)}}
  @keyframes escape{0%{transform:translate(0,0) rotate(0);opacity:.85}
    70%{opacity:.85}100%{transform:translate(90px,-70px) rotate(140deg);opacity:0}}
  @keyframes sweep{0%{transform:scaleX(.06)}50%{transform:scaleX(1)}100%{transform:scaleX(.06)}}
  @keyframes breathe{0%,100%{opacity:.34;transform:scale(1)}50%{opacity:.6;transform:scale(1.08)}}

  @media (prefers-reduced-motion:reduce){
    *,*::before,*::after{animation-duration:.001ms!important;animation-iteration-count:1!important;
      transition-duration:.12s!important}
    .motif-wait .code::after{transform:scaleX(1)}
  }
  @media (max-width:720px){
    .wrap{flex-direction:column-reverse; align-items:flex-start}
    .visual{min-height:150px; width:100%; justify-content:flex-start}
    .code{font-size:clamp(88px,30vw,150px)}
  }
</style>
</head>
<body class="motif-{{ $motif }}" data-pcl-error-shell="{{ $code }}">
  <div class="field" aria-hidden="true"></div>
  <div class="glow a" aria-hidden="true"></div>
  <div class="glow b" aria-hidden="true"></div>

  <header>
    <a class="brand" href="{{ url('/') }}">
      {{-- The real mark, with an inline SVG fallback if /assets can't be served. --}}
      <img class="mark" src="{{ url('/assets/pcl-logo.png') }}" alt=""
           onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
      <svg class="mark" viewBox="0 0 96 96" fill="none" aria-hidden="true" style="display:none">
        <rect x="42" y="6" width="12" height="44" rx="3" fill="#0D0B12"/>
        <rect x="38" y="48" width="20" height="12" rx="3" fill="#8B45FF"/>
        <path d="M39 60C35 78 41 90 48 96c7-6 13-18 9-36Z" fill="#FF5F1F"/>
      </svg>
      <span class="lockup">
        <span class="name">PixelCraftsLab</span>
        <span class="tag">Ideas . Build . Launch</span>
      </span>
    </a>
  </header>

  <main>
    <div class="wrap">
      <div class="copy">
        <div class="eyebrow"><i aria-hidden="true"></i>@yield('eyebrow', 'Error ' . $code)</div>
        <h1>@yield('headline')</h1>
        <p class="lead">@yield('body')</p>
        @hasSection('note')
          <div class="note">@yield('note')</div>
        @endif
        <div class="actions">
          @foreach ($actions as $a)
            <a class="btn {{ ($a['primary'] ?? false) ? 'p' : '' }}" href="{{ $a['href'] }}">{{ $a['label'] }}</a>
          @endforeach
          @hasSection('extra-action')@yield('extra-action')@endif
        </div>
      </div>

      <div class="visual" aria-hidden="true">
        <div class="bits"><i></i><i></i><i></i><i></i></div>
        <div class="code">{{ $code }}</div>
      </div>
    </div>
  
@include('errors.partials.home-action')

</main>

  <footer>{{ $isAdmin ? 'PixelCraftsLab Studio admin' : 'PixelCraftsLab Studio' }}</footer>
</body>
</html>
