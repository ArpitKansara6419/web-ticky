<?php

namespace App\Providers;

use App\Repositories\Interface\BankRepositoryInterface;
use App\Repositories\Interface\CustomerAuthorisedPersonRepositoryInterface;
use App\Repositories\Interface\EngineerNotificationRepositoryInterface;
use App\Repositories\Interface\ReviewRepositoryInterface;
use App\Repositories\Interface\RoleRepositoryInterface;
use App\Repositories\Interface\TaskReminderRepositoryInterface;
use App\Repositories\Interface\UserRepositoryInterface;
use App\Repositories\Repository\BankRepository;
use App\Repositories\Repository\CustomerAuthorisedPersonRepository;
use App\Repositories\Repository\EngineerNotificationRepository;
use App\Repositories\Repository\ReviewRepository;
use App\Repositories\Repository\RoleRepository;
use App\Repositories\Repository\TaskReminderRepository;
use App\Repositories\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(
            EngineerNotificationRepositoryInterface::class,
            EngineerNotificationRepository::class
        );
        $this->app->bind(
            TaskReminderRepositoryInterface::class,
            TaskReminderRepository::class
        );
        $this->app->bind(
            RoleRepositoryInterface::class,
            RoleRepository::class
        );
        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );
        $this->app->bind(
            BankRepositoryInterface::class,
            BankRepository::class
        );
        $this->app->bind(
            CustomerAuthorisedPersonRepositoryInterface::class,
            CustomerAuthorisedPersonRepository::class
        );
        $this->app->bind(
            ReviewRepositoryInterface::class,
            ReviewRepository::class
        );
    }
}
