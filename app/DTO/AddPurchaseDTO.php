<?php

namespace App\DTO;

class AddPurchaseDTO
{
    public function __construct(
        public string  $bank_account_id,
        public string $description,
        public string $date,
        public string $type,
        public float $amount,
        public string $status,
        public ?string $image_url,
    ) {
    }
}
