<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'base_price' => $this->base_price,
            'short_description' => $this->short_description,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'featured' => $this->featured,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'images' => $this->whenLoaded('media', fn () => $this->media->map(fn ($m) => $m->getUrl())),
            'skus' => SkuResource::collection($this->whenLoaded('skus')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
            'image' => $this->when($this->hasMedia('images'), fn () => $this->getFirstMediaUrl('images')),
            'children' => CategoryResource::collection($this->whenLoaded('children')),
        ];
    }
}

class SkuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'stock' => $this->stock,
            'is_active' => $this->is_active,
            'attributes' => $this->whenLoaded('attributeOptions', fn () =>
                $this->attributeOptions->groupBy('attribute.name')->map(fn ($opts, $name) => [
                    'name' => $name,
                    'values' => $opts->pluck('value'),
                ])->values()
            ),
        ];
    }
}
