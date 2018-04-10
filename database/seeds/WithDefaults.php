<?php

trait WithDefaults
{
    public function withTimestamps(array $data)
    {
        return collect([
            'updated_at' => now(),
            'created_at' => now(),
        ])->merge($data)->toArray();
    }
}
