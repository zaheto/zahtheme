<?php

namespace App;

class Calculator
{
    public function calculate($request)
    {
        $materials = [];

        if (isset($request['gamma'])) {
            try {
                $materials['gamma'] = $this->calculateGammaMaterials($request['gamma']);
            } catch (\Exception $e) {
                return rest_ensure_response([
                    'error' => true,
                    'message' => "Error calculating materials for Gamma: " . $e->getMessage()
                ]);
            }
        }

        return rest_ensure_response($materials);
    }

    private function calculateGammaMaterials($data)
    {
        $materials = [];

        foreach ($data as $row) {
            $width = floatval($row['width']);
            $height = floatval($row['height']);
            $quantity = intval($row['quantity']);

            if ($width <= 0 || $height <= 0 || $quantity <= 0) {
                throw new \InvalidArgumentException("Invalid dimensions or quantity for Gamma");
            }

            $blindsProfilePcs = ceil(5 * $quantity);
            $blindsProfileLm = round($width * 4.95, 2) * $quantity;
            $uProfileLeft = 1 * $quantity;
            $uProfileLeftLm = round($height, 2) * $quantity;
            $uProfileRight = 1 * $quantity;
            $uProfileRightLm = round($height, 2) * $quantity;
            $horizontalUProfile = 1 * $quantity;
            $horizontalUProfileLm = round($width, 2) * $quantity;
            $reinforcingProfile = 0;
            $reinforcingProfileLm = 0;
            $rivets = 100 * $quantity;
            $selfTappingScrews = 10 * $quantity;
            $dowels = 10 * $quantity;
            $corner = 0;

            $materials[] = [
                'blinds_profile' => $blindsProfilePcs,
                'blinds_profile_lm' => $blindsProfileLm,
                'u_profile_left' => $uProfileLeft,
                'u_profile_left_lm' => $uProfileLeftLm,
                'u_profile_right' => $uProfileRight,
                'u_profile_right_lm' => $uProfileRightLm,
                'horizontal_u_profile' => $horizontalUProfile,
                'horizontal_u_profile_lm' => $horizontalUProfileLm,
                'reinforcing_profile' => $reinforcingProfile,
                'reinforcing_profile_lm' => $reinforcingProfileLm,
                'rivets' => $rivets,
                'self_tapping_screws' => $selfTappingScrews,
                'dowels' => $dowels,
                'corner' => $corner,
            ];
        }

        return $materials;
    }
}