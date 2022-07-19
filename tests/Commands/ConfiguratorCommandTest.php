<?php

namespace Sparors\Ussd\Tests\Commands;

use Sparors\Ussd\Tests\TestCase;
use Illuminate\Support\Facades\File;

class ConfiguratorCommandTest extends TestCase
{
    public function test_it_print_out_success_when_class_does_not_exists()
    {
        File::shouldReceive('exists')->once()->andReturn(false);
        File::shouldReceive('isDirectory')->once();
        File::shouldReceive('makeDirectory')->once();
        File::shouldReceive('put')->once()->andReturn(true);
        $this->artisan('ussd:configurator', ['name' => 'africastalking'])
            ->expectsOutput('Africastalking configurator created successfully')
            ->assertExitCode(0);
    }

    public function test_it_generates_a_configurator()
    {
        File::delete(app_path('Http/Ussd/Configurator/Save.php'));

        $this->artisan('ussd:configurator',['name'=>'save'])
            ->assertExitCode(0);

        $this->assertFileExists(app_path('Http/Ussd/Configurator/Save.php'));
    }

    public function test_it_print_out_error_when_class_exists()
    {
        File::shouldReceive('exists')->once()->andReturn(true);
        $this->artisan('ussd:configurator', ['name' => 'Nsano'])
            ->expectsOutput('File already exists !')
            ->assertExitCode(0);
    }
}
