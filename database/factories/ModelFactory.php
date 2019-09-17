<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Models\Admin;
use App\Models\DevtPartner;
use App\Models\Farmer;
use App\Models\InputOrder;
use App\Models\InputSupplier;
use App\Models\MapCoordinate;
use App\Models\MasterAgent;
use App\Models\OffTaker;
use App\Models\Planting;
use App\Models\RequestPassword;
use App\Models\SoilTest;
use App\Models\VillageAgent;
use App\Models\CustomIncome;
use App\Models\MilkLedger;
use App\Models\Spraying;
use App\Models\Income;
use App\Models\CustomExpense;
use App\Models\Diagnosis;
use App\Models\Account;
use App\Models\OurCrop;
use App\Models\CropInfo;
use App\Models\Insurance;
use App\Models\Expense;

use database\factories\AdminFactory;
use database\factories\PartnerFactory;
use database\factories\FarmerFactory;
use database\factories\InputOrderFactory;
use database\factories\InputSupplierFactory;
use database\factories\MapCoordinateFactory;
use database\factories\MasterAgentFactory;
use database\factories\OffTakerFactory;
use database\factories\PlantingFactory;
use database\factories\RequestPasswordFactory;
use database\factories\SoilTestFactory;
use database\factories\VillageAgentFactory;
use database\factories\CustomIncomeFactory;
use database\factories\MilkLedgerFactory;
use database\factories\SprayingFactory;
use database\factories\IncomeFactory;
use database\factories\CustomExpenseFactory;
use database\factories\DiagnosisFactory;
use database\factories\AccountFactory;
use database\factories\OurCropFactory;
use database\factories\CropInfoFactory;
use database\factories\InsuranceFactory;
use database\factories\ExpenseFactory;

$factory->define(Admin::class, function (Faker\Generator $faker) {
    return AdminFactory::getFactory($faker);
});

$factory->define(DevtPartner::class, function (Faker\Generator $faker) {
    return PartnerFactory::getFactory($faker);
});

$factory->define(Farmer::class, function (Faker\Generator $faker) {
    return FarmerFactory::getFactory($faker);
});

$factory->define(InputOrder::class, function (Faker\Generator $faker) {
    return InputOrderFactory::getFactory($faker);
});

$factory->define(InputSupplier::class, function (Faker\Generator $faker) {
    return InputSupplierFactory::getFactory($faker);
});

$factory->define(MapCoordinate::class, function (Faker\Generator $faker) {
    return MapCoordinateFactory::getFactory($faker);
});

$factory->define(MasterAgent::class, function (Faker\Generator $faker) {
    return MasterAgentFactory::getFactory($faker);
});

$factory->define(OffTaker::class, function (Faker\Generator $faker) {
    return OffTakerFactory::getFactory($faker);
});

$factory->define(Planting::class, function (Faker\Generator $faker) {
    return PlantingFactory::getFactory($faker);
});

$factory->define(RequestPassword::class, function (Faker\Generator $faker) {
    return RequestPasswordFactory::getFactory($faker);
});

$factory->define(SoilTest::class, function (Faker\Generator $faker) {
    return SoilTestFactory::getFactory($faker);
});

$factory->define(VillageAgent::class, function (Faker\Generator $faker) {
    return VillageAgentFactory::getFactory($faker);
});

$factory->define(CustomIncome::class, function (Faker\Generator $faker) {
    return CustomIncomeFactory::getFactory($faker);
});

$factory->define(MilkLedger::class, function (Faker\Generator $faker) {
    return MilkLedgerFactory::getFactory($faker);
});

$factory->define(Spraying::class, function (Faker\Generator $faker) {
    return SprayingFactory::getFactory($faker);
});

$factory->define(Income::class, function (Faker\Generator $faker) {
    return IncomeFactory::getFactory($faker);
});

$factory->define(CustomExpense::class, function (Faker\Generator $faker) {
    return CustomExpenseFactory::getFactory($faker);
});

$factory->define(Diagnosis::class, function (Faker\Generator $faker) {
    return DiagnosisFactory::getFactory($faker);
});

$factory->define(Account::class, function (Faker\Generator $faker) {
    return AccountFactory::getFactory($faker);
});

$factory->define(OurCrop::class, function (Faker\Generator $faker) {
    return OurCropFactory::getFactory($faker);
});

$factory->define(CropInfo::class, function (Faker\Generator $faker) {
    return CropInfoFactory::getFactory($faker);
});

$factory->define(Insurance::class, function (Faker\Generator $faker) {
    return InsuranceFactory::getFactory($faker);
});

$factory->define(Expense::class, function (Faker\Generator $faker) {
    return ExpenseFactory::getFactory($faker);
});
