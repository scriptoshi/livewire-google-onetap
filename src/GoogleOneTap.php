<?php

namespace Scriptoshi\LivewireGoogleOneTap;

use Google\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Laravel\Socialite\Two\User as Social;
use Livewire\Component;

class GoogleOneTap extends Component
{
    /**
     * The type of button to render (login or register)
     */
    public string $type = 'login';
    public string $width = '364px';

    /**
     * Initialize the component
     */
    public function mount(string $type = 'login', string $width = '364px'): void
    {
        $this->type = $type;
        $this->width = $width;
    }

    /**
     * Get the Google client ID from the config
     */
    public function getGoogleClientIdProperty(): string
    {
        return Config::get('google-onetap.client_id') ?: Config::get('services.google.client_id');
    }

    /**
     * Handle Google One Tap sign-in directly in the component
     */
    public function oneTapSignIn(string $credential)
    {
        try {
            // Verify and get user info from Google
            $social = $this->getOneTapUser($credential);

            // Get user model from config
            $userModel = Config::get('google-onetap.user_model') ?: \App\Models\User::class;

            // Find existing user or create a new one
            $user = $userModel::where('email', $social->getEmail())->first();

            if (!$user) {
                // Create a new user
                $user = $userModel::create([
                    'name' => $social->getName(),
                    'email' => $social->getEmail()
                ]);
                $user->email_verified_at = now();
                $user->googleId = $social->getId();
                $user->google_avatar_url = $social->getAvatar();
                $user->save();
            } else {
                // Update existing user's Google ID if not set
                if (!$user->googleId) {
                    $user->googleId = $social->getId();
                    $user->google_avatar_url = $social->getAvatar();
                    $user->email_verified_at = now();
                    $user->save();
                }
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            }

            // Log the user in
            Auth::login($user);

            // Get the redirect path from config
            $redirectRoute = Config::get('google-onetap.redirect') ?: 'dashboard';
            // Redirect to dashboard
            // If the redirect URL is a route name, resolve it
            $this->redirectIntended(default: route($redirectRoute, absolute: false), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Google authentication failed: ' . $e->getMessage());
        }
    }

    /**
     * Get user information from Google One Tap token
     */
    protected function getOneTapUser(string $token): Social
    {
        $client = new Client([
            'client_id' => Config::get('google-onetap.client_id') ?: Config::get('services.google.client_id'),
            'client_secret' => Config::get('google-onetap.client_secret') ?: Config::get('services.google.client_secret'),
        ]);

        $client->setScopes('email');
        $info = $client->verifyIdToken($token);

        if (!$info) {
            throw new \Exception('Invalid token');
        }

        return (new Social)
            ->setRaw($info)
            ->map([
                'id' => Arr::get($info, 'sub'),
                'nickname' => Str::of(Arr::get($info, 'name') . '-' . Str::random(8))->slug(),
                'name' => Arr::get($info, 'name'),
                'email' => Arr::get($info, 'email'),
                'avatar' => Arr::get($info, 'picture'),
                'avatar_original' => Arr::get($info, 'picture'),
                'given_name' => Arr::get($info, 'given_name'),
                'family_name' => Arr::get($info, 'family_name'),
            ])
            ->setToken($token);
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('google-onetap::google-onetap');
    }
}
