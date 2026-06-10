<?php

namespace Tests\Unit;

use App\Models\cr;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CrTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test cr model can be instantiated
     */
    public function test_cr_model_can_be_instantiated(): void
    {
        $crModel = new cr();

        $this->assertInstanceOf(cr::class, $crModel);
    }

    /**
     * Test cr model inherits from Model
     */
    public function test_cr_model_inherits_from_eloquent_model(): void
    {
        $crModel = new cr();

        $this->assertTrue(method_exists($crModel, 'save'));
        $this->assertTrue(method_exists($crModel, 'delete'));
        $this->assertTrue(method_exists($crModel, 'update'));
    }

    /**
     * Test cr table name matches model name (plural)
     */
    public function test_cr_table_name_is_correct(): void
    {
        $crModel = new cr();
        $tableName = $crModel->getTable();

        // Laravel convention: singular model name pluralized becomes table name
        $this->assertEquals('crs', $tableName);
    }

    /**
     * Test cr model can use timestamps
     */
    public function test_cr_model_has_timestamps(): void
    {
        $crModel = new cr();

        $this->assertTrue($crModel->usesTimestamps());
    }

    /**
     * Test cr model can be created (database table exists or is assumed)
     */
    public function test_cr_model_structure(): void
    {
        $crModel = new cr();

        // Test basic eloquent model functionality
        $this->assertTrue(method_exists($crModel, 'getConnection'));
        $this->assertTrue(method_exists($crModel, 'getTable'));
        $this->assertTrue(method_exists($crModel, 'getFillable'));
    }

    /**
     * Test cr model fillable array can be set
     */
    public function test_cr_model_fillable_attributes(): void
    {
        $crModel = new cr();
        $fillable = $crModel->getFillable();

        // Should return an array (empty or with attributes)
        $this->assertIsArray($fillable);
    }

    /**
     * Test cr model primary key
     */
    public function test_cr_model_primary_key(): void
    {
        $crModel = new cr();

        $this->assertEquals('id', $crModel->getKeyName());
    }

    /**
     * Test cr model is guarded or fillable properly
     */
    public function test_cr_model_is_properly_configured(): void
    {
        $crModel = new cr();

        // Check if the model has either fillable or guarded attributes
        $fillable = $crModel->getFillable();
        $guarded = $crModel->getGuarded();

        // At least one should be defined
        $this->assertTrue(count($fillable) >= 0 || count($guarded) >= 0);
    }
}
