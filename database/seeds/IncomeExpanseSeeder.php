<?php

    namespace Database\Seeders;

    use App\Expanse;
    use App\Income;
    use Carbon\Carbon;
    use Faker\Factory;
    use Illuminate\Database\Seeder;
    use Illuminate\Support\Facades\DB;

    class IncomeExpanseSeeder extends Seeder {

        /**
         * Run the database seeds.
         *
         * @return void
         */
        public function run()
        {

            // Reset cached roles and permissions
            app()[ \Spatie\Permission\PermissionRegistrar::class ]->forgetCachedPermissions();
            $faker = Factory::create();

            $categories = [

                //////////////////////////Income Category////////////////////////////////////
                [


                    'name' => 'Condition Delivery',
                    'type' => 'income',
                ],

                [


                    'name' => 'Due CN Delivery',
                    'type' => 'income',
                ],

                [


                    'name' => 'Paid CN Delivery',
                    'type' => 'income',
                ],

                [


                    'name' => 'Early Bookings',
                    'type' => 'income',
                ],

                [


                    'name' => 'Advance Payment',
                    'type' => 'income',
                ],

                [

                    'name' => 'Loan Payment',
                    'type' => 'income',
                ],

                [

                      //8
                    'name' => 'Other',
                    'type' => 'income',
                ],

                //////////////////////////Expanse Category////////////////////////////////////
                [

                    'name' => 'Condition Delivery',
                    'type' => 'expanse',
                ],

                [

                    'name' => 'Condition Advance Payment',
                    'type' => 'expanse',
                ],

                [

                    'name' => 'T.T Delivery',
                    'type' => 'expanse',
                ],

                [

                    'name' => 'D.D Delivery',
                    'type' => 'expanse',
                ],

                [

                    'name' => 'Advance R/N',
                    'type' => 'expanse',
                ],

                [

                    'name' => 'Loan R/N',
                    'type' => 'expanse',
                ],

                [

                    'name' => 'Commissions',
                    'type' => 'expanse',
                ],

                [

                    'name' => 'H/O Payment',
                    'type' => 'expanse',
                ],

                [
                    //16
                    'name' => 'Other',
                    'type' => 'expanse',
                ],
            ];


            DB::table('categories')->insert($categories);

            for ( $i = 0; $i < 5000; $i++ )
            {

                Income::insert([
                    'category_id' => rand(1, 7),
                    'tracking_id' => rand(5000, 6000),
                    'condition_amount' => rand(1, 100),
                    'condition_charge' => rand(1, 100),
                    'booking_charge' => rand(1, 100),
                    'labour_charge' => rand(1, 100),
                    'other_amount' => rand(1, 100),
                    'previous_cash' => rand(1, 100),
                    'notes' => $faker->name(),
                    'created_at' => $faker->dateTimeBetween('-120 days', now()),
                    'updated_at' => $faker->dateTimeBetween('-10 days', now())
                ]);

                Expanse::insert([
                    'category_id' => rand(8, 16),
                    'tracking_id' => rand(5000, 6000),
                    'condition_delivery' => rand(1, 100),
                    'condition_advance_payment' => rand(1, 100),
                    'tt_delivery' => rand(1, 100),
                    'dd_delivery' => rand(1, 100),
                    'ho_payment' => rand(1, 100),
                    'advance_rn' => rand(1, 100),
                    'loan_rn' => rand(1, 100),
                    'commission' => rand(1, 100),
                    'other_amount' => rand(1, 100),
                    'previous_cash' => rand(1, 100),
                    'notes' => $faker->name(),
                    'created_at' => $faker->dateTimeBetween('-120 days', now()),
                    'updated_at' => $faker->dateTimeBetween('-10 days', now())
                ]);
            }

        }

    }