# Laravel Livewire Google One Tap

A Laravel Livewire component for Google One Tap authentication. Easily add "Sign in with Google" functionality to your Laravel application with a simple Livewire component.

## Installation

You can install the package via composer:

```bash
composer require scriptoshi/livewire-google-onetap
```

## Requirements

This package requires:

-   PHP 8.2+
-   Laravel 12.x
-   Livewire 3.x
-   Alpine.js

## Configuration

Publish the configuration file with:

```bash
php artisan vendor:publish --tag=google-onetap-config
```

You'll need to set up your Google OAuth credentials in your `.env` file:

```
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
```

Or, if you're already using Laravel Socialite, it will use those credentials by default from your `services.google` config.

## Database Migration

This package adds Google authentication fields to your `users` table. To run the migrations:

```bash
php artisan migrate
```

Or you can publish the migrations and customize them:

```bash
php artisan vendor:publish --tag=google-onetap-migrations
```

## Usage

To use the component, make sure you have Livewire installed and properly set up in your Laravel application. Livewire includes Alpine by default.

Then, in your login or registration page, add the component:

```blade
<livewire:google-onetap />
```

For registration pages, specify the type:

```blade
<livewire:google-onetap type="register" />
```

This component will automatically load the Google API script, so you don't need to include it separately in your layout.

## User Avatar

When users sign in with Google, their Google profile picture URL is stored in the `google_avatar_url` field. You can use this to display their profile picture in your application:

```blade
@if(auth()->user()->google_avatar_url)
    <img src="{{ auth()->user()->google_avatar_url }}" alt="{{ auth()->user()->name }}" class="rounded-full h-10 w-10">
@else
    <!-- Fallback avatar -->
@endif
```

This can be particularly useful for creating a consistent user experience when users sign in with Google.

## Configure Tailwind CSS

To ensure that Tailwind CSS properly processes the component styles, add this package to your content sources in your CSS file (typically `resources/css/app.css`):

```css
/* Add this line with your other @source directives */
@source '../../vendor/scriptoshi/livewire-google-onetap/resources/views/**/*.blade.php';
```

For example, your CSS file might look similar to this:

```css
@import "tailwindcss";
@source "../views";
@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../vendor/scriptoshi/livewire-google-onetap/resources/views/**/*.blade.php';
/* Rest of your CSS file */
```

## Customization

You can publish the view and modify it, but keep in mind, Google restricts how much you can customize:

```bash
php artisan vendor:publish --tag=google-onetap-views
```

## Styling

The component uses minimal styling and adapts to both light and dark modes. It will automatically detect if your site is using dark mode by checking for the `dark` class on the HTML element.

You can customize the width, height, and other aspects by editing the published view.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
