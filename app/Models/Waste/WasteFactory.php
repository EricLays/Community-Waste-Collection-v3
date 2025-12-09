<?php

namespace App\Models\Waste;

final class WasteFactory
{
    public static function make(array $payload): Waste
    {
        $map = [
            'organic'    => WasteOrganic::class,
            'plastic'    => WastePlastic::class,
            'paper'      => WastePaper::class,
            'electronic' => WasteElectronic::class,
        ];

        $type = $payload['type'] ?? null;
        $cls  = $map[$type] ?? null;

        if (!$cls) {
            throw new \InvalidArgumentException('Unsupported waste type: ' . ($type ?? '<missing>'));
        }

        /** @var Waste $model */
        $model = new $cls();
        $model->fill($payload);

        // Ensure defaults when missing:
        $model->status = $model->status ?? Waste::STATUS_PENDING;

        return $model;
    }
}
