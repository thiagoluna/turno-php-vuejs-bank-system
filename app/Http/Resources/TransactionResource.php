<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "bank_account_id" => $this->bank_account_id,
            "description" => $this->description ?? "Not informed",
            "date" => Carbon::parse($this->date)->format('m/d/Y, h:i A'),
            "type" => $this->type,
            "amount" => number_format($this->amount, 2, ',', ''),
            "status" => $this->status,
            "image_url" => $this->image_url,
            "created_at" => Carbon::parse($this->created_at)->format('m/d/Y, h:i A'),
            "updated_at" => Carbon::parse($this->updated_at)->format('m/d/Y, h:i A'),
        ];
    }
}
