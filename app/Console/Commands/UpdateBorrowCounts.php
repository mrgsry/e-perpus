<?php

namespace App\Console\Commands;

use App\Models\Buku;
use Illuminate\Console\Command;

class UpdateBorrowCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buku:update-borrow-counts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update borrow counts for all books based on peminjaman records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating borrow counts...');

        $bukus = Buku::withCount('peminjamen')->get();

        foreach ($bukus as $buku) {
            $buku->borrow_count = $buku->peminjamen_count;
            $buku->save();
        }

        $this->info('Borrow counts updated successfully for ' . $bukus->count() . ' books.');

        return 0;
    }
}