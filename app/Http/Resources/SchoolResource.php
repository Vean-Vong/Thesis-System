<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SchoolResource extends JsonResource
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
            'code' => $this->code,
            'khmer_name' => $this->khmer_name,
            'latin_name' => $this->latin_name,
            'short_name' => $this->short_name,
            'sort' => $this->sort,
            'is_city' => $this->is_city,
            'is_district' => $this->is_district,
            'is_khan' => $this->is_khan,
        ];
    }
}