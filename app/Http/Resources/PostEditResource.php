<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostEditResource extends JsonResource
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
            'user_name' => optional($this->user)->name ?? 'Unknown User',
            'category_name' =>optional( $this->category)->name ?? 'Unknown Category',
            'title' => $this->title,
            'description' =>  $this->description,
            'image' => $this->image ? asset('storage/media/' . $this->image->file_name) : null,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d h:i:s A'),
            'created_at_readable' => Carbon::parse($this->created_at)->diffForHumans(),
        ];
    }
}