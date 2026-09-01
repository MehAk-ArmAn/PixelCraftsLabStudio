<?php

namespace Database\Seeders;

use App\Models\ContactOption;
use Illuminate\Database\Seeder;

class ContactOptionSeeder extends Seeder
{
    public function run(): void
    {
        // Step 1 — the original four build options plus marketing entries.
        // "growth" group entries flag the enquiry as a marketing enquiry.
        $build = [
            ['Website', 'build'],
            ['App', 'build'],
            ['Brand', 'build'],
            ['Game / Interactive', 'build'],
            ['Marketing & Growth', 'growth'],
            ['Something else', 'build'],
        ];

        foreach ($build as $index => [$label, $group]) {
            $this->option('build', $label, $group, ($index + 1) * 10);
        }

        $scope = [
            'A first version / MVP',
            'A full product build',
            'A redesign of something existing',
            'Ongoing support',
            'Not sure yet',
        ];

        foreach ($scope as $index => $label) {
            $this->option('scope', $label, 'build', ($index + 1) * 10);
        }

        $timeline = [
            'As soon as possible',
            '1–3 months',
            '3–6 months',
            'Just exploring',
        ];

        foreach ($timeline as $index => $label) {
            $this->option('timeline', $label, 'build', ($index + 1) * 10);
        }

        // Marketing service options shown when the visitor picks Marketing & Growth.
        $services = [
            'Digital Marketing',
            'Social Media Marketing',
            'Social Media Management',
            'Growth Strategy',
            'SEO',
            'Content Marketing',
            'Paid Advertising',
            'Campaign Strategy',
            'Launch Marketing',
            'Marketing Audit',
            'Email Marketing',
            'Marketing Automation',
        ];

        foreach ($services as $index => $label) {
            $this->option('service', $label, 'growth', ($index + 1) * 10);
        }

        // Budget ranges are disabled by default — the public form does not show
        // a budget step today, and enabling one is an admin decision.
        $budgets = [
            'Under £2,000',
            '£2,000 – £5,000',
            '£5,000 – £10,000',
            '£10,000 – £25,000',
            '£25,000+',
            'Not sure yet',
        ];

        foreach ($budgets as $index => $label) {
            $this->option('budget', $label, 'build', ($index + 1) * 10, false);
        }
    }

    private function option(string $type, string $label, string $group, int $sort, bool $enabled = true): void
    {
        ContactOption::firstOrCreate(
            ['type' => $type, 'value' => $label],
            [
                'label' => $label,
                'group' => $group,
                'sort_order' => $sort,
                'is_enabled' => $enabled,
            ],
        );
    }
}
