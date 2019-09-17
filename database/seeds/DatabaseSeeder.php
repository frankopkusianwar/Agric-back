<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('AdminDocumentSeeder');
        $this->call('PartnerDocumentSeeder');
        $this->call('InputSupplierDocumentSeeder');
        $this->call('InputOrderDocumentSeeder');
        $this->call('MapCoordinateDocumentSeeder');
        $this->call('MasterAgentDocumentSeeder');
        $this->call('OffTakerDocumentSeeder');
        $this->call('RequestPasswordDocumentSeeder');
        $this->call('VillageAgentDocumentSeeder');
        $this->call('FarmerDocumentSeeder');
        $this->call('CustomIncomeDocumentSeeder');
        $this->call('MilkLedgerDocumentSeeder');
        $this->call('IncomeDocumentSeeder');
        $this->call('CustomExpenseDocumentSeeder');
        $this->call('DiagnosisDocumentSeeder');
        $this->call('AccountDocumentSeeder');
        $this->call('OurCropDocumentSeeder');
        $this->call('CropInfoDocumentSeeder');
        $this->call('InsuranceDocumentSeeder');
        $this->call('ExpenseDocumentSeeder');
        $this->call('DistrictDocumentSeeder');
        $this->call('InputCategoryDocumentSeeder');
    }
}
