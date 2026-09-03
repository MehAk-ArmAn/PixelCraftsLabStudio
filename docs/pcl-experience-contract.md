# PixelCraftsLab — public experience contract

What Codex needs to expose in Admin so the public experience is data-driven.
Every value below already has a **working fallback in the frontend**, so nothing
breaks before the settings exist. Adding the setting simply takes over.

Frontend reads settings from `window.PCL_CMS.settings.*` (camelCase) and feature
booleans from `window.PCL_CMS.flags.*`.

> No setting here should ever accept raw CSS or JavaScript.

---

## 1. Home experience (the launch intro)

Read in the frontend as the `INTRO` config object.

| Admin setting | Payload key | Type | Default | Effect |
|---|---|---|---|---|
| `home_intro_enabled` | `flags.introEnabled` | bool | `true` | Master switch. Off = straight to the informational homepage. |
| `home_intro_replay_on_home` | `settings.introReplayOnHome` | bool | `true` | Clicking Home from any page replays the intro. |
| `home_intro_mode` | `settings.introMode` | enum | `forge` | `forge` \| `minimal` |
| `home_intro_heading` | `settings.introHeading` | string | `PixelCraftsLab` | Main line. |
| `home_intro_subheading` | `settings.introSubheading` | text | studio sentence | Supporting line. |
| `home_intro_cta` | `settings.introCta` | string | `Enter the studio` | Entry button. |
| `home_intro_duration` | `settings.introDuration` | int ms | `2600` | Auto-advance safety net (clamped 900–6000). Never fires under reduced motion. |
| `home_intro_intensity` | `settings.introIntensity` | float | `1` | Pointer-parallax strength (clamped 0–1.6). |
| `home_intro_accent_preset` | `settings.introAccentPreset` | enum | `violet-orange` | Accent ramp. |
| `home_intro_show_project_fragments` | `settings.introShowProjectFragments` | bool | `true` | Orbiting real project icons on/off. |
| `home_intro_interaction_preset` | `settings.introInteractionPreset` | enum | `pointer-parallax` | Interaction model. |
| `home_intro_background_preset` | `settings.introBackgroundPreset` | enum | `paper-grid` | Ground treatment. |
| `home_intro_transition_preset` | `settings.introTransitionPreset` | enum | `scatter` | How it morphs into the homepage. |

**Behaviour already implemented**

- Plays on arrival at `/` and on every deliberate Home navigation.
- Exits on scroll, wheel, touch-move, click, `Enter`, `Space` or `Esc`.
- Exit **scatters** the overlay outward while the homepage — already mounted
  underneath — is revealed. It is one continuous move, never a cut or reload.
- Under `prefers-reduced-motion` there is **no auto-exit**; the visitor gets a
  static branded card and must press the CTA. This is intentional: an
  auto-dismissing overlay is worse for motion-sensitive users than a deliberate one.
- It is **not** suppressed by `localStorage` any more.

---

## 2. Featured projects

Driven entirely by existing columns — **no new field required**.

| Concept | Source | Notes |
|---|---|---|
| Which projects | `projects.is_featured` | Falls back to the first three published projects. |
| Order | `projects.sort_order` | Lower first. Slot roles are positional: primary / secondary / tertiary. |
| Slot label | `projects.kind` | The admin's own words win; a shape-inferred label is the fallback. |
| Presentation | measured media | See below. |

Presentation is inferred from what each project *actually has*, so roles survive
reordering:

- **wide + phone capture** → cross-platform treatment (wide interface with a real
  phone screen overlapping it)
- **product family head** → connected-products strip of real sibling icons
- **wide only** → platform treatment

Current data ships as: `bangtan` → `studybuddy` → `alphablock`.
Changing `is_featured` / `sort_order` in Admin changes the homepage. Verified by
reordering to `alphablock, fikar, matchmallow` and confirming the slots followed.

### ⚠ One interim heuristic Codex should replace

There is **no parent/child link in the schema**, so the product-family head is
currently identified from authored copy (a featured, non-`Apps` project whose
`short`/`full_description` references "apps"), capped to one slot.

**Please add a real field** — `projects.is_ecosystem_head` (bool) or
`projects.parent_project_id` (nullable FK) — and swap the heuristic for it.
It is the only place in the public frontend still inferring a relationship
from prose.

---

## 3. Micro-experiences

Each interactive moment should be an admin row, never stored JavaScript.

| Field | Type | Notes |
|---|---|---|
| `enabled` | bool | |
| `page` | enum | `home` `work` `services` `marketing` `studio` `lab` `contact` |
| `section` | string | placement key within the page |
| `type` | enum | see presets below |
| `title` / `body` / `cta_label` / `cta_url` | string / text | copy |
| `accent_preset` | enum | `violet` `orange` `ink` `violet-orange` |
| `intensity` | float 0–1.6 | motion/interaction strength |
| `sort_order` | int | |

**Type presets currently implemented or supported by the frontend**

| Preset | Where | State |
|---|---|---|
| `logo_assemble` | home intro | live — the mark forges from brand layers with orbiting real work |
| `pixel_forge` | lab | live — paint/drag board already in the Lab |
| `project_stack` | work / project detail | live — screen stacks respond to cursor, second-image reveals |
| `growth_network` | marketing | live — pinned pipeline, channel constellation, funnel |
| `build_path` | services | live — stage picker walks Imagine → … → Grow |
| `signal_field` | marketing hero | live — growth curve + travelling signal |
| `mini_launch` | lab | **not built** — reserved name |

Any score in a Lab experiment must stay session-only and must never appear on a
business case study.

---

## 4. Page effects

| Admin setting | Payload key | Default |
|---|---|---|
| `page_transitions_enabled` | `flags.transitionsEnabled` | `true` |
| `custom_cursor_enabled` | `flags.cursorEnabled` | `true` |
| `ambient_decoration_enabled` | `flags.ambientEnabled` | `true` |
| `lab_page_enabled` | `flags.labEnabled` | `true` |
| `growth_page_enabled` | `flags.growthEnabled` | `true` |
| `testimonials_enabled` | `flags.testimonialsEnabled` | `true` |

Page transitions use **one language with per-destination character**, keyed by
route in `FLAVOR`: home `grid` · work `pixels` · services `columns` ·
growth `columns` · studio `brush` · lab `scatter` · contact `center` ·
project `pixels`.

---

## 5. Media rules the frontend enforces

- Assets are never rendered above native resolution (5% tolerance). Intrinsic
  dimensions are baked into `MEDIA_DIMS` — **regenerate it whenever project
  assets change** (see `docs/project-media-manifest.md`).
- Feature graphics and screenshots are classified separately by aspect ratio and
  are not interchangeable.
- Project visual priority is fixed: real icon → feature graphic → screenshot →
  artwork → neutral PCL fallback. **Initials are never used.**
