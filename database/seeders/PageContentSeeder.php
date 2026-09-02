<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageSection;
use Illuminate\Database\Seeder;

/**
 * Every string the public site renders that is not a projects/services/team
 * record. Values are the originals from the locked design document, so the
 * seeded CMS reproduces the current site exactly.
 *
 * Placeholders such as {count} are replaced by the frontend with live figures.
 */
class PageContentSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $order => $page) {
            $record = Page::firstOrCreate(
                ['key' => $page['key']],
                [
                    'title' => $page['title'],
                    'slug' => $page['key'],
                    'is_published' => true,
                    'sort_order' => ($order + 1) * 10,
                    'seo_title' => $page['seo_title'] ?? null,
                    'seo_description' => $page['seo_description'] ?? null,
                    'robots_index' => true,
                ],
            );

            foreach ($page['sections'] as $index => $section) {
                $sectionRecord = PageSection::firstOrCreate(
                    ['page_id' => $record->id, 'section_key' => $section['key']],
                    [
                        'label' => $section['label'],
                        'eyebrow' => $section['eyebrow'] ?? null,
                        'heading' => $section['heading'] ?? null,
                        'subheading' => $section['subheading'] ?? null,
                        'body' => $section['body'] ?? null,
                        'cta_label' => $section['cta_label'] ?? null,
                        'cta_url' => $section['cta_url'] ?? null,
                        'secondary_cta_label' => $section['cta2_label'] ?? null,
                        'secondary_cta_url' => $section['cta2_url'] ?? null,
                        'settings' => $section['settings'] ?? [],
                        'sort_order' => ($index + 1) * 10,
                        'is_enabled' => true,
                    ],
                );

                if ($page['key'] === 'growth' && $section['key'] === 'plans') {
                    $legacyBody = 'Three starting points. Every engagement is scoped to the business in front of us, so pricing is quoted rather than listed.';

                    if ($sectionRecord->body === $legacyBody) {
                        $sectionRecord->body = $section['body'];
                    }

                    $sectionRecord->settings = array_replace($section['settings'] ?? [], $sectionRecord->settings ?? []);
                    $sectionRecord->save();
                }
            }
        }
    }

    /** @return list<array<string, mixed>> */
    private function pages(): array
    {
        return [
            $this->home(),
            $this->work(),
            $this->project(),
            $this->services(),
            $this->growth(),
            $this->pricing(),
            $this->studio(),
            $this->lab(),
            $this->contact(),
        ];
    }

    private function home(): array
    {
        return [
            'key' => 'home',
            'title' => 'Home',
            'seo_title' => 'PixelCraftsLab Studio — Ideas. Build. Launch. Grow.',
            'seo_description' => 'A creative technology studio building websites, apps, games and brand experiences — and the marketing that takes them to an audience.',
            'sections' => [
                [
                    'key' => 'hero', 'label' => 'Hero',
                    'eyebrow' => 'Creative technology studio · UK',
                    'heading' => 'Bring your idea',
                    'subheading' => 'to',
                    'body' => 'From websites and branding to UI/UX and digital experiences, we help turn ideas into polished, professional work that feels modern, purposeful, and ready to launch.',
                    'cta_label' => 'See the work', 'cta_url' => '#work',
                    'cta2_label' => 'Start a project', 'cta2_url' => '#contact',
                    'settings' => ['emphasis' => 'life', 'badgeLabel' => 'Now live'],
                ],
                [
                    'key' => 'reveal', 'label' => 'Scroll reveal',
                    'eyebrow' => 'Built in the lab · Web platform',
                    'cta_label' => 'Open project →',
                    'settings' => ['scrollHint' => 'Scroll — the pixels are assembling'],
                ],
                [
                    'key' => 'craft', 'label' => 'What we do',
                    'eyebrow' => '01 — What we do',
                    'heading' => 'Craft',
                    'body' => 'From websites and apps to interactive projects and games, we focus on building clean, modern, and well-structured digital experiences — then on the marketing and growth work that puts them in front of the right people. Everything we create is designed to feel smooth, look sharp, and work the way it’s supposed to.',
                ],
                [
                    'key' => 'selected_work', 'label' => 'Selected work',
                    'eyebrow' => '02 — Selected work',
                    'heading' => 'Where creativity meets tech',
                    'cta_label' => 'All {count} projects →', 'cta_url' => '#work',
                ],
                [
                    'key' => 'capabilities', 'label' => 'Capabilities',
                    'eyebrow' => '03 — Capabilities',
                    'heading' => 'The disciplines that made that work',
                    'cta_label' => 'How we work →', 'cta_url' => '#services',
                ],
                [
                    'key' => 'behind', 'label' => 'Behind the finish',
                    'eyebrow' => '04 — Behind the finish',
                    'heading' => 'Every polished build',
                    'settings' => ['heading2' => 'starts out rough.'],
                ],
                [
                    'key' => 'process', 'label' => 'How we build',
                    'eyebrow' => 'How we build',
                    'body' => 'Three words on our logo, and the way every project actually runs.',
                    'cta_label' => 'Inside the studio →', 'cta_url' => '#studio',
                    'settings' => [
                        'word1' => 'Ideas.', 'word2' => 'Build.', 'word3' => 'Launch.',
                        'step1No' => '01', 'step1Title' => 'Ideas',
                        'step1Body' => 'We start from the problem, not the pixels. Scope, audience and what the product actually has to do get settled before anything is designed.',
                        'step2No' => '02', 'step2Title' => 'Build',
                        'step2Body' => 'Clean code and strong UI design together — clear, intuitive, visually balanced interfaces on architecture built to be scalable and secure.',
                        'step3No' => '03', 'step3Title' => 'Launch',
                        'step3Body' => 'Ship it, then keep it healthy: performance tuning, ongoing updates and technical support so the product keeps running the way it should.',
                    ],
                ],
                [
                    'key' => 'cta', 'label' => 'Closing CTA',
                    'heading' => 'Ready to build something',
                    'body' => 'Let’s create digital experiences that make your brand stand out.',
                    'cta_label' => 'Start a Project', 'cta_url' => '#contact',
                    'settings' => ['emphasis' => 'amazing?'],
                ],
            ],
        ];
    }

    private function work(): array
    {
        return [
            'key' => 'work',
            'title' => 'Work',
            'seo_title' => 'Work — PixelCraftsLab Studio',
            'seo_description' => 'Live websites, apps, games and growth work from PixelCraftsLab Studio.',
            'sections' => [
                [
                    'key' => 'header', 'label' => 'Header',
                    'eyebrow' => 'Portfolio — {count} live projects',
                    'heading' => 'Our', 'subheading' => 'Solutions',
                    'body' => 'Where creativity meets tech.',
                    'settings' => ['body2' => 'Explore our projects below!', 'badge' => 'In build'],
                ],
                [
                    'key' => 'closing', 'label' => 'Closing',
                    'heading' => 'Every one of these is live. Open any of them.',
                    'cta_label' => 'Start yours', 'cta_url' => '#contact',
                ],
            ],
        ];
    }

    private function project(): array
    {
        return [
            'key' => 'project',
            'title' => 'Project detail',
            'sections' => [
                [
                    'key' => 'detail', 'label' => 'Labels',
                    'settings' => [
                        'backLabel' => 'All work',
                        'visitLabel' => 'Visit the live project',
                        'inBuildLabel' => 'Currently in build',
                        'mediaPlaceholder' => '[ PLACEHOLDER — drop real project screenshots in here ]',
                        'builtLabel' => 'What we built',
                        'casePlaceholder' => '[ PLACEHOLDER ] Brief, role, timeline and outcome for this project — send us the details and they slot in here.',
                        'capsLabel' => 'Capabilities used',
                        'nextLabel' => 'Next project',
                        'metaDiscipline' => 'Discipline',
                        'metaPlatform' => 'Platform',
                        'metaCategory' => 'Category',
                        'metaStatus' => 'Status',
                        'statusLive' => 'Live',
                        'statusBuild' => 'In build',
                    ],
                ],
                [
                    'key' => 'marketing', 'label' => 'Case study labels',
                    'settings' => [
                        'goalLabel' => 'Client goal',
                        'challengeLabel' => 'The problem',
                        'audienceLabel' => 'Audience',
                        'strategyLabel' => 'Strategy',
                        'approachLabel' => 'Content & campaign approach',
                        'deliverablesLabel' => 'Deliverables',
                        'resultsLabel' => 'Results',
                        'lessonsLabel' => 'What we’d do next',
                        'metricsLabel' => 'Measured',
                        'periodLabel' => 'Campaign period',
                    ],
                ],
            ],
        ];
    }

    private function services(): array
    {
        return [
            'key' => 'services',
            'title' => 'Services',
            'seo_title' => 'Services — PixelCraftsLab Studio',
            'seo_description' => 'Apps, games, web platforms, UI/UX, performance, support — and digital marketing and growth.',
            'sections' => [
                [
                    'key' => 'header', 'label' => 'Header',
                    'eyebrow' => 'Services — capabilities',
                    'heading' => 'Watch what we do',
                    'body' => '{count} capabilities, end to end. Each one below is running a live miniature of the actual work.',
                ],
                [
                    'key' => 'closing', 'label' => 'Closing',
                    'heading' => 'Tell us which one you need.',
                    'cta_label' => 'Start a project', 'cta_url' => '#contact',
                ],
            ],
        ];
    }

    private function growth(): array
    {
        return [
            'key' => 'growth',
            'title' => 'Growth',
            'seo_title' => 'Marketing & Growth — PixelCraftsLab Studio',
            'seo_description' => 'Strategy, social media, content, SEO and campaigns that take digital products to the right audience — and report honestly on what happened.',
            'sections' => [
                [
                    'key' => 'hero', 'label' => 'Hero',
                    'eyebrow' => 'Marketing & growth · UK',
                    'heading' => 'Build it.',
                    'body' => 'Shipping a product is the easy half. We plan and run the marketing that finds its audience — strategy, social, content, search and campaigns — built by the same studio that builds the product, and measured against goals set before anything goes live.',
                    'cta_label' => 'Start a project', 'cta_url' => '#contact',
                    'cta2_label' => 'See the work', 'cta2_url' => '#work',
                    'settings' => ['emphasis' => 'Then grow it.'],
                ],
                [
                    'key' => 'capabilities', 'label' => 'Capabilities',
                    'eyebrow' => '01 — What we grow',
                    'heading' => 'Marketing, run like engineering',
                    'body' => 'Define the problem, choose the channels that fit it, build the work, then measure whether it moved. Every capability below is a real piece of work with a stated outcome — not a line on a pitch deck.',
                ],
                [
                    'key' => 'social', 'label' => 'Social media',
                    'eyebrow' => '02 — Social',
                    'heading' => 'Social that has a strategy behind it',
                    'body' => 'Channel strategy, content pillars, calendars, short-form video concepts, creative direction and community engagement — planned as ongoing work rather than posted when someone remembers. We do not promise followers or virality; we plan for reach, engagement and the audience that actually converts.',
                ],
                [
                    'key' => 'strategy', 'label' => 'Growth strategy',
                    'eyebrow' => '03 — Strategy',
                    'heading' => 'Where growth actually comes from',
                    'body' => 'Audits, positioning, audience and competitor research, customer journey mapping, and acquisition, retention and conversion strategy — turned into a 90-day plan and a quarterly roadmap you could execute without us.',
                ],
                [
                    'key' => 'channels', 'label' => 'Channels',
                    'eyebrow' => '04 — Channels',
                    'heading' => 'The places we work',
                    'body' => 'We pick channels because they suit the audience, not because they are available.',
                ],
                [
                    'key' => 'plans', 'label' => 'Growth plans',
                    'eyebrow' => '05 — Growth plans',
                    'heading' => 'Ways to work together',
                    'body' => 'Choose a focused strategy project, monthly marketing management, performance growth, or a full growth partnership. Core prices are listed below and every scope remains editable.',
                    'settings' => [
                        'engagement1' => 'Strategy Project',
                        'engagement2' => 'Monthly Marketing Management',
                        'engagement3' => 'Performance / Growth',
                        'engagement4' => 'Full Growth Partnership',
                    ],
                ],
                [
                    'key' => 'process', 'label' => 'Process',
                    'eyebrow' => '06 — How we work',
                    'heading' => 'Discover, strategize, create, launch, measure',
                    'body' => 'The marketing process runs on its own track. It borrows the studio’s discipline without forcing engineering language onto creative work.',
                ],
                [
                    'key' => 'cases', 'label' => 'Case studies',
                    'eyebrow' => '07 — Case studies',
                    'heading' => 'Growth work, documented',
                    'body' => 'Marketing case studies are published here once the client has approved the detail.',
                    'settings' => ['empty' => 'No marketing case studies are published yet. We would rather show nothing than invent numbers.'],
                ],
                [
                    'key' => 'analytics', 'label' => 'Measurement',
                    'eyebrow' => '08 — Measurement',
                    'heading' => 'Reported in plain language',
                    'body' => 'Reach, engagement, traffic, leads, conversion rate, CTR, audience growth, search visibility and email performance — tracked properly and reported against the goals we set at the start. Only figures we can stand behind are ever published.',
                ],
                [
                    'key' => 'cta', 'label' => 'Closing CTA',
                    'heading' => 'Got something that deserves an audience?',
                    'body' => 'Tell us what you have built, or what you are about to.',
                    'cta_label' => 'Start a project', 'cta_url' => '#contact',
                ],
            ],
        ];
    }

    private function pricing(): array
    {
        return [
            'key' => 'pricing',
            'title' => 'Pricing',
            'seo_title' => 'Pricing — PixelCraftsLab Studio',
            'seo_description' => 'Transparent PixelCraftsLab pricing for growth, social media, content, paid media, SEO, WhatsApp, automation and launches.',
            'sections' => [],
        ];
    }

    private function studio(): array
    {
        return [
            'key' => 'studio',
            'title' => 'Studio',
            'seo_title' => 'Studio — PixelCraftsLab',
            'seo_description' => 'A small creative technology studio in the UK. Four people, shipped products, and the marketing that grows them.',
            'sections' => [
                [
                    'key' => 'header', 'label' => 'Header',
                    'eyebrow' => 'Studio — who we are',
                    'heading' => 'Crafting digital experiences where',
                    'settings' => ['wordA' => 'creativity', 'wordB' => 'meets', 'wordC' => 'precision'],
                ],
                [
                    'key' => 'name', 'label' => 'Our name',
                    'heading' => 'Our name, read literally',
                    'settings' => [
                        'part1Key' => 'PIXEL',
                        'part1Body' => 'The raw material. Blocks, grids and fragments — the smallest unit of anything digital we make.',
                        'part2Key' => 'CRAFTS',
                        'part2Body' => 'The part that is not automatic. Brush, vector, type and judgement, applied by people who care how it lands.',
                        'part3Key' => 'LAB',
                        'part3Body' => 'Where it gets tested. Prototypes, experiments and in-progress builds before anything reaches a store.',
                    ],
                ],
                [
                    'key' => 'story', 'label' => 'Our story',
                    'heading' => 'Our Story',
                    'body' => 'PixelCraftsLab Studio started with one goal — build digital experiences that actually stand out. We combine creativity, clean code, and strong UI design to craft modern websites, digital tools, and fun games.',
                ],
                [
                    'key' => 'mission', 'label' => 'Our mission',
                    'heading' => 'Our Mission',
                    'body' => 'Create fast, modern, and visually powerful utility app, and online games that help brands grow and stand out online.',
                ],
                [
                    'key' => 'vision', 'label' => 'Future vision',
                    'heading' => 'Future Vision',
                    'body' => 'We aim to grow PixelCraftsLab into a full creative tech studio specializing in web platforms, design systems, digital marketing and growth.',
                ],
                [
                    'key' => 'team', 'label' => 'Team',
                    'heading' => '{count} people, one studio',
                    'body' => 'Small enough that you talk to the people building your product.',
                    'settings' => ['photoPlaceholder' => '[ Photo to supply ]'],
                ],
                [
                    'key' => 'proof', 'label' => 'Proof',
                    'eyebrow' => 'Proof, honestly',
                    'heading' => 'No borrowed logos. Just shipped products you can open right now.',
                    'body' => 'Every project in the index is live on Google Play or the open web. Click any of them and judge the work directly — that is the only reference we’d rather give you.',
                ],
                [
                    'key' => 'reviews', 'label' => 'Client reviews',
                    'eyebrow' => 'Client reviews',
                    'body' => '“Be the first to leave a review : )”',
                    'settings' => [
                        'attribution' => '— Mehak Arman, PixelCraftsLab Studio’s Admin',
                        'note' => '[ PLACEHOLDER ] Real client quotes, Play Store ratings or case-study metrics drop in here once you send them over.',
                    ],
                ],
            ],
        ];
    }

    private function lab(): array
    {
        return [
            'key' => 'lab',
            'title' => 'Lab',
            'seo_title' => 'Lab — PixelCraftsLab Studio',
            'seo_description' => 'Where things get tested before they get shipped.',
            'sections' => [
                [
                    'key' => 'header', 'label' => 'Header',
                    'heading' => 'Make a',
                    'body' => 'Lab is where things get tested before they get shipped. Drag the pieces, paint the grid, break the layout. Nothing here is precious.',
                    'settings' => ['emphasis' => 'mess', 'toolLabel' => 'Tool', 'clearLabel' => 'Clear'],
                ],
                [
                    'key' => 'projects', 'label' => 'In the lab',
                    'eyebrow' => 'In the lab right now',
                    'settings' => ['statusLabel' => 'Not yet shipped', 'badge' => 'In build'],
                ],
                [
                    'key' => 'cta', 'label' => 'Closing CTA',
                    'heading' => 'Got something that belongs in here?',
                    'cta_label' => 'Bring it to us', 'cta_url' => '#contact',
                ],
            ],
        ];
    }

    private function contact(): array
    {
        return [
            'key' => 'contact',
            'title' => 'Contact',
            'seo_title' => 'Start a project — PixelCraftsLab Studio',
            'seo_description' => 'Tell us what you are building, or what you need to grow.',
            'sections' => [
                [
                    'key' => 'header', 'label' => 'Header',
                    'eyebrow' => 'Start a project',
                    'heading' => 'Build your brief',
                    'body' => 'Answer four things. Watch your project take shape on the right as you go.',
                ],
                [
                    'key' => 'steps', 'label' => 'Form steps',
                    'settings' => [
                        'qBuild' => 'What are you building?',
                        'qScope' => 'How much of it?',
                        'qTime' => 'When do you need it?',
                        'qRest' => 'Tell us the rest',
                        'nameLabel' => 'Your name',
                        'namePlaceholder' => 'Jane Cooper',
                        'emailLabel' => 'Your email',
                        'emailPlaceholder' => 'you@company.com',
                        'projectLabel' => 'The project',
                        'projectPlaceholder' => 'What are you building, and what does success look like?',
                        'stepLabel' => 'Step',
                        'backLabel' => 'Back',
                        'submitLabel' => 'Launch project',
                        'backStepLabel' => '← Back a step',
                        'sendingLabel' => 'Sending…',
                    ],
                ],
                [
                    'key' => 'marketing_fields', 'label' => 'Marketing questions',
                    'settings' => [
                        'qMarketing' => 'A bit about the brand',
                        'businessLabel' => 'Business / brand',
                        'businessPlaceholder' => 'What is it called?',
                        'websiteLabel' => 'Website',
                        'websitePlaceholder' => 'yourbrand.com',
                        'goalLabel' => 'Primary goal',
                        'goalPlaceholder' => 'More leads, more downloads, more reach…',
                        'audienceLabel' => 'Target audience',
                        'audiencePlaceholder' => 'Who are you trying to reach?',
                        'channelsLabel' => 'Preferred channels',
                        'channelsPlaceholder' => 'Instagram, TikTok, search…',
                        'currentLabel' => 'Current marketing',
                        'currentPlaceholder' => 'What are you doing now, and what is working?',
                        'skipLabel' => 'Skip this step',
                    ],
                ],
                [
                    'key' => 'success', 'label' => 'Success state',
                    'heading' => 'Brief assembled.',
                    'body' => 'Thanks — your brief is with us. We read every enquiry ourselves and will come back to you shortly.',
                    'cta_label' => 'Build another',
                    'settings' => ['errorMessage' => 'Something went wrong sending that. Please try again, or email us directly.'],
                ],
                [
                    'key' => 'details', 'label' => 'Contact details',
                    'settings' => [
                        'emailLabel' => 'Email',
                        'phoneLabel' => 'Phone',
                        'locationLabel' => 'Location',
                        'emailPlaceholder' => '[ PLACEHOLDER — studio email ]',
                    ],
                ],
                [
                    'key' => 'preview', 'label' => 'Brief preview',
                    'heading' => 'Let’s build yours',
                    'settings' => [
                        'briefLabel' => 'Your brief',
                        'buildingLabel' => 'Building',
                        'scopeLabel' => 'Scope',
                        'timingLabel' => 'Timing',
                    ],
                ],
            ],
        ];
    }
}
