<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\DesawarRecord;
use App\Models\GameType;
use App\Models\DesawarMarket;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DesawarRecordChartService
{
    public static function getChartData()
    {
        $desawarMarkets = DesawarMarket::latest()->get();
        $jodiData = self::getJodiData();
        $baharCounts = self::getGameTypeCounts('bahar');
        $andarCounts = self::getGameTypeCounts('andar');

        return compact('jodiData', 'desawarMarkets', 'andarCounts', 'baharCounts');
    }

    protected static function getJodiData()
    {
        $query = DesawarRecord::select(
            'number',
            DB::raw('SUM(amount) as total_amount')
        )->whereRaw('LENGTH(number) <= 2'); // Ensure number length is 2 or less

        if (request()->has('market_id')) {
            $market = DesawarMarket::findOrFail(request()->query('market_id'));
            [$openTime, $closeTime] = static::getTimeFrame($market);

            $query->where('desawar_market_id', $market->id)
                ->whereBetween('created_at', [$openTime, $closeTime]);
        } else {
            $markets = DesawarMarket::all();
            $conditions = [];

            foreach ($markets as $market) {
                [$openTime, $closeTime] = static::getTimeFrame($market);

                $conditions[] = [
                    'desawar_market_id' => $market->id,
                    'created_at' => [$openTime, $closeTime]
                ];
            }

            $query->where(function ($query) use ($conditions) {
                foreach ($conditions as $index => $condition) {
                    if ($index === 0) {
                        $query->where('desawar_market_id', $condition['desawar_market_id'])
                            ->whereBetween('created_at', $condition['created_at']);
                    } else {
                        $query->orWhere(function ($query) use ($condition) {
                            $query->where('desawar_market_id', $condition['desawar_market_id'])
                                ->whereBetween('created_at', $condition['created_at']);
                        });
                    }
                }
            });
        }

        // Get the data as a collection
        $data = $query->groupBy('number')->get()->toArray();

        // Filter out non-numeric values
        $filteredData = collect($data)->reject(function ($item) {
            // Reject if 'number' contains any non-numeric characters
            return !ctype_digit($item['number']);
        });

        $jodiNewArray = self::initializeJodiArray();

        foreach ($filteredData as $item) {
            $number = $item['number'];
            $amount = $item['total_amount'] ?? 0;
            if (strlen($number) == 2) {
                $jodiNewArray[$number]['total_amount'] = $amount;
            }
        }

        // Add one more key
        $jodiNewArrayWith100 = $jodiNewArray['00'];
        $jodiNewArray[] = $jodiNewArrayWith100;

        return $jodiNewArray;
    }


    protected static function initializeJodiArray()
    {
        $jodiNewArray = [];
        for ($i = 0; $i < 100; $i++) {
            $key = str_pad($i, 2, '0', STR_PAD_LEFT);
            $jodiNewArray[$key] = ['total_amount' => 0];
        }
        return $jodiNewArray;
    }

    protected static function getGameTypeCounts($gameType)
    {
        $gameTypeModel = GameType::where('game_type', $gameType)
            ->where('type', 'desawar')->first();
        if (blank($gameTypeModel)) {
            return [];
        }

        $series = ["000", "111", "222", "333", "444", "555", "666", "777", "888", "999"];
        $totals = array_fill_keys($series, 0);

        $query = DesawarRecord::select(
            'number',
            DB::raw('SUM(amount) as total_amount')
        )->where('game_type_id', $gameTypeModel->id)
            ->whereIn('number', $series)
            ->groupBy('number');

        if (request()->has('market_id')) {
            $market = DesawarMarket::findOrFail(request()->query('market_id'));
            [$openTime, $closeTime] = static::getTimeFrame($market);
            $query->where('desawar_market_id', $market->id)
                ->whereBetween('created_at', [$openTime, $closeTime]);
        } else {
            $markets = DesawarMarket::all();

            $query->where(function ($query) use ($markets) {
                foreach ($markets as $index => $market) {
                    [$openTime, $closeTime] = self::getTimeFrame($market);
                    if ($index === 0) {
                        $query->where('desawar_market_id', $market->id)
                            ->whereBetween('created_at', [$openTime, $closeTime]);
                    } else {
                        $query->orWhere(function ($query) use ($market, $openTime, $closeTime) {
                            $query->where('desawar_market_id', $market->id)
                                ->whereBetween('created_at', [$openTime, $closeTime]);
                        });
                    }
                }
            });
        }

        // Get the data as a collection
        $records = $query->get();

        // Filter out non-numeric values
        $filteredRecords = $records->filter(function ($record) {
            // Reject if 'number' contains any non-numeric characters
            return ctype_digit($record->number);
        });

        foreach ($filteredRecords as $record) {
            $number = $record->number;
            if (isset($totals[$number])) {
                $totals[$number] = $record->total_amount;
            }
        }

        return $totals;
    }


    private static function getTimeFrame($market)
    {
        $now = request()->has('date') ? Carbon::parse(request('date')) : Carbon::now();

        $openTime = Carbon::parse($market->open_time)->setDate($now->year, $now->month, $now->day);
        $closeTime = Carbon::parse($market->close_time)->setDate($now->year, $now->month, $now->day);
        $nowTime = Carbon::now();

        //if close time is less than open time, it means close time is on next day
        if ($closeTime->lt($openTime)) {
            if ($nowTime->lt($closeTime)) {
                $openTime->subDay();
            } else {
                $closeTime->addDay();
            }
        }

        return [$openTime, $closeTime];
    }
}
