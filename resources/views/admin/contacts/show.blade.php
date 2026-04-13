@extends('layouts.admin')

@section('title', 'Contact Details')

@section('content')

<div class="admin-card">

    <div style="display:flex; justify-content:space-between; align-items:center; gap:14px; flex-wrap:wrap; margin-bottom:20px;">
        <div>
            <h2 style="margin:0; color:#fff;">Contact Details</h2>
            <p style="margin:6px 0 0; color:#94a3b8;">
                Full inquiry details from your website form.
            </p>
        </div>

        <div class="btn-group">
            <a href="{{ route('admin.contacts.index') }}" class="btn primary">← Back</a>

            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}">
                @csrf
                @method('DELETE')
                <button class="btn danger" type="submit" onclick="return confirm('Delete this contact?')">
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:16px; margin-bottom:20px;">

        <div class="admin-card" style="margin-bottom:0; text-align:left;">
            <p style="margin:0 0 6px; color:#a78bfa; font-weight:700;">Name</p>
            <h3 style="margin:0; color:#fff;">{{ $contact->name }}</h3>
        </div>

        <div class="admin-card" style="margin-bottom:0; text-align:left;">
            <p style="margin:0 0 6px; color:#a78bfa; font-weight:700;">Email</p>
            <h3 style="margin:0; color:#fff; font-size:1rem; word-break:break-word;">
                {{ $contact->email }}
            </h3>
        </div>

        <div class="admin-card" style="margin-bottom:0; text-align:left;">
            <p style="margin:0 0 6px; color:#a78bfa; font-weight:700;">Service</p>
            <h3 style="margin:0; color:#fff;">
                {{ $contact->service ?: 'Not selected' }}
            </h3>
        </div>

        <div class="admin-card" style="margin-bottom:0; text-align:left;">
            <p style="margin:0 0 6px; color:#a78bfa; font-weight:700;">Status</p>

            @if($contact->is_read)
                <span style="
                    display:inline-block;
                    padding:8px 14px;
                    border-radius:999px;
                    background:rgba(34,197,94,0.15);
                    color:#86efac;
                    border:1px solid rgba(34,197,94,0.25);
                    font-weight:700;
                ">
                    Read
                </span>
            @else
                <span style="
                    display:inline-block;
                    padding:8px 14px;
                    border-radius:999px;
                    background:rgba(250,204,21,0.12);
                    color:#fde68a;
                    border:1px solid rgba(250,204,21,0.25);
                    font-weight:700;
                ">
                    Unread
                </span>
            @endif
        </div>

    </div>

    <div class="admin-card" style="margin-bottom:20px; text-align:left;">
        <p style="margin:0 0 10px; color:#a78bfa; font-weight:700;">Submitted At</p>
        <p style="margin:0; color:#e2e8f0;">
            {{ $contact->created_at ? $contact->created_at->format('d M Y, h:i A') : 'N/A' }}
        </p>
    </div>

    <div class="admin-card" style="margin-bottom:0; text-align:left;">
        <p style="margin:0 0 12px; color:#a78bfa; font-weight:700;">Message</p>

        <div style="
            padding:18px;
            border-radius:14px;
            background:rgba(255,255,255,0.03);
            border:1px solid rgba(255,255,255,0.06);
            color:#e2e8f0;
            line-height:1.8;
            white-space:pre-wrap;
            word-break:break-word;
        ">{{ $contact->message }}</div>
    </div>

</div>

@endsection
