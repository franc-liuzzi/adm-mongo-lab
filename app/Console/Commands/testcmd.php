<?php

namespace App\Console\Commands;

use App\Models\Student;
use Illuminate\Console\Command;

class testcmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected function buildProjection($subject) {
        return [
            $subject => [
                '$avg' => [
                    '$map' => [
                        'input' => [
                            '$filter' => [
                                'input' => "\$voti",
                                'as' => 'voto',
                                'cond' => [
                                    '$eq' => ["\$\$voto.materia", $subject]
                                ],
                            ],
                        ],
                        'as' => "voto",
                        'in' => "\$\$voto.voto",
                    ]
                ]
            ],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $v = Student::project([
            ...($this->buildProjection('italiano')),
            ...($this->buildProjection('inglese')),
            ...($this->buildProjection('storia')),
            ...($this->buildProjection('geografia')),
        ])->get();
        // dd($v);
        $this->table(
            array_keys($v[0]->toArray()),
            $v->toArray()
        );
        return Command::SUCCESS;
    }
}
