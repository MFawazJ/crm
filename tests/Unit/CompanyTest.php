<?php

namespace Tests\Unit;



use App\Models\Company;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{


    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    public function test_companies_view(){

        $response = $this->get('/companies');

        $response->assertStatus(200);
        $response->assertViewIs('companies.index');
        $response->assertViewHas('companies');
    }

    public function  test_create_view(){
        $response = $this->get('/companies/create');

        $response->assertStatus(200);
        $response->assertViewIs('companies.create');
    }

    public function test_create_view_has_elements()
    {
        $response = $this->get('/companies/create');

        $response->assertSee('name');
        $response->assertSee('logo');
        $response->assertSee('email');
        $response->assertSee('website');

    }

    public function test_form_validation()
    {
        $data = [
            'name' => '',
            'email' => 'abc',
            'website' => 'abc',
            'logo' => UploadedFile::fake()->image('logo.jpg', 99, 99),
        ];

        $response = $this->post('/companies', $data);

        $response->assertSessionHasErrors('name');
        $response->assertSessionHasErrors('email');
        $response->assertSessionHasErrors('website');
        $response->assertSessionHasErrors('logo');

    }

    public function test_form_validation_does_not_have_error()
    {
        $data = [
            'name' => 'Test',
            'email' => 'abc@abc.com',
            'website' => 'http://www.test.com',
            'logo' => UploadedFile::fake()->image('logo.jpg', 100, 100),
        ];

        $response = $this->post('/companies', $data);

        $response->assertSessionDoesntHaveErrors('name');
        $response->assertSessionDoesntHaveErrors('email');
        $response->assertSessionDoesntHaveErrors('website');
        $response->assertSessionDoesntHaveErrors('logo');

    }

    public function test_create_companies()
    {

        Storage::fake('public');

        $logoFile = UploadedFile::fake()->image('logo.jpg', 200, 200);

        $data = [
            'name' => 'Acme Inc.',
            'email' => 'info@acme.com',
            'logo' => $logoFile,
            'website' => 'http://www.acme.com',
        ];

        $response = $this->post('/companies', $data);

        $response->assertStatus(302);
        $response->assertRedirect();

        $company = Company::latest()->first();
        $this->assertEquals($data['name'], $company->name);
        $this->assertEquals($data['email'], $company->email);
        $this->assertNotNull($company->logo);
        $this->assertEquals($data['website'], $company->website);
    }

    public function test_logo_is_saved()
    {
        Storage::fake('public');

        $logoFile = UploadedFile::fake()->image('naelogo.jpg', 200, 200);

        $data = [
            'name' => 'Acmes  ew Inc.',
            'logo' => $logoFile,
            'email' => 'infoSa@acme.com',
            'website' => 'http://www.aaacme.com',
        ];

        $response = $this->post('/companies', $data);

        $response->assertStatus(302);
        $response->assertRedirect();

        $company = Company::latest()->first();
        $this->assertEquals($data['name'], $company->name);
        $this->assertNotNull($company->logo);

        $storedLogoUrl = Storage::url($company->logo);
        $this->assertStringContainsString('public/logos', $storedLogoUrl);

        $this->assertTrue(Storage::disk('public')->exists($company->logo));

    }

    public function test_company_edit()
    {
        Storage::fake('public');


        $company = new Company;

        $company->name = 'Acme Inc New';
        $company->email = '';
        $company->website = '';
        $company->logo = '';

        $company->save();

        $company = Company::latest()->first();

        // Define the new data for the company
        $newData = [
            'name' => 'New Company Name',
            'email' => 'new-email@example.com',
            'logo' => UploadedFile::fake()->image('new-logo.jpg', 200, 200),
            'website' => 'https://www.new-website.com',
        ];

        // Send a PUT request to update the company
        $response = $this->put(route('companies.update', $company->id), $newData);


        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => $newData['name'],
            'email' => $newData['email'],
            'website' => $newData['website'],
        ]);

        $this->assertNotNull($company->fresh()->logo);
        Storage::assertExists($company->fresh()->logo);
        $this->assertStringContainsString('new-logo', $company->fresh()->logo);

    }



}
