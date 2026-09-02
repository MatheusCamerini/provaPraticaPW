<?php

namespace Database\Seeders;

use App\Models\Filme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class FilmeSeeder extends Seeder
{
    /**
     * Pasta onde você deve colocar imagens de exemplo (.jpg/.png) antes de rodar o seeder.
     * Ex: database/seeders/samples/capas/duna.jpg
     */
    protected string $samplesPath;

    public function __construct()
    {
        $this->samplesPath = database_path('seeders/samples/capas');
    }

    public function run(): void
    {
        // Garante que existam usuários para associar aos filmes
        $users = User::all();
        if ($users->isEmpty()) {
            $users = User::factory()->count(3)->create();
        }

        $filmes = [
            [
                'nome' => 'Duna',
                'categoria' => 'Ficção Científica',
                'ano' => 2021,
                'sinopse' => 'Um jovem herdeiro é levado a um planeta hostil para proteger o recurso mais valioso do universo.',
                'trailer' => 'https://www.youtube.com/watch?v=n9xhJrPXop4',
                'imagem' => 'duna.jpg',
            ],
            [
                'nome' => 'Interestelar',
                'categoria' => 'Ficção Científica',
                'ano' => 2014,
                'sinopse' => 'Um grupo de exploradores viaja por um buraco de minhoca em busca de um novo lar para a humanidade.',
                'trailer' => 'https://www.youtube.com/watch?v=zSWdZVtXT7E',
                'imagem' => 'interestelar.jpg',
            ],
            [
                'nome' => 'Parasita',
                'categoria' => 'Drama',
                'ano' => 2019,
                'sinopse' => 'A relação entre duas famílias de classes sociais opostas termina em tragédia.',
                'trailer' => 'https://www.youtube.com/watch?v=5xH0HfJHsaY',
                'imagem' => 'parasita.jpg',
            ],
            [
                'nome' => 'Whiplash',
                'categoria' => 'Drama',
                'ano' => 2014,
                'sinopse' => 'Um jovem baterista de jazz é pressionado ao limite por um professor implacável.',
                'trailer' => 'https://www.youtube.com/watch?v=7d_jQycdQGo',
                'imagem' => 'whiplash.jpg',
            ],
            [
                'nome' => 'Coringa',
                'categoria' => 'Drama',
                'ano' => 2019,
                'sinopse' => 'Um comediante fracassado mergulha na loucura e se transforma em um ícone do crime.',
                'trailer' => 'https://www.youtube.com/watch?v=zAGVQLHvwOY',
                'imagem' => 'coringa.jpg',
            ],
        ];

        foreach ($filmes as $dado) {
            // Tenta usar a imagem local; se não existir, baixa um placeholder
            $capaPath = $this->armazenarImagemLocal($dado['imagem'])
                ?? $this->baixarImagemPlaceholder();

            Filme::create([
                'user_id' => $users->random()->id,
                'nome' => $dado['nome'],
                'sinopse' => $dado['sinopse'],
                'ano' => $dado['ano'],
                'categoria' => $dado['categoria'],
                'trailer' => $dado['trailer'],
                'capa' => $capaPath,
            ]);
        }
    }

    /**
     * OPÇÃO 1 (recomendada): copia uma imagem de
     * database/seeders/samples/capas/{nomeArquivo} para storage/app/public/capas.
     *
     * Storage::putFile() gera um nome único e devolve o caminho relativo
     * (ex.: "capas/aBcD1234.jpg") — exatamente o formato salvo pelo Controller
     * quando o usuário faz upload de verdade.
     */
    protected function armazenarImagemLocal(?string $nomeArquivo): ?string
    {
        if (!$nomeArquivo) {
            return null;
        }

        $origem = $this->samplesPath . DIRECTORY_SEPARATOR . $nomeArquivo;

        if (!file_exists($origem)) {
            $this->command?->warn("Imagem de exemplo não encontrada, pulando: {$origem}");
            return null;
        }

        return Storage::disk('public')->putFile('capas', new File($origem));
    }

    /**
     * OPÇÃO 2 (fallback): baixa uma imagem de placeholder da internet
     * e salva no disco público — útil se você não tiver imagens de exemplo à mão.
     */
    protected function baixarImagemPlaceholder(): ?string
    {
        try {
            $response = Http::timeout(10)->get('https://picsum.photos/500/750');

            if (!$response->successful()) {
                return null;
            }

            $nomeArquivo = 'capas/' . uniqid('filme_') . '.jpg';
            Storage::disk('public')->put($nomeArquivo, $response->body());

            return $nomeArquivo;
        } catch (\Throwable $e) {
            $this->command?->warn('Não foi possível baixar a imagem placeholder: ' . $e->getMessage());
            return null;
        }
    }
}
