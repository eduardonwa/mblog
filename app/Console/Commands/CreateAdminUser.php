<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin
                            {email? : Admin email}
                            {--name= : Admin name (default: "Admin")}
                            {--password= : Optional password (random if not provided)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new admin user.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //crear rol de admin si no existe
        if (!Role::where('name', 'admin')->exists()) {
            Role::create(['name' => 'admin']);
            $this->info('Admin role created automatically.');
        } 

        // obtener o solicitar nombre
        $name = $this->option('name') ?? $this->ask('Username for the admin', 'Admin');

        // obtener o solicitar email 
        $email = $this->argument('email') ?? $this->ask('Enter a valid email address');
        // validar email
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|email|unique:users,email'
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return 1;
        }

        // obtener o solicitad contraseña
        $password = $this->option('password') ?? $this->secret('Password: (leave empty to generate a random password)');
        // generar la contraseña aleatoriamente
        $password = $password ?: Str::random(12);

        $this->table(
            ['Field', 'Value'],
            [
                ['Name', $name],
                ['Email', $email],
                ['Password', $password ? '*****' : 'Randomly generated']
            ]
        );

        if (!$this->confirm('Create this admin user?')) {
            $this->info('Cancelled.');
            return 0;
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'email_verified_at' => now()
        ]);

        $user->assignRole('admin');

        $this->info("✅ Admin user succesfully created.");
        $this->info("🤘 Email already verified, no need to check your email.");
        $this->line("Name: {$name}");
        $this->line("Email: {$email}");
        $this->line("Password: {$password}");

        return 0;
    }
}
