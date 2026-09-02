<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['name' => 'Ana Souza', 'email' => 'ana@exemplo.com'],
            ['name' => 'Bruno Lima', 'email' => 'bruno@exemplo.com'],
            ['name' => 'Carla Nunes', 'email' => 'carla@exemplo.com'],
        ];

        foreach ($usuarios as $dado) {
            User::firstOrCreate(
                ['email' => $dado['email']],
                [
                    'name' => $dado['name'],
                    'password' => Hash::make('password'), // senha padrão de teste
                    'profile_picture' => $this->baixarAvatar(),
                ]
            );
        }
    }

    /**
     * Baixa um avatar aleatório e salva em storage/app/public/profile_pictures.
     * Mesmo princípio do FilmeSeeder: Storage::put() grava os bytes recebidos
     * e devolve/usa o caminho relativo salvo no banco.
     */
    protected function baixarAvatar(): ?string
    {
        try {
            $response = Http::timeout(10)->get('https://i.pravatar.cc/300?u=' . uniqid());

            if (!$response->successful()) {
                return null;
            }

            $nomeArquivo = 'profile_pictures/' . uniqid('avatar_') . '.jpg';
            Storage::disk('public')->put($nomeArquivo, $response->body());

            return $nomeArquivo;
        } catch (\Throwable $e) {
            $this->command?->warn('Não foi possível baixar o avatar: ' . $e->getMessage());
            return null;
        }
    }
}
