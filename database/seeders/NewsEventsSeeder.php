<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NewsEventsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [

            // -------------------------------------------------------------
            // ------------------------ FEBRUARY ----------------------------
            // -------------------------------------------------------------

            [
                'date' => '2025-02-03 17:00',
                'currency' => 'USD',
                'event_name' => 'ISM Manufacturing PMI',
                'impact' => 'high',
                'actual' => '50.9',
                'forecast' => '49.3',
                'previous' => '49.3',
            ],
            [
                'date' => '2025-02-04 17:00',
                'currency' => 'USD',
                'event_name' => 'JOLTS Job Openings',
                'impact' => 'high',
                'actual' => '7.60M',
                'forecast' => '8.01M',
                'previous' => '8.16M',
            ],
            [
                'date' => '2025-02-05 12:10',
                'currency' => 'USD',
                'event_name' => 'President Trump Speaks',
                'impact' => 'high',
            ],
            [
                'date' => '2025-02-05 15:15',
                'currency' => 'USD',
                'event_name' => 'ADP Non-Farm Employment Change',
                'impact' => 'high',
                'actual' => '183K',
                'forecast' => '148K',
                'previous' => '176K',
            ],
            [
                'date' => '2025-02-05 17:00',
                'currency' => 'USD',
                'event_name' => 'ISM Services PMI',
                'impact' => 'high',
                'actual' => '52.8',
                'forecast' => '54.2',
                'previous' => '54.1',
            ],
            [
                'date' => '2025-02-06 15:30',
                'currency' => 'USD',
                'event_name' => 'Unemployment Claims',
                'impact' => 'high',
                'actual' => '219K',
                'forecast' => '214K',
                'previous' => '208K',
            ],
            [
                'date' => '2025-02-07 15:30',
                'currency' => 'USD',
                'event_name' => 'Average Hourly Earnings m/m',
                'impact' => 'high',
                'actual' => '0.5%',
                'forecast' => '0.3%',
                'previous' => '0.3%',
            ],
            [
                'date' => '2025-02-07 15:30',
                'currency' => 'USD',
                'event_name' => 'Non-Farm Employment Change',
                'impact' => 'high',
                'actual' => '143K',
                'forecast' => '169K',
                'previous' => '307K',
            ],
            [
                'date' => '2025-02-07 15:30',
                'currency' => 'USD',
                'event_name' => 'Unemployment Rate',
                'impact' => 'high',
                'actual' => '4.0%',
                'forecast' => '4.1%',
                'previous' => '4.1%',
            ],
            [
                'date' => '2025-02-10 23:30',
                'currency' => 'USD',
                'event_name' => 'President Trump Speaks',
                'impact' => 'high',
            ],

            // FEB 11
            [
                'date' => '2025-02-11 17:00',
                'currency' => 'USD',
                'event_name' => 'Fed Chair Powell Testifies',
                'impact' => 'high',
            ],
            [
                'date' => '2025-02-11 22:40',
                'currency' => 'USD',
                'event_name' => 'President Trump Speaks',
                'impact' => 'high',
            ],

            // FEB 12
            [
                'date' => '2025-02-12 15:30',
                'currency' => 'USD',
                'event_name' => 'Core CPI m/m',
                'impact' => 'high',
                'actual' => '0.4%',
                'forecast' => '0.3%',
                'previous' => '0.2%',
            ],
            [
                'date' => '2025-02-12 15:30',
                'currency' => 'USD',
                'event_name' => 'CPI m/m',
                'impact' => 'high',
                'actual' => '0.5%',
                'forecast' => '0.3%',
                'previous' => '0.4%',
            ],
            [
                'date' => '2025-02-12 15:30',
                'currency' => 'USD',
                'event_name' => 'CPI y/y',
                'impact' => 'high',
                'actual' => '3.0%',
                'forecast' => '2.9%',
                'previous' => '2.9%',
            ],
            [
                'date' => '2025-02-12 17:00',
                'currency' => 'USD',
                'event_name' => 'Fed Chair Powell Testifies',
                'impact' => 'high',
            ],
            [
                'date' => '2025-02-12 22:48',
                'currency' => 'USD',
                'event_name' => 'President Trump Speaks',
                'impact' => 'high',
            ],

            // FEB 13
            [
                'date' => '2025-02-13 15:30',
                'currency' => 'USD',
                'event_name' => 'Core PPI m/m',
                'impact' => 'high',
                'actual' => '0.3%',
                'forecast' => '0.3%',
                'previous' => '0.4%',
            ],
            [
                'date' => '2025-02-13 15:30',
                'currency' => 'USD',
                'event_name' => 'PPI m/m',
                'impact' => 'high',
                'actual' => '0.4%',
                'forecast' => '0.3%',
                'previous' => '0.5%',
            ],
            [
                'date' => '2025-02-13 15:30',
                'currency' => 'USD',
                'event_name' => 'Unemployment Claims',
                'impact' => 'high',
                'actual' => '213K',
                'forecast' => '217K',
                'previous' => '220K',
            ],
            [
                'date' => '2025-02-13 20:36',
                'currency' => 'USD',
                'event_name' => 'President Trump Speaks',
                'impact' => 'high',
            ],

            // FEB 14
            [
                'date' => '2025-02-14 15:30',
                'currency' => 'USD',
                'event_name' => 'Core Retail Sales m/m',
                'impact' => 'high',
                'actual' => '-0.4%',
                'forecast' => '0.3%',
                'previous' => '0.7%',
            ],
            [
                'date' => '2025-02-14 15:30',
                'currency' => 'USD',
                'event_name' => 'Retail Sales m/m',
                'impact' => 'high',
                'actual' => '-0.9%',
                'forecast' => '-0.2%',
                'previous' => '0.7%',
            ],
            [
                'date' => '2025-02-14 21:08',
                'currency' => 'USD',
                'event_name' => 'President Trump Speaks',
                'impact' => 'high',
            ],

            // FEB 19
            [
                'date' => '2025-02-19 21:00',
                'currency' => 'USD',
                'event_name' => 'FOMC Meeting Minutes',
                'impact' => 'high',
            ],

            // FEB 20
            [
                'date' => '2025-02-20 15:30',
                'currency' => 'USD',
                'event_name' => 'Unemployment Claims',
                'impact' => 'high',
                'actual' => '219K',
                'forecast' => '215K',
                'previous' => '214K',
            ],

            // FEB 21
            [
                'date' => '2025-02-21 16:45',
                'currency' => 'USD',
                'event_name' => 'Flash Manufacturing PMI',
                'impact' => 'high',
                'actual' => '51.6',
                'forecast' => '51.3',
                'previous' => '51.2',
            ],
            [
                'date' => '2025-02-21 16:45',
                'currency' => 'USD',
                'event_name' => 'Flash Services PMI',
                'impact' => 'high',
                'actual' => '49.7',
                'forecast' => '53.0',
                'previous' => '52.9',
            ],

            // FEB 27
            [
                'date' => '2025-02-27 15:30',
                'currency' => 'USD',
                'event_name' => 'Prelim GDP q/q',
                'impact' => 'high',
                'actual' => '2.3%',
                'forecast' => '2.3%',
                'previous' => '2.3%',
            ],
            [
                'date' => '2025-02-27 15:30',
                'currency' => 'USD',
                'event_name' => 'Unemployment Claims',
                'impact' => 'high',
                'actual' => '242K',
                'forecast' => '222K',
                'previous' => '220K',
            ],

            // FEB 28
            [
                'date' => '2025-02-28 15:30',
                'currency' => 'USD',
                'event_name' => 'Core PCE Price Index m/m',
                'impact' => 'high',
                'actual' => '0.3%',
                'forecast' => '0.3%',
                'previous' => '0.2%',
            ],

            // -------------------------------------------------------------
            // ------------------------ MARCH ------------------------------
            // -------------------------------------------------------------

            [
                'date' => '2025-03-03 17:00',
                'currency' => 'USD',
                'event_name' => 'ISM Manufacturing PMI',
                'impact' => 'high',
                'actual' => '50.3',
                'forecast' => '50.6',
                'previous' => '50.9',
            ],

            // MAR 5
            [
                'date' => '2025-03-05 15:15',
                'currency' => 'USD',
                'event_name' => 'ADP Non-Farm Employment Change',
                'impact' => 'high',
                'actual' => '77K',
                'forecast' => '141K',
                'previous' => '186K',
            ],
            [
                'date' => '2025-03-05 17:00',
                'currency' => 'USD',
                'event_name' => 'ISM Services PMI',
                'impact' => 'high',
                'actual' => '53.5',
                'forecast' => '52.5',
                'previous' => '52.8',
            ],

            // MAR 6
            [
                'date' => '2025-03-06 15:30',
                'currency' => 'USD',
                'event_name' => 'Unemployment Claims',
                'impact' => 'high',
                'actual' => '221K',
                'forecast' => '234K',
                'previous' => '242K',
            ],

            // MAR 7 NFP
            [
                'date' => '2025-03-07 15:30',
                'currency' => 'USD',
                'event_name' => 'Average Hourly Earnings m/m',
                'impact' => 'high',
                'actual' => '0.3%',
                'forecast' => '0.3%',
                'previous' => '0.4%',
            ],
            [
                'date' => '2025-03-07 15:30',
                'currency' => 'USD',
                'event_name' => 'Non-Farm Employment Change',
                'impact' => 'high',
                'actual' => '151K',
                'forecast' => '159K',
                'previous' => '125K',
            ],
            [
                'date' => '2025-03-07 15:30',
                'currency' => 'USD',
                'event_name' => 'Unemployment Rate',
                'impact' => 'high',
                'actual' => '4.1%',
                'forecast' => '4.0%',
                'previous' => '4.0%',
            ],

            // MAR 12 CPI
            [
                'date' => '2025-03-12 14:30',
                'currency' => 'USD',
                'event_name' => 'Core CPI m/m',
                'impact' => 'high',
                'actual' => '0.2%',
                'forecast' => '0.3%',
                'previous' => '0.4%',
            ],
            [
                'date' => '2025-03-12 14:30',
                'currency' => 'USD',
                'event_name' => 'CPI m/m',
                'impact' => 'high',
                'actual' => '0.2%',
                'forecast' => '0.3%',
                'previous' => '0.5%',
            ],
            [
                'date' => '2025-03-12 14:30',
                'currency' => 'USD',
                'event_name' => 'CPI y/y',
                'impact' => 'high',
                'actual' => '2.8%',
                'forecast' => '2.9%',
                'previous' => '3.0%',
            ],

            // MAR 13 PPI
            [
                'date' => '2025-03-13 14:30',
                'currency' => 'USD',
                'event_name' => 'Core PPI m/m',
                'impact' => 'high',
                'actual' => '-0.1%',
                'forecast' => '0.3%',
                'previous' => '0.5%',
            ],
            [
                'date' => '2025-03-13 14:30',
                'currency' => 'USD',
                'event_name' => 'PPI m/m',
                'impact' => 'high',
                'actual' => '0.0%',
                'forecast' => '0.3%',
                'previous' => '0.6%',
            ],

            // MAR 19 FOMC
            [
                'date' => '2025-03-19 20:00',
                'currency' => 'USD',
                'event_name' => 'Federal Funds Rate',
                'impact' => 'high',
                'actual' => '4.50%',
                'forecast' => '4.50%',
                'previous' => '4.50%',
            ],

            // MAR 24 PMI
            [
                'date' => '2025-03-24 15:45',
                'currency' => 'USD',
                'event_name' => 'Flash Manufacturing PMI',
                'impact' => 'high',
                'actual' => '49.8',
                'forecast' => '51.9',
                'previous' => '52.7',
            ],
            [
                'date' => '2025-03-24 15:45',
                'currency' => 'USD',
                'event_name' => 'Flash Services PMI',
                'impact' => 'high',
                'actual' => '54.3',
                'forecast' => '51.2',
                'previous' => '51.0',
            ],

            // MAR 27 GDP Final
            [
                'date' => '2025-03-27 14:30',
                'currency' => 'USD',
                'event_name' => 'Final GDP q/q',
                'impact' => 'high',
                'actual' => '2.4%',
                'forecast' => '2.3%',
                'previous' => '2.3%',
            ],

            // MAR 28 PCE
            [
                'date' => '2025-03-28 14:30',
                'currency' => 'USD',
                'event_name' => 'Core PCE Price Index m/m',
                'impact' => 'high',
                'actual' => '0.4%',
                'forecast' => '0.3%',
                'previous' => '0.3%',
            ],
        ];

        foreach ($events as $e) {
            DB::table('news_events')->insert([
                'currency'    => $e['currency'],
                'event_name'  => $e['event_name'],
                'event_time'  => Carbon::parse($e['date']),
                'impact'      => $e['impact'],
                'actual'      => $e['actual'] ?? null,
                'forecast'    => $e['forecast'] ?? null,
                'previous'    => $e['previous'] ?? null,
                'raw'         => null,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}