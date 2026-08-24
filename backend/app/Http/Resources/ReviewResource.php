<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'body' => $this->body,
            'rating' => $this->rating,
            'advantages' => $this->advantages,
            'disadvantages' => $this->disadvantages,
            'status' => $this->status,
            'is_buyer' => $this->is_buyer,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'user' => $this->whenLoaded('user', function () {
                return [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                ];
            }),

            'product' => $this->whenLoaded('product', function () {
                return [
                    'id' => $this->product->id,
                    'title' => $this->product->title,
                    'slug' => $this->product->slug,
                ];
            }),
            'likes_count' => $this->whenCounted('likes'),
            'dislikes_count' => $this->whenCounted('dislikes'),
            'user_reaction' => $this->when($user !== null, function () use ($user) {
                if (!$user) return null;

                $reaction = $this->relationLoaded('reactions')
                    ? $this->reactions->where('user_id', $user->id)->first()
                    : $this->reactions()->where('user_id', $user->id)->first();

                return $reaction?->type;
            }),
        ];
    }
}