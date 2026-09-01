<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreateAdminUser extends Command
{
    protected $signature = 'pcl:admin
        {--email= : Skip the prompt and use this email}
        {--name= : Skip the prompt and use this name}
        {--role= : super_admin, admin or editor}
        {--password= : Skip the prompt (use only in trusted, non-interactive setup)}';

    protected $description = 'Create or update a PixelCraftsLab admin account';

    public function handle(): int
    {
        $name = $this->option('name') ?: text(
            label: 'Name',
            required: true,
            validate: fn ($v) => strlen($v) > 190 ? 'Too long.' : null,
        );

        $email = $this->option('email') ?: text(
            label: 'Email',
            required: true,
            validate: fn ($v) => Validator::make(['email' => $v], ['email' => 'email'])->fails()
                ? 'Enter a valid email address.'
                : null,
        );

        $existing = User::where('email', $email)->first();

        if ($existing) {
            $this->warn('An account already exists for '.$email.'.');

            if (! confirm('Update that account instead?', default: true)) {
                return self::FAILURE;
            }
        }

        $plain = $this->option('password') ?: password(
            label: $existing ? 'New password (leave blank to keep the current one)' : 'Password',
            required: ! $existing,
            validate: function ($v) use ($existing) {
                if ($existing && $v === '') {
                    return null;
                }

                return strlen($v) < 10 ? 'Use at least 10 characters.' : null;
            },
        );

        $role = $this->option('role') ?: select(
            label: 'Role',
            options: [
                User::ROLE_SUPER_ADMIN => 'Super admin — everything, including admin users',
                User::ROLE_ADMIN => 'Admin — all content, enquiries and media',
                User::ROLE_EDITOR => 'Editor — content and media only',
            ],
            default: User::ROLE_SUPER_ADMIN,
        );

        if (! in_array($role, User::ROLES, true)) {
            $this->error('Unknown role "'.$role.'".');

            return self::FAILURE;
        }

        $user = $existing ?: new User(['email' => $email]);
        $user->name = $name;
        $user->role = $role;
        $user->is_active = true;

        if ($plain !== '' && $plain !== null) {
            $user->password = Hash::make($plain);
        }

        $user->save();

        $this->newLine();
        $this->info(($existing ? 'Updated' : 'Created').' admin account:');
        $this->line('  Name  '.$user->name);
        $this->line('  Email '.$user->email);
        $this->line('  Role  '.$user->roleLabel());
        $this->newLine();
        $this->line('Sign in at '.rtrim((string) config('app.url'), '/').'/admin/login');

        return self::SUCCESS;
    }
}
