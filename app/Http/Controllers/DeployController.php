<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\RateLimiter;

class DeployController extends Controller
{
    public function deploy(Request $request)
    {
        $key = 'deployer:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            abort(429, 'Too many attempts. Try again later.');
        }

        $token = (string) env('DEPLOYER_PASSWORD');
        $given = (string) $request->query('password', '');

        if ($token === '' || ! hash_equals($token, $given)) {
            RateLimiter::hit($key, 60);
            Log::warning('Deployer: invalid password attempt', ['ip' => $request->ip()]);
            abort(403, 'Forbidden');
        }

        RateLimiter::clear($key);
        Log::info('Deployer: deployment started', ['ip' => $request->ip()]);

        $steps = [
            'git fetch origin main',
            'git reset --hard origin/main',
            ['php', 'artisan', 'config:clear'],
            ['php', 'artisan', 'view:clear'],
            ['php', 'artisan', 'route:clear'],
        ];

        // Some hosts (e.g. Hostinger) serve from a sibling public_html with its
        // own patched index.php, instead of this app's public/ directly.
        $publicHtmlPath = env('DEPLOY_PUBLIC_HTML_PATH');

        if ($publicHtmlPath) {
            $steps[] = [
                'rsync', '-a', '--delete',
                '--exclude=index.php',
                'public/', rtrim($publicHtmlPath, '/') . '/',
            ];
        }

        $log = [];

        foreach ($steps as $command) {
            $result = Process::path(base_path())->timeout(120)->run($command);

            $label = is_array($command) ? implode(' ', $command) : $command;
            $log[] = "\$ {$label}\n" . trim($result->output() . $result->errorOutput());

            if (! $result->successful()) {
                Log::error('Deployer: step failed', ['command' => $label]);

                return response(implode("\n\n", $log) . "\n\nDeployment FAILED.", 500)
                    ->header('Content-Type', 'text/plain');
            }
        }

        Log::info('Deployer: deployment finished successfully', ['ip' => $request->ip()]);

        return response(implode("\n\n", $log) . "\n\nDeployment finished successfully.")
            ->header('Content-Type', 'text/plain');
    }
}
