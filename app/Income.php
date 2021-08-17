<?php

    namespace App;

    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;
    use Spatie\MediaLibrary\HasMedia;
    use Spatie\MediaLibrary\InteractsWithMedia;
    use Spatie\MediaLibrary\MediaCollections\Models\Media;
    use Illuminate\Support\Facades\Cache;

    class Income extends Model implements HasMedia {

        use HasFactory, InteractsWithMedia;

        protected $guarded = [''];

        public function registerMediaCollections(): void
        {
            $this->addMediaCollection('receipt')
                ->singleFile()
                ->useFallbackUrl(config('app.placeholder') . '160.png')
                ->useFallbackPath(config('app.placeholder') . '160.png');
//            ->registerMediaConversions(function (Media $media) {
//                $this
//                    ->addMediaConversion('thumb')
//                    ->width(160)
//                    ->height(160);
//            });
        }


        /**
         * Flush the cache
         */
        public static function flushCache()
        {
            Cache::forget('incomes.all');
            Cache::forget('incomes.today');
        }


        // Forget cache key on storing or updating
        public static function boot()
        {
            parent::boot();
            static::saving(function () {
                Cache::forget('incomes.all');
                Cache::forget('incomes.today');
            });
        }


        public function category()
        {
            return $this->belongsTo(Category::class);
        }

        public function scopeToday($query)
        {
            $today = Carbon::now();

            return $query->whereBetween('created_at', [$today->startOfDay()->format('Y-m-d H:i:s'), $today->endOfDay()->format('Y-m-d H:i:s')]);


        }


        public function scopeLastMonth($query)
        {
            $start = Carbon::now()->subMonth()->startOfMonth()->startOfMonth()->startOfDay()->format('Y-m-d H:i:s');
            $end = Carbon::now()->subMonth()->startOfMonth()->endOfMonth()->endOfDay()->format('Y-m-d H:i:s');

            return $query->whereBetween('created_at', [$start, $end]);

        }


        public function scopeThisMonth($query)
        {
            $today = Carbon::now();

            return $query->whereBetween('created_at', [$today->startOfMonth()->startOfDay()->format('Y-m-d H:i:s'), $today->endOfMonth()->endOfDay()->format('Y-m-d H:i:s')]);


        }


    }
