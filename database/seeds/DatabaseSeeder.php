<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        Model::unguard();
        
        // Ask for db migration refresh, default is no
        if ($this->command->confirm('Do you wish to refresh migration before seeding, it will clear all old data ?')) {
            
            // Call the php artisan migrate:refresh using Artisan
            $this->command->call('migrate:refresh');
            
            $this->command->line("Data cleared, starting from blank database.");
        }
        
        // How many users you need, defaulting to 20
        $numberOfUser = $this->command->ask('How many users do you need ?', 20);
        
        $this->command->info("Creating {$numberOfUser} users, each will have a channel associated.");
        
        // Create the channel, it will create a user and assign the channel
        $channels = factory(App\Channel::class, $numberOfUser)->create();
        
        $this->command->info('Users Created!');
        
        // How many videos per channel
        $videoRange = $this->command->ask('How many videos per channel should have, give a range ?', '10-20');
        
        // Loop and create the video in range with channel id
        $channels->each(function ($channel) use ($videoRange) {
            factory(App\Video::class, $this->getRandomRange($videoRange))
                ->create(['channel_id' => $channel->id]);
        });
        
        $this->command->info("Now all Channels have {$videoRange} videos !");
        
        // Now how many comments per video
        $commentRange = $this->command->ask('Give a range for comments per video ?', '0-20');
        
        // Get all video and give each one some comment in asked range
        \App\Video::all()->each(function () use ($commentRange) {
            factory(App\Comment::class, $this->getRandomRange($commentRange))->create();
        });
        
        $this->command->info("{$commentRange} Comment(s) added for videos !");
        
        $this->command->info("Hurrah! Database has been seeded.");
        
        // Re Guard model
        Model::reguard();
    }
    
    /**
     * Return random value in given range
     *
     * @param $videoRange
     * @return int
     */
    function getRandomRange($videoRange)
    {
        return rand(...explode('-', $videoRange));
    }
}