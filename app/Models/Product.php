<?php

namespace App\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory,Sluggable;
    protected $table = 'products';
    protected $guarded = [];
    protected $appends = ['quantityCheck','saleCheck','minPrice'];


    public function sluggable(): array
    {
        return [
            'slug'=>[
                'source'=>'name'
            ]
        ];
    }

    public function tags():BelongsToMany
    {
        return $this->belongsToMany(Tag::class,'product_tag');
    }

    public function scopeFilter($query)
    {
        if (request()->has('attribute'))
        {
           foreach (request()->attribute as $attribute)
           {
               $query->whereHas('attributes',function ($query) use($attribute){
                  foreach (explode('-',$attribute) as $index=> $value)
                  {
                      if ($index == 0)
                      {
                          $query->where('value',$value);
                      }else
                      {
                          $query->orWhere('value',$value);
                      }
                  }
               });
           }
        }

        if (request()->has('variation'))
        {
            $query->whereHas('variations',function ($query){
               foreach (explode('-',request()->variation) as $index=> $variation)
               {
                   if ($index == 0)
                   {
                       $query->where('value',$variation);
                   }else
                   {
                       $query->orWhere('value',$variation);
                   }
               }
            });
        }

        if (request()->has('sortBy'))
        {
            $sortBy = request()->sortBy;
            switch ($sortBy)
            {
                case 'maxPrice':
                    $query->orderByDesc(
                        ProductVariation::select('price')->whereColumn('product_variations.product_id','products.id')->orderByDesc('sale_price')->take(1)
                    );
                    break;

                case 'minPrice':
                    $query->orderBy(
                        ProductVariation::select('price')->whereColumn('product_variations.product_id','products.id')->orderBy('sale_price')->take(1)
                    );
                    break;

                case 'latest':
                    $query->latest();
                    break;

                case 'oldest':
                    $query->oldest();
                    break;

                default :
                    $query;
                    break;
            }
        }
        return $query;
    }

    public function scopeSearch($query)
    {
        $keyword = request()->search;
        if (request()->has('search') && trim($keyword) != '')
        {
            $query->where('name','LIKE','%'.trim($keyword).'%');
        }
        return $query;
    }

    public function brand():BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getIsActiveAttribute($is_active)
    {
        return $is_active ? 'فعال' : 'غیرفعال';
    }

    public function attributes():HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function variations():HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images():HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function getQuantityCheckAttribute()
    {
        return $this->variations()->where('quantity','>',0)->first() ?? 0;
    }

    public function getSaleCheckAttribute()
    {
        return $this->variations()->where('quantity','>',0)
            ->where('sale_price','!=',null)
            ->where('date_on_sale_from','<',Carbon::now())
            ->where('date_on_sale_to','>',Carbon::now())
            ->orderBy('sale_price')
            ->first() ?? false;
    }

    public function getMinPriceAttribute()
    {
        return $this->variations()->where('quantity','>',0)
            ->orderBy('price')
            ->first() ?? false;
    }

    public function rates():HasMany
    {
        return $this->hasMany(ProductRate::class);
    }
    public function comments():HasMany
    {
        return $this->hasMany(Comment::class)->where('approved',1);
    }

    public function wishlist($userId): bool
    {
        return $this->hasMany(Wishlist::class)->where('user_id',$userId)->exists();
    }

}
