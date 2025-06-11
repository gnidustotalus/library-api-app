<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'isbn' => $this->isbn,
            'description' => $this->description,
            'pages' => $this->pages,
            'published_at' => $this->published_at?->format('Y-m-d'),
            'language' => $this->language,
            'price' => $this->price,
            'formatted_price' => $this->formatted_price,
            'stock_quantity' => $this->stock_quantity,
            'is_available' => $this->is_available,
            'availability_status' => $this->availability_status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            
            'author' => new AuthorResource($this->whenLoaded('author')),
            'publisher' => new PublisherResource($this->whenLoaded('publisher')),
            'category' => new CategoryResource($this->whenLoaded('category')),
        ];
    }
}
