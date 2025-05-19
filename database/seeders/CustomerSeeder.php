<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Create 50 customers with varied profiles
        for ($i = 0; $i < 50; $i++) {
            // Create base user as customer
            $user = User::create([
                'first_name' => fake()->firstName(),
                'last_name' => fake()->lastName(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'), // Default password for all customers
                'contact_number' => fake()->phoneNumber(),
                'role' => 'customer',
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);

            // Add 1-3 addresses for each customer
            $addressCount = rand(1, 3);
            for ($j = 0; $j < $addressCount; $j++) {
                $isDefault = ($j === 0); // First address is default
                
                Address::create([
                    'user_id' => $user->id,
                    'name' => $j === 0 ? 'Home' : ($j === 1 ? 'Office' : 'Other'),
                    'type' => $j === 0 ? 'home' : ($j === 1 ? 'office' : 'other'),
                    'street_address' => fake()->streetAddress(),
                    'city' => fake()->city(),
                    'state' => fake()->state(),
                    'postal_code' => fake()->postcode(),
                    'country' => 'Philippines',
                    'is_default' => $isDefault,
                    'phone_number' => fake()->phoneNumber(),
                    'additional_info' => $j === 0 ? 'Main residence' : ($j === 1 ? 'Work address' : 'Alternative address'),
                ]);
            }

            // Randomly subscribe some users to the newsletter (60% chance)
            if (rand(1, 100) <= 60) {
                \DB::table('newsletter_subscribers')->insert([
                    'email' => $user->email,
                    'name' => $user->first_name . ' ' . $user->last_name,
                    'is_active' => true,
                    'subscribed_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Create a shopping cart for each user
            \DB::table('carts')->insert([
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create 5 customers with social login credentials
        for ($i = 0; $i < 5; $i++) {
            $firstName = fake()->firstName();
            $lastName = fake()->lastName();
            
            User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'google_id' => Str::random(21), // Simulated Google ID
                'avatar' => 'https://lh3.googleusercontent.com/a/' . Str::random(32), // Simulated Google avatar URL
                'provider' => 'google',
                'profile_picture_url' => 'https://lh3.googleusercontent.com/a/' . Str::random(32),
                'contact_number' => fake()->phoneNumber(),
                'role' => 'customer',
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);
        }

        // Create 5 customers with Facebook login
        for ($i = 0; $i < 5; $i++) {
            $firstName = fake()->firstName();
            $lastName = fake()->lastName();
            
            User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'facebook_id' => Str::random(16), // Simulated Facebook ID
                'avatar' => 'https://graph.facebook.com/' . Str::random(16) . '/picture', // Simulated Facebook avatar URL
                'provider' => 'facebook',
                'profile_picture_url' => 'https://graph.facebook.com/' . Str::random(16) . '/picture',
                'contact_number' => fake()->phoneNumber(),
                'role' => 'customer',
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);
        }
    }
} 