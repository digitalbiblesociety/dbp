<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryPeople
 *
 * @property string $country_id
 * @property string $languages
 * @property string $religions
 * @property int|null $population
 * @property int|null $population_date
 * @property string|null $nationality_noun
 * @property string|null $nationality_adjective
 * @property float|null $age_structure_14
 * @property float|null $age_structure_24
 * @property float|null $age_structure_54
 * @property float|null $age_structure_64
 * @property float|null $age_structure_65
 * @property float|null $dependency_total
 * @property float|null $dependency_youth
 * @property float|null $dependency_elder
 * @property float|null $dependency_potential
 * @property float|null $median_age_total
 * @property float|null $median_age_male
 * @property float|null $median_age_female
 * @property float|null $population_growth_rate_percentage
 * @property float|null $birth_rate_per_1k
 * @property float|null $death_rate_per_1k
 * @property float|null $net_migration_per_1k
 * @property string|null $population_distribution
 * @property float|null $urban_population_percentage
 * @property float|null $urbanization_rate
 * @property string|null $major_urban_areas_population
 * @property float|null $sex_ratio_birth
 * @property float|null $sex_ratio_14
 * @property float|null $sex_ratio_24
 * @property float|null $sex_ratio_54
 * @property float|null $sex_ratio_64
 * @property float|null $sex_ratio_65
 * @property float|null $sex_ratio_total
 * @property int|null $mother_age_first_birth
 * @property float|null $maternal_mortality_rate
 * @property float|null $infant_mortality_per_1k_total
 * @property float|null $infant_mortality_per_1k_male
 * @property float|null $infant_mortality_per_1k_female
 * @property float|null $life_expectancy_at_birth_total
 * @property float|null $life_expectancy_at_birth_male
 * @property float|null $life_expectancy_at_birth_female
 * @property float|null $total_fertility_rate
 * @property float|null $contraceptive_prevalence
 * @property float|null $health_expenditures
 * @property float|null $physicians
 * @property float|null $hospital_beds
 * @property float|null $drinking_water_source_urban_improved
 * @property float|null $drinking_water_source_rural_improved
 * @property float|null $sanitation_facility_access_urban_improved
 * @property float|null $sanitation_facility_access_rural_improved
 * @property float|null $hiv_infection_rate
 * @property float|null $hiv_infected
 * @property float|null $hiv_deaths
 * @property float|null $obesity_rate
 * @property float|null $underweight_children
 * @property string|null $education_expenditures
 * @property string|null $literacy_definition
 * @property float|null $literacy_total
 * @property float|null $literacy_male
 * @property float|null $literacy_female
 * @property int|null $school_years_total
 * @property int|null $school_years_male
 * @property int|null $school_years_female
 * @property int|null $child_labor
 * @property float|null $child_labor_percentage
 * @property float|null $unemployment_youth_total
 * @property float|null $unemployment_youth_male
 * @property float|null $unemployment_youth_female
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereAgeStructure14($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereAgeStructure24($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereAgeStructure54($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereAgeStructure64($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereAgeStructure65($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereBirthRatePer1k($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereChildLabor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereChildLaborPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereContraceptivePrevalence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereDeathRatePer1k($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereDependencyElder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereDependencyPotential($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereDependencyTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereDependencyYouth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereDrinkingWaterSourceRuralImproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereDrinkingWaterSourceUrbanImproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereEducationExpenditures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereHealthExpenditures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereHivDeaths($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereHivInfected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereHivInfectionRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereHospitalBeds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereInfantMortalityPer1kFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereInfantMortalityPer1kMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereInfantMortalityPer1kTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereLanguages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereLifeExpectancyAtBirthFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereLifeExpectancyAtBirthMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereLifeExpectancyAtBirthTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereLiteracyDefinition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereLiteracyFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereLiteracyMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereLiteracyTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereMajorUrbanAreasPopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereMaternalMortalityRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereMedianAgeFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereMedianAgeMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereMedianAgeTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereMotherAgeFirstBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereNationalityAdjective($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereNationalityNoun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereNetMigrationPer1k($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereObesityRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople wherePhysicians($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople wherePopulation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople wherePopulationDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople wherePopulationDistribution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople wherePopulationGrowthRatePercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereReligions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSanitationFacilityAccessRuralImproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSanitationFacilityAccessUrbanImproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSchoolYearsFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSchoolYearsMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSchoolYearsTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSexRatio14($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSexRatio24($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSexRatio54($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSexRatio64($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSexRatio65($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSexRatioBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereSexRatioTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereTotalFertilityRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereUnderweightChildren($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereUnemploymentYouthFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereUnemploymentYouthMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereUnemploymentYouthTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereUrbanPopulationPercentage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryPeople whereUrbanizationRate($value)
 * @mixin \Eloquent
 */
class CountryPeople extends Model
{
	public $incrementing = false;
	public $table = "country_people";

}
