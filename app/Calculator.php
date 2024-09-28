<?php

namespace App;

class Calculator
{
    public function calculate($request)
    {
        $materials = [];
        $models = ['gamma', 'atlas', 'sigma', 'piramida', 'terra'];

        foreach ($models as $model) {
            if (isset($request[$model])) {
                $materials[$model] = $this->calculateMaterials($model, $request[$model]);
            }
        }

        return rest_ensure_response($materials);
    }

    private function calculateMaterials($model, $data)
    {
        $materials = [];

        foreach ($data as $row) {
            $width = floatval($row['width']);
            $height = floatval($row['height']);
            $quantity = intval($row['quantity']);

            // Basic calculation example - adjust based on your specific requirements
            $materials[] = [
                'blinds_profile' => ceil($width * $quantity),
                'u_profile_left' => ceil($height * $quantity),
                'u_profile_right' => ceil($height * $quantity),
                'horizontal_u_profile' => ceil($width * $quantity),
                'rivets' => ceil($width * $height * 10 * $quantity),
                'self_tapping_screws' => ceil($width * $height * $quantity),
                'dowels' => ceil($width * $height * $quantity),
            ];

            // Add Terra-specific calculations
            if ($model === 'terra') {
                $cassette_distance = floatval($row['cassette_distance']) / 100; // Convert to meters
                $base_distance = floatval($row['base_distance']) / 100; // Convert to meters
                $optimal_height = floatval($row['optimal_height']);

                $materials[count($materials) - 1]['cassettes'] = ceil(($height - $base_distance) / $cassette_distance) * $quantity;
                $materials[count($materials) - 1]['optimal_height_difference'] = $optimal_height - $height;
            }
        }

        return $materials;
    }
}