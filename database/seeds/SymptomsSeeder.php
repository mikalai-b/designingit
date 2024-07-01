<?php

use Illuminate\Database\Seeder;

class SymptomsSeeder extends Seeder
{
    static protected $symptoms = [
        'L65.9'=>'Inadequate Lashes',
        'L98.8'=>'Fine Rhytides on Face'
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Symptoms $symptoms)
    {
        foreach (static::$symptoms as $id => $content) {
            if (!$symptoms->find($id)) {
                $symptom = $symptoms->create();

                $symptom->setId($id);
                $symptom->setContent($content);

                $symptoms->store($symptom, TRUE);
            }
        }
    }
}
