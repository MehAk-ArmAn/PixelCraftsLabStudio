@php
    $status = (int) ($status ?? 500);
    $title = (string) ($title ?? 'Something went wrong.');
    $message = (string) ($message ?? 'We could not complete that request. Please try again shortly.');
    $request = app()->bound('request') ? app('request') : null;
    $isAuthenticatedAdmin = $request?->attributes->get('pcl.authenticated_admin', false) === true;
@endphp
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $status }} — PixelCraftsLab</title>
    <style>
        :root {
            color-scheme: light;
            --paper: #f5f1ea;
            --ink: #110e17;
            --muted: #6f6875;
            --violet: #6731b8;
            --orange: #ff5f1f;
            --line: rgba(17, 14, 23, .18);
        }

        * { box-sizing: border-box; }

        html, body { min-height: 100%; }

        body {
            margin: 0;
            background:
                radial-gradient(circle at 84% 16%, rgba(103, 49, 184, .13), transparent 26rem),
                radial-gradient(circle at 10% 88%, rgba(255, 95, 31, .12), transparent 24rem),
                var(--paper);
            color: var(--ink);
            font-family: Inter, ui-sans-serif, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        a { color: inherit; }

        .error-shell {
            min-height: 100vh;
            min-height: 100svh;
            display: grid;
            grid-template-rows: auto 1fr auto;
            padding: clamp(1.25rem, 3vw, 2.75rem);
            overflow: hidden;
        }

        .error-header,
        .error-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            position: relative;
            z-index: 2;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: .7rem;
            text-decoration: none;
            font-size: .82rem;
            font-weight: 800;
            letter-spacing: -.02em;
        }

        .brand-mark {
            width: 2rem;
            aspect-ratio: 1;
            display: grid;
            place-items: center;
            background: var(--ink);
            color: var(--paper);
            border-radius: 50%;
            font-size: .62rem;
            letter-spacing: -.08em;
        }

        .system-label,
        .error-kicker,
        .error-footer {
            font-size: .68rem;
            font-weight: 750;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .system-label,
        .error-footer { color: var(--muted); }

        .error-main {
            width: min(100%, 76rem);
            margin: auto;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(17rem, .7fr);
            gap: clamp(3rem, 8vw, 8rem);
            align-items: center;
            padding: 5rem 0;
        }

        .error-kicker {
            display: flex;
            align-items: center;
            gap: .75rem;
            margin: 0 0 1.6rem;
            color: var(--violet);
        }

        .error-kicker::before {
            content: "";
            width: 2.4rem;
            height: 2px;
            background: var(--orange);
        }

        h1 {
            max-width: 14ch;
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(3rem, 7vw, 6.8rem);
            font-weight: 500;
            letter-spacing: -.065em;
            line-height: .91;
        }

        .error-message {
            max-width: 38rem;
            margin: 1.7rem 0 0;
            color: var(--muted);
            font-size: clamp(1rem, 1.5vw, 1.18rem);
            line-height: 1.65;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            margin-top: 2rem;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 3rem;
            padding: .8rem 1.15rem;
            border: 1px solid var(--ink);
            border-radius: 999px;
            font-size: .76rem;
            font-weight: 800;
            letter-spacing: .06em;
            text-decoration: none;
            text-transform: uppercase;
            transition: transform .18s ease, background-color .18s ease, color .18s ease;
        }

        .button:hover { transform: translateY(-2px); }

        .button-primary {
            background: var(--ink);
            color: var(--paper);
        }

        .button-secondary:hover {
            background: rgba(17, 14, 23, .06);
        }

        .error-art {
            position: relative;
            min-height: clamp(18rem, 38vw, 31rem);
            display: grid;
            place-items: center;
            isolation: isolate;
        }

        .error-number {
            position: relative;
            z-index: 2;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(7rem, 18vw, 14rem);
            line-height: 1;
            letter-spacing: -.1em;
            text-indent: -.1em;
        }

        .error-art::before,
        .error-art::after {
            content: "";
            position: absolute;
            border-radius: 50%;
        }

        .error-art::before {
            width: 78%;
            aspect-ratio: 1;
            background: var(--orange);
            transform: translate(8%, -4%);
            z-index: 0;
        }

        .error-art::after {
            width: 58%;
            aspect-ratio: 1;
            border: clamp(.75rem, 2vw, 1.6rem) solid var(--violet);
            transform: translate(-24%, 18%);
            z-index: 1;
        }

        .error-footer {
            padding-top: 1.1rem;
            border-top: 1px solid var(--line);
        }

        @media (max-width: 760px) {
            .system-label { display: none; }

            .error-main {
                grid-template-columns: 1fr;
                gap: 2rem;
                padding: 3rem 0;
            }

            .error-art {
                grid-row: 1;
                min-height: 13rem;
            }

            .error-number { font-size: clamp(7rem, 36vw, 11rem); }

            h1 { font-size: clamp(2.8rem, 14vw, 4.8rem); }
        }

        @media (prefers-reduced-motion: reduce) {
            .button { transition: none; }
        }
    </style>
</head>
<body>
    <div class="error-shell" data-pcl-error-shell>
        <header class="error-header">
            <a class="brand" href="/" aria-label="PixelCraftsLab home">
                <span class="brand-mark" aria-hidden="true">PCL</span>
                <span>PixelCraftsLab Studio</span>
            </a>
            <span class="system-label">A small interruption</span>
        </header>

        <main class="error-main">
            <section>
                <p class="error-kicker">Error {{ $status }}</p>
                <h1>{{ $title }}</h1>
                <p class="error-message">{{ $message }}</p>

                <nav class="error-actions" aria-label="Recovery options">
                    @if ($isAuthenticatedAdmin)
                        <a class="button button-primary" href="/admin">Back to dashboard</a>
                        <a class="button button-secondary" href="/">View website</a>
                    @else
                        <a class="button button-primary" href="/">Back home</a>
                        <a class="button button-secondary" href="/work">View our work</a>
                        <a class="button button-secondary" href="/contact">Contact the studio</a>
                    @endif
                </nav>
            </section>

            <div class="error-art" aria-hidden="true">
                <span class="error-number">{{ $status }}</span>
            </div>
        </main>

        <footer class="error-footer">
            <span>Ideas · Build · Launch · Grow</span>
            <span>PixelCraftsLab</span>
        </footer>
    </div>
</body>
</html>
