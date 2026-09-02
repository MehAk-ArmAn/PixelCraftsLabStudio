# Project media manifest

Internal record of where every public project image came from. Not published.

Captured **2 September 2026** with Playwright (Chromium, deviceScaleFactor 2) for
live sites, and from our own Google Play listings for the apps. All assets are
PixelCraftsLab-owned work or our own store material.

Processing: `sips` resize → `cwebp`. Web heroes capped at 1600px wide, phone
captures at 1200px tall, icons at 256px. **43 assets, 2.2 MB total.**

Local root: `public/assets/projects/{slug}/`

---

## Live web products — captured from the running site

| Project | Source | Assets | Screens |
|---|---|---|---|
| Fikar-e-Adab | https://fikareadab.com/ | `fikar/hero.webp`, `feature-01.webp`, `mobile-01.webp` | Home / literary world, journey section, mobile home |
| The Farm Care | https://thefarmcare.com/ | `farmcare/hero.webp`, `feature-01.webp`, `mobile-01.webp` | Product hero, catalogue section, mobile home |
| Study Buddy | https://www.studybuddy.fun/ | `studybuddy/hero.webp`, `feature-01.webp`, `mobile-01.webp` | Landing hero, roles/apps section, mobile home |
| BangTan | https://bangtan.info/ | `bangtan/hero.webp`, `feature-01.webp`, `mobile-01.webp` | ARMY homebase hero, vaults section, mobile home |
| Alpha Block Solutions | https://alphablocksolutions.com/ | `alphablock/hero.webp`, `feature-01.webp`, `mobile-01.webp` | Market dashboard hero, headlines, mobile home |
| Pulse | https://alphablocksolutions.com/pulse | `pulse/hero.webp` | Pulse Trading Intelligence hero + workflow |

`fikareadab.com` rejects plain HTTP clients (403); a real browser context works.

## Mobile apps — official Google Play listing assets (our own apps)

Screenshots pulled at source resolution from `play-lh.googleusercontent.com`
(Google's size directive stripped, re-requested at `=w1080` / `=s512`).
These are the developer-supplied captures, not screenshots of the Play page —
no store chrome, no browser UI.

| Project | Store listing | Assets |
|---|---|---|
| Abandoned City: Zombie Attack | `com.pixelcraftslab.abandonedcity` | `abandoned/hero.webp`, `screen-01`, `screen-02`, `icon` |
| MatchMallow: Memory Match | `com.pixelcraftslab.matchmallow` | `matchmallow/hero.webp` (landscape), `screen-01`, `screen-02`, `icon` |
| Coloriboo: Learn Colors | `com.pixelcraftslab.coloriboo` | `coloriboo/hero.webp`, `screen-01`, `screen-02`, `icon` |
| Mathibble: Kids Math Games | `com.pixelcraftslab.mathibble` | `mathibble/hero.webp`, `screen-01`, `screen-02`, `icon` |
| Animal Adventure: Learn & Play | `com.pixelcraftslab.animalkingdom` | `animal/hero.webp`, `screen-01`, `screen-02`, `icon` |
| Bloxabet: Learn ABC | `com.pixelcraftslab.abclearning` | `bloxabet/hero.webp`, `screen-01`, `screen-02`, `icon` |
| GlobePop: Learn Flags | `com.pixelcraftslab.flagquest` | `globepop/hero.webp`, `screen-01`, `screen-02`, `icon` |

Store URL `.../store/apps/details?id=<package>`.

---

## Corrections made from verified sources

- **Pulse** was recorded as "Coming soon", no URL. It is **live** at
  `alphablocksolutions.com/pulse` — URL, copy and hero added.
- **Alpha Block Solutions** was "Lab / Coming soon / In build". It is a **live
  platform**; moved to `Web`, kind set to "Market intelligence platform".
- **Lab section labels** changed from "Not yet shipped" / "In build" to
  "Studio-built" / "In-house", which is now accurate.
- Scraped Play "category" was discarded — it read *Kids* for a zombie shooter
  (a nav artefact). Existing correct categories kept.

## Not used, deliberately

- **The Farm Care** publishes "Trusted Since 2011 · 300+ Veterinary Stores ·
  250+ Farm Setups" on its own site. These are the **client's** claims and are
  **not** restated anywhere as PixelCraftsLab results.
- **Abandoned City** raw gameplay captures (`shot-03`/`shot-05`) are untextured
  and weaker than the developer's polished store captures; store captures used.
- No icons wired for web projects — none needed by the current card design.

## Gaps

- No second screen for **Pulse** (single hero only); the deeper pages sit behind
  login. Gallery is empty for that project by design.
- No Apple App Store listings exist for any title — Google Play only.
- No marketing/campaign case-study imagery exists. The Growth page still shows
  its honest empty state; nothing was fabricated.

## For Codex

Media is stored as **static public paths** (`assets/projects/…`) on
`projects.primary_image` and `projects.gallery`, resolved by `MediaResolver`.
Nothing was added to the Media Library table, and no migration was touched.
If the SQLite→MySQL importer re-seeds `projects`, **re-run the population step**
or these paths will be lost — the values are listed above.
