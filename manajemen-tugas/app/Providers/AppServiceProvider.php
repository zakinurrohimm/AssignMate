<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Kustomisasi tampilan email verifikasi ala Sci-Fi Terminal
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('[ASSIGNMATE] // SYSTEM_SECURITY_VERIFICATION')
                ->greeting('Halo, Operator!')
                ->line('Sistem mendeteksi adanya registrasi baru menggunakan alamat email ini.')
                ->line('Silakan klik tombol di bawah untuk memverifikasi identitas Anda dan membuka akses terminal:')
                ->action('[EXECUTE] Verifikasi Email', $url)
                ->line('Jika Anda tidak merasa melakukan registrasi ini, abaikan pesan sistem ini.')
                ->salutation('Regards, ASSIGNMATE Core System');
        });
    }
}