<?php

namespace App\Providers;

use App\Repositories\BankAccountRepository;
use App\Repositories\Contracts\BankAccountRepositoryInterface;
use Carbon\Laravel\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\TransactionRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            BankAccountRepositoryInterface::class,
            BankAccountRepository::class
        );

        $this->app->bind(
            TransactionRepositoryInterface::class,
            TransactionRepository::class
        );
    }
}
