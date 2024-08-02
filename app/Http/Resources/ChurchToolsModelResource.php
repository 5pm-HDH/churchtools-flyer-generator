<?php

namespace App\Http\Resources;

use CTApi\Traits\Model\FillWithData;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin FillWithData
 */
class ChurchToolsModelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->toData();
    }
}
