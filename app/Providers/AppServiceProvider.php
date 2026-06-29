<?php

namespace App\Providers;

use App\Repositories\ICategoryRepository;
use App\Repositories\IDrugRepository;
use App\Repositories\IInvoiceItemRepository;
use App\Repositories\IInvoiceRepository;
use App\Repositories\Implementation\CategoryRepository;
use App\Repositories\Implementation\DrugRepository;
use App\Repositories\Implementation\InvoiceItemRepository;
use App\Repositories\Implementation\InvoiceRepository;
use App\Repositories\Implementation\NotificationRepository;
use App\Repositories\Implementation\OrderRepository;
use App\Repositories\Implementation\PharmaRepository;
use App\Repositories\Implementation\UserRepository;
use App\Repositories\Implementation\WarehouseRepository;
use App\Repositories\INotificationRepository;
use App\Repositories\IOrderRepository;
use App\Repositories\IPharmaRepository;
use App\Repositories\IUserRepository;
use App\Repositories\IWarehouseRepository;
use App\Services\Admin\ICategoryService;
use App\Services\Admin\IDrugService;
use App\Services\Admin\IInvoiceService;
use App\Services\Admin\Implementation\CategoryService;
use App\Services\Admin\Implementation\DrugService;
use App\Services\Admin\Implementation\InvoiceService;
use App\Services\Admin\Implementation\NotificationService;
use App\Services\Admin\Implementation\OrderService;
use App\Services\Admin\Implementation\PharmaService;
use App\Services\Admin\Implementation\UserService;
use App\Services\Admin\Implementation\WarehouseService;
use App\Services\Admin\INotificationService;
use App\Services\Admin\IOrderService;
use App\Services\Admin\IPharmaService;
use App\Services\Admin\IUserService;
use App\Services\Admin\IWarehouseService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ICategoryRepository::class,
            CategoryRepository::class
        );

        $this->app->bind(
            ICategoryService::class,
            CategoryService::class
        );


        $this->app->bind(
            IInvoiceRepository::class,
            InvoiceRepository::class
        );
        $this->app->bind(
            IInvoiceService::class,
            InvoiceService::class
        );

        $this->app->bind(IInvoiceRepository::class, InvoiceRepository::class);
        $this->app->bind(IInvoiceItemRepository::class, InvoiceItemRepository::class);
        $this->app->bind(IWarehouseRepository::class, WarehouseRepository::class);
        $this->app->bind(INotificationRepository::class, NotificationRepository::class);

        $this->app->bind(
            \App\Services\Supervisor\IInvoiceService::class,
            \App\Services\Supervisor\Implementation\InvoiceService::class
        );


        $this->app->bind(
            IDrugRepository::class,
            DrugRepository::class
        );
        $this->app->bind(
            IDrugService::class,
            DrugService::class
        );

        $this->app->bind(
            INotificationRepository::class,
            NotificationRepository::class
        );
        $this->app->bind(
            INotificationService::class,
            NotificationService::class
        );

        $this->app->bind(
            IOrderRepository::class,
            OrderRepository::class
        );
        $this->app->bind(
            IOrderService::class,
            OrderService::class
        );

        $this->app->bind(
            IPharmaRepository::class,
            PharmaRepository::class
        );
        $this->app->bind(
            IPharmaService::class,
            PharmaService::class
        );

        $this->app->bind(
            IUserRepository::class,
            UserRepository::class
        );
        $this->app->bind(
            IUserService::class,
            UserService::class
        );

        $this->app->bind(
            IWarehouseRepository::class,
            WarehouseRepository::class
        );
        $this->app->bind(
            IWarehouseService::class,
            WarehouseService::class
        );
        $this->app->bind(
            \App\Services\Supervisor\IWarehouseService::class,
            \App\Services\Supervisor\Implementation\WarehouseService::class
        );




    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
