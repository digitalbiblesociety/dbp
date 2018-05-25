<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryPeople
 *
 * @mixin \Eloquent
 *
 * @OAS\Schema (
 *     type="object",
 *     description="CountryPeople",
 *     title="CountryPeople",
 *     @OAS\Xml(name="CountryPeople")
 * )
 *
 */
class CountryPeople extends Model {
	public $incrementing = false;
	public $table = "country_people";


	/**
	 *
	 * @OAS\Property(ref="#/components/schemas/Country/properties/id")
	 * @method static CountryPeople whereCountryId( $value )
	 * @property string $country_id
	 */
	protected $country_id;

	/**
	 *
	 * @OAS\Property(
	 *     title="languages",
	 *     description="languages",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereLanguages( $value )
	 * @property $languages
	 */
	protected $languages;

	/**
	 *
	 * @OAS\Property(
	 *     title="religions",
	 *     description="religions",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereReligions( $value )
	 * @property $religions
	 */
	protected $religions;

	/**
	 *
	 * @OAS\Property(
	 *     title="population",
	 *     description="population",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople wherePopulation( $value )
	 * @property $population
	 */
	protected $population;

	/**
	 *
	 * @OAS\Property(
	 *     title="population_date",
	 *     description="population_date",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople wherePopulationDate( $value )
	 * @property $population_date
	 */
	protected $population_date;

	/**
	 *
	 * @OAS\Property(
	 *     title="nationality_noun",
	 *     description="nationality_noun",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereNationalityNoun( $value )
	 * @property $nationality_noun
	 */
	protected $nationality_noun;

	/**
	 *
	 * @OAS\Property(
	 *     title="nationality_adjective",
	 *     description="nationality_adjective",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereNationalityAdjective( $value )
	 * @property $nationality_adjective
	 */
	protected $nationality_adjective;

	/**
	 *
	 * @OAS\Property(
	 *     title="age_structure_14",
	 *     description="age_structure_14",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereAgeStructure14( $value )
	 * @property $age_structure_14
	 */
	protected $age_structure_14;

	/**
	 *
	 * @OAS\Property(
	 *     title="age_structure_24",
	 *     description="age_structure_24",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereAgeStructure24( $value )
	 * @property $age_structure_24
	 */
	protected $age_structure_24;

	/**
	 *
	 * @OAS\Property(
	 *     title="age_structure_54",
	 *     description="age_structure_54",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereAgeStructure54( $value )
	 * @property $age_structure_54
	 */
	protected $age_structure_54;

	/**
	 *
	 * @OAS\Property(
	 *     title="age_structure_64",
	 *     description="age_structure_64",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereAgeStructure64( $value )
	 * @property $age_structure_64
	 */
	protected $age_structure_64;

	/**
	 *
	 * @OAS\Property(
	 *     title="age_structure_65",
	 *     description="age_structure_65",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereAgeStructure65( $value )
	 * @property $age_structure_65
	 */
	protected $age_structure_65;

	/**
	 *
	 * @OAS\Property(
	 *     title="dependency_total",
	 *     description="dependency_total",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereDependencyTotal( $value )
	 * @property $dependency_total
	 */
	protected $dependency_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="dependency_youth",
	 *     description="dependency_youth",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereDependencyYouth( $value )
	 * @property $dependency_youth
	 */
	protected $dependency_youth;

	/**
	 *
	 * @OAS\Property(
	 *     title="dependency_elder",
	 *     description="dependency_elder",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereDependencyElder( $value )
	 * @property $dependency_elder
	 */
	protected $dependency_elder;

	/**
	 *
	 * @OAS\Property(
	 *     title="dependency_potential",
	 *     description="dependency_potential",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereDependencyPotential( $value )
	 * @property $dependency_potential
	 */
	protected $dependency_potential;

	/**
	 *
	 * @OAS\Property(
	 *     title="median_age_total",
	 *     description="median_age_total",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereMedianAgeTotal( $value )
	 * @property $median_age_total
	 */
	protected $median_age_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="median_age_male",
	 *     description="median_age_male",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereMedianAgeMale( $value )
	 * @property $median_age_male
	 */
	protected $median_age_male;

	/**
	 *
	 * @OAS\Property(
	 *     title="median_age_female",
	 *     description="median_age_female",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereMedianAgeFemale( $value )
	 * @property $median_age_female
	 */
	protected $median_age_female;

	/**
	 *
	 * @OAS\Property(
	 *     title="population_growth_rate_percentage",
	 *     description="population_growth_rate_percentage",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople wherePopulationGrowthRatePercentage( $value )
	 * @property $population_growth_rate_percentage
	 */
	protected $population_growth_rate_percentage;

	/**
	 *
	 * @OAS\Property(
	 *     title="birth_rate_per_1k",
	 *     description="birth_rate_per_1k",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereBirthRatePer1k( $value )
	 * @property $birth_rate_per_1k
	 */
	protected $birth_rate_per_1k;

	/**
	 *
	 * @OAS\Property(
	 *     title="death_rate_per_1k",
	 *     description="death_rate_per_1k",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereDeathRatePer1k( $value )
	 * @property $death_rate_per_1k
	 */
	protected $death_rate_per_1k;

	/**
	 *
	 * @OAS\Property(
	 *     title="net_migration_per_1k",
	 *     description="net_migration_per_1k",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereNetMigrationPer1k( $value )
	 * @property $net_migration_per_1k
	 */
	protected $net_migration_per_1k;

	/**
	 *
	 * @OAS\Property(
	 *     title="population_distribution",
	 *     description="population_distribution",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople wherePopulationDistribution( $value )
	 * @property $population_distribution
	 */
	protected $population_distribution;

	/**
	 *
	 * @OAS\Property(
	 *     title="urban_population_percentage",
	 *     description="urban_population_percentage",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereUrbanPopulationPercentage( $value )
	 * @property $urban_population_percentage
	 */
	protected $urban_population_percentage;

	/**
	 *
	 * @OAS\Property(
	 *     title="urbanization_rate",
	 *     description="urbanization_rate",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereUrbanizationRate( $value )
	 * @property $urbanization_rate
	 */
	protected $urbanization_rate;

	/**
	 *
	 * @OAS\Property(
	 *     title="major_urban_areas_population",
	 *     description="major_urban_areas_population",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereMajorUrbanAreasPopulation( $value )
	 * @property $major_urban_areas_population
	 */
	protected $major_urban_areas_population;

	/**
	 *
	 * @OAS\Property(
	 *     title="sex_ratio_birth",
	 *     description="sex_ratio_birth",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSexRatioBirth( $value )
	 * @property $sex_ratio_birth
	 */
	protected $sex_ratio_birth;

	/**
	 *
	 * @OAS\Property(
	 *     title="sex_ratio_14",
	 *     description="sex_ratio_14",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSexRatio14( $value )
	 * @property $sex_ratio_14
	 */
	protected $sex_ratio_14;

	/**
	 *
	 * @OAS\Property(
	 *     title="sex_ratio_24",
	 *     description="sex_ratio_24",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSexRatio24( $value )
	 * @property $sex_ratio_24
	 */
	protected $sex_ratio_24;

	/**
	 *
	 * @OAS\Property(
	 *     title="sex_ratio_54",
	 *     description="sex_ratio_54",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSexRatio54( $value )
	 * @property $sex_ratio_54
	 */
	protected $sex_ratio_54;

	/**
	 *
	 * @OAS\Property(
	 *     title="sex_ratio_64",
	 *     description="sex_ratio_64",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSexRatio64( $value )
	 * @property $sex_ratio_64
	 */
	protected $sex_ratio_64;

	/**
	 *
	 * @OAS\Property(
	 *     title="sex_ratio_65",
	 *     description="sex_ratio_65",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSexRatio65( $value )
	 * @property $sex_ratio_65
	 */
	protected $sex_ratio_65;

	/**
	 *
	 * @OAS\Property(
	 *     title="sex_ratio_total",
	 *     description="sex_ratio_total",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSexRatioTotal( $value )
	 * @property $sex_ratio_total
	 */
	protected $sex_ratio_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="mother_age_first_birth",
	 *     description="mother_age_first_birth",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereMotherAgeFirstBirth( $value )
	 * @property $mother_age_first_birth
	 */
	protected $mother_age_first_birth;

	/**
	 *
	 * @OAS\Property(
	 *     title="maternal_mortality_rate",
	 *     description="maternal_mortality_rate",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereMaternalMortalityRate( $value )
	 * @property $maternal_mortality_rate
	 */
	protected $maternal_mortality_rate;

	/**
	 *
	 * @OAS\Property(
	 *     title="infant_mortality_per_1k_total",
	 *     description="infant_mortality_per_1k_total",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereInfantMortalityPer1kTotal( $value )
	 * @property $infant_mortality_per_1k_total
	 */
	protected $infant_mortality_per_1k_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="infant_mortality_per_1k_male",
	 *     description="infant_mortality_per_1k_male",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereInfantMortalityPer1kMale( $value )
	 * @property $infant_mortality_per_1k_male
	 */
	protected $infant_mortality_per_1k_male;

	/**
	 *
	 * @OAS\Property(
	 *     title="infant_mortality_per_1k_female",
	 *     description="infant_mortality_per_1k_female",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereInfantMortalityPer1kFemale( $value )
	 * @property $infant_mortality_per_1k_female
	 */
	protected $infant_mortality_per_1k_female;

	/**
	 *
	 * @OAS\Property(
	 *     title="life_expectancy_at_birth_total",
	 *     description="life_expectancy_at_birth_total",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereLifeExpectancyAtBirthTotal( $value )
	 * @property $life_expectancy_at_birth_total
	 */
	protected $life_expectancy_at_birth_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="life_expectancy_at_birth_male",
	 *     description="life_expectancy_at_birth_male",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereLifeExpectancyAtBirthMale( $value )
	 * @property $life_expectancy_at_birth_male
	 */
	protected $life_expectancy_at_birth_male;

	/**
	 *
	 * @OAS\Property(
	 *     title="life_expectancy_at_birth_female",
	 *     description="life_expectancy_at_birth_female",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereLifeExpectancyAtBirthFemale( $value )
	 * @property $life_expectancy_at_birth_female
	 */
	protected $life_expectancy_at_birth_female;

	/**
	 *
	 * @OAS\Property(
	 *     title="total_fertility_rate",
	 *     description="total_fertility_rate",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereTotalFertilityRate( $value )
	 * @property $total_fertility_rate
	 */
	protected $total_fertility_rate;

	/**
	 *
	 * @OAS\Property(
	 *     title="contraceptive_prevalence",
	 *     description="contraceptive_prevalence",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereContraceptivePrevalence( $value )
	 * @property $contraceptive_prevalence
	 */
	protected $contraceptive_prevalence;

	/**
	 *
	 * @OAS\Property(
	 *     title="health_expenditures",
	 *     description="health_expenditures",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereHealthExpenditures( $value )
	 * @property $health_expenditures
	 */
	protected $health_expenditures;

	/**
	 *
	 * @OAS\Property(
	 *     title="physicians",
	 *     description="physicians",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople wherePhysicians( $value )
	 * @property $physicians
	 */
	protected $physicians;

	/**
	 *
	 * @OAS\Property(
	 *     title="hospital_beds",
	 *     description="hospital_beds",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereHospitalBeds( $value )
	 * @property $hospital_beds
	 */
	protected $hospital_beds;

	/**
	 *
	 * @OAS\Property(
	 *     title="drinking_water_source_urban_improved",
	 *     description="drinking_water_source_urban_improved",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereDrinkingWaterSourceUrbanImproved( $value )
	 * @property $drinking_water_source_urban_improved
	 */
	protected $drinking_water_source_urban_improved;

	/**
	 *
	 * @OAS\Property(
	 *     title="drinking_water_source_rural_improved",
	 *     description="drinking_water_source_rural_improved",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereDrinkingWaterSourceRuralImproved( $value )
	 * @property $drinking_water_source_rural_improved
	 */
	protected $drinking_water_source_rural_improved;

	/**
	 *
	 * @method static CountryPeople
	 * @OAS\Property(
	 *     title="sanitation_facility_access_urban_improved",
	 *     description="sanitation_facility_access_urban_improved",
	 *     type="string"
	 * )
	 * whereSanitationFacilityAccessUrbanImproved($value)
	 * @property $sanitation_facility_access_urban_improved
	 */
	protected $sanitation_facility_access_urban_improved;

	/**
	 *
	 * @method static CountryPeople
	 * @OAS\Property(
	 *     title="sanitation_facility_access_rural_improved",
	 *     description="",
	 *     type="string"
	 * )
	 * whereSanitationFacilityAccessRuralImproved($value)
	 * @property $sanitation_facility_access_rural_improved
	 */
	protected $sanitation_facility_access_rural_improved;

	/**
	 *
	 * @OAS\Property(
	 *     title="hiv_infection_rate",
	 *     description="hiv_infection_rate",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereHivInfectionRate( $value )
	 * @property $hiv_infection_rate
	 */
	protected $hiv_infection_rate;

	/**
	 *
	 * @OAS\Property(
	 *     title="hiv_infected",
	 *     description="hiv_infected",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereHivInfected( $value )
	 * @property $hiv_infected
	 */
	protected $hiv_infected;

	/**
	 *
	 * @OAS\Property(
	 *     title="hiv_deaths",
	 *     description="hiv_deaths",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereHivDeaths( $value )
	 * @property $hiv_deaths
	 */
	protected $hiv_deaths;

	/**
	 *
	 * @OAS\Property(
	 *     title="obesity_rate",
	 *     description="obesity_rate",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereObesityRate( $value )
	 * @property $obesity_rate
	 */
	protected $obesity_rate;

	/**
	 *
	 * @OAS\Property(
	 *     title="underweight_children",
	 *     description="underweight_children",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereUnderweightChildren( $value )
	 * @property $underweight_children
	 */
	protected $underweight_children;

	/**
	 *
	 * @OAS\Property(
	 *     title="education_expenditures",
	 *     description="education_expenditures",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereEducationExpenditures( $value )
	 * @property $education_expenditures
	 */
	protected $education_expenditures;

	/**
	 *
	 * @OAS\Property(
	 *     title="literacy_definition",
	 *     description="literacy_definition",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereLiteracyDefinition( $value )
	 * @property $literacy_definition
	 */
	protected $literacy_definition;

	/**
	 *
	 * @OAS\Property(
	 *     title="literacy_total",
	 *     description="literacy_total",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereLiteracyTotal( $value )
	 * @property $literacy_total
	 */
	protected $literacy_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="literacy_male",
	 *     description="literacy_male",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereLiteracyMale( $value )
	 * @property $literacy_male
	 */
	protected $literacy_male;

	/**
	 *
	 * @OAS\Property(
	 *     title="literacy_female",
	 *     description="literacy_female",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereLiteracyFemale( $value )
	 * @property $literacy_female
	 */
	protected $literacy_female;

	/**
	 *
	 * @OAS\Property(
	 *     title="school_years_total",
	 *     description="school_years_total",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSchoolYearsTotal( $value )
	 * @property $school_years_total
	 */
	protected $school_years_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="school_years_male",
	 *     description="school_years_male",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSchoolYearsMale( $value )
	 * @property $school_years_male
	 */
	protected $school_years_male;

	/**
	 *
	 * @OAS\Property(
	 *     title="school_years_female",
	 *     description="school_years_female",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereSchoolYearsFemale( $value )
	 * @property $school_years_female
	 */
	protected $school_years_female;

	/**
	 *
	 * @OAS\Property(
	 *     title="child_labor",
	 *     description="child_labor",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereChildLabor( $value )
	 * @property $child_labor
	 */
	protected $child_labor;

	/**
	 *
	 * @OAS\Property(
	 *     title="child_labor_percentage",
	 *     description="child_labor_percentage",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereChildLaborPercentage( $value )
	 * @property $child_labor_percentage
	 */
	protected $child_labor_percentage;

	/**
	 *
	 * @OAS\Property(
	 *     title="unemployment_youth_total",
	 *     description="unemployment_youth_total",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereUnemploymentYouthTotal( $value )
	 * @property $unemployment_youth_total
	 */
	protected $unemployment_youth_total;

	/**
	 *
	 * @OAS\Property(
	 *     title="unemployment_youth_male",
	 *     description="unemployment_youth_male",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereUnemploymentYouthMale( $value )
	 * @property $unemployment_youth_male
	 */
	protected $unemployment_youth_male;

	/**
	 *
	 * @OAS\Property(
	 *     title="unemployment_youth_female",
	 *     description="unemployment_youth_female",
	 *     type="string"
	 * )
	 *
	 * @method static CountryPeople whereUnemploymentYouthFemale( $value )
	 * @property $unemployment_youth_female
	 */
	protected $unemployment_youth_female;

}
