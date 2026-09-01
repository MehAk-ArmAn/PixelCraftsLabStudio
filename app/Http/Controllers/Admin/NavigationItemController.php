<?php

namespace App\Http\Controllers\Admin;

use App\Models\NavigationItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class NavigationItemController extends AdminResourceController
{
    protected function model(): string
    {
        return NavigationItem::class;
    }

    protected function routeBase(): string
    {
        return 'navigation';
    }

    protected function title(): string
    {
        return 'Navigation';
    }

    protected function singular(): string
    {
        return 'Navigation item';
    }

    protected function intro(): ?string
    {
        return 'One list drives the desktop header, the mobile menu and the footer. '
            .'The route key must match a page the frontend knows: work, services, studio, lab, growth, contact.';
    }

    protected function searchable(): array
    {
        return ['label', 'route_key'];
    }

    protected function listColumns(): array
    {
        return [
            ['key' => 'number', 'label' => 'No'],
            ['key' => 'label', 'label' => 'Label'],
            ['key' => 'route_key', 'label' => 'Route key'],
            ['key' => 'is_visible', 'label' => 'Visible', 'type' => 'bool'],
            ['key' => 'show_desktop', 'label' => 'Desktop', 'type' => 'bool'],
            ['key' => 'show_mobile', 'label' => 'Mobile', 'type' => 'bool'],
            ['key' => 'show_footer', 'label' => 'Footer', 'type' => 'bool'],
        ];
    }

    protected function schema(): array
    {
        return [
            ['name' => 'label', 'label' => 'Label', 'type' => 'text', 'required' => true],
            ['name' => 'route_key', 'label' => 'Route key', 'type' => 'text', 'required' => true, 'datalist' => ['work', 'services', 'studio', 'lab', 'growth', 'contact', 'home']],
            ['name' => 'destination', 'label' => 'Destination', 'type' => 'text', 'help' => 'Defaults to #route-key. Use a full URL for external links.'],
            ['name' => 'number', 'label' => 'Number', 'type' => 'text', 'help' => 'e.g. 01'],
            ['name' => 'is_visible', 'label' => 'Visible', 'type' => 'checkbox'],
            ['name' => 'show_desktop', 'label' => 'Show in desktop header', 'type' => 'checkbox'],
            ['name' => 'show_mobile', 'label' => 'Show in mobile menu', 'type' => 'checkbox'],
            ['name' => 'show_footer', 'label' => 'Show in footer', 'type' => 'checkbox'],
            ['name' => 'is_external', 'label' => 'External link', 'type' => 'checkbox'],
            ['name' => 'open_new_tab', 'label' => 'Open in new tab', 'type' => 'checkbox'],
            ['name' => 'sort_order', 'label' => 'Sort order', 'type' => 'number'],
        ];
    }

    protected function rules(?Model $record = null): array
    {
        return [
            'label' => ['required', 'string', 'max:64'],
            'route_key' => ['required', 'string', 'max:64', 'alpha_dash', Rule::unique('navigation_items', 'route_key')->ignore($record?->getKey())],
            'destination' => ['nullable', 'string', 'max:255'],
            'number' => ['nullable', 'string', 'max:8'],
            'is_visible' => ['boolean'],
            'show_desktop' => ['boolean'],
            'show_mobile' => ['boolean'],
            'show_footer' => ['boolean'],
            'is_external' => ['boolean'],
            'open_new_tab' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:100000'],
        ];
    }

    protected function transform(array $data, Model $record): array
    {
        $data['route_key'] = Str::slug($data['route_key']);

        return $data;
    }

    protected function recordLabel(Model $record): string
    {
        return $record->label;
    }
}
