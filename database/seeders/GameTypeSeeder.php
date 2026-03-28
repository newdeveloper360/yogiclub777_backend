<?php

namespace Database\Seeders;

use App\Models\AppData;
use App\Models\GameType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function PHPSTORM_META\type;

class GameTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Single Digit - General
        GameType::create([
            'id' => 4,
            'name' => 'Single Digit',
            'game_type' => 'single_digit',
            'multiply_by' => 9.5,
            'type' => 'general'
        ]);

        // Jodi Digit - General
        GameType::create([
            'id' => 5,
            'name' => 'Jodi Digit',
            'game_type' => 'jodi_digit',
            'multiply_by' => 95,
            'type' => 'general'
        ]);

        // Single Pana - General
        GameType::create([
            'id' => 6,
            'name' => 'Single Pana',
            'game_type' => 'single_pana',
            'multiply_by' => 150,
            'type' => 'general'
        ]);

        // Double Pana - General
        GameType::create([
            'id' => 7,
            'name' => 'Double Pana',
            'game_type' => 'double_pana',
            'multiply_by' => 300,
            'type' => 'general'
        ]);

        // Triple Pana - General
        GameType::create([
            'id' => 8,
            'name' => 'Triple Pana',
            'game_type' => 'triple_pana',
            'multiply_by' => 900,
            'type' => 'general'
        ]);

        // Half Sangam A - General
        GameType::create([
            'id' => 9,
            'name' => 'Half Sangam A',
            'game_type' => 'half_sangam_a',
            'multiply_by' => 1000,
            'type' => 'general'
        ]);

        // Half Sangam B - General
        GameType::create([
            'id' => 10,
            'name' => 'Half Sangam B',
            'game_type' => 'half_sangam_b',
            'multiply_by' => 1000,
            'type' => 'general'
        ]);

        // Full Sangam - General
        GameType::create([
            'id' => 11,
            'name' => 'Full Sangam',
            'game_type' => 'full_sangam',
            'multiply_by' => 10000,
            'type' => 'general'
        ]);

        // Single Digit - Start_line
        GameType::create([
            'id' => 12,
            'name' => 'Single Digit',
            'game_type' => 'single_digit',
            'multiply_by' => 10,
            'type' => 'start_line'
        ]);

        // Single Pana - Start_line
        GameType::create([
            'id' => 13,
            'name' => 'Single Pana',
            'game_type' => 'single_pana',
            'multiply_by' => 160,
            'type' => 'start_line'
        ]);

        // Double Pana - Start_line
        GameType::create([
            'id' => 14,
            'name' => 'Double Pana',
            'game_type' => 'double_pana',
            'multiply_by' => 320,
            'type' => 'start_line'
        ]);

        // Triple Pana - Start_line
        GameType::create([
            'id' => 15,
            'name' => 'Triple Pana',
            'game_type' => 'triple_pana',
            'multiply_by' => 1000,
            'type' => 'start_line'
        ]);

        // Jodi - Desawar
        GameType::create([
            'id' => 16,
            'name' => 'Jodi',
            'game_type' => 'jodi',
            'multiply_by' => 90,
            'type' => 'desawar'
        ]);

        // Andar - Desawar
        GameType::create([
            'id' => 17,
            'name' => 'Andar',
            'game_type' => 'andar',
            'multiply_by' => 9,
            'type' => 'desawar'
        ]);
        // Bahar - Desawar
        GameType::create([
            'id' => 18,
            'name' => 'Bahar',
            'game_type' => 'bahar',
            'multiply_by' => 9,
            'type' => 'desawar'
        ]);
    }
}
