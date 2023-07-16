<?php

namespace App\Console\Commands;

use App\Libraries\Traits\InteractWithQrCodeGenerator;
use App\Models\AttendanceCode;
use App\Models\Employee;
use Illuminate\Console\Command;

class GenerateQrCodeCommand extends Command
{
    use InteractWithQrCodeGenerator;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:qr {--force} {--count=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $totalEmployee = Employee::count();
        $totalBarcodeActive = AttendanceCode::active()->count();

        if ($this->option('force')) {
            for ($i = 0; $i < $totalEmployee; $i++) {
                AttendanceCode::create(['code' => $this->getCode(), 'status' => 'not scanned']);
            }
        } elseif ($this->option('count')) {
            for ($i = 0; $i < $this->option('count'); $i++) {
                AttendanceCode::create(['code' => $this->getCode(), 'status' => 'not scanned']);
            }
        }

        AttendanceCode::create(['code' => $this->getCode(), 'status' => 'not scanned']);

        return $this->info('Sukses membuat qr code baru');
    }
}
