### Configuration
1. Change config.starmoozie.base.user_model_fqn to
```
    'user_model_fqn' => \Starmoozie\LaravelMenuPermission\app\Models\User::class
```
2. Please read [Laravel Model Caching](https://github.com/GeneaLabs/laravel-model-caching) to setup caching configuration
3. Recommendation use
```
php artisan view:cache
```

### Default Login
```
email : starmoozie@gmail.com
password: password
```