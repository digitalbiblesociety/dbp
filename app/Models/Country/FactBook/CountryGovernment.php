<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryGovernment
 *
 * @OAS\Schema (
 *     type="object",
 *     description="CountryGovernment",
 *     title="CountryGovernment",
 *     @OAS\Xml(name="CountryGovernment")
 * )
 *
 * @mixin \Eloquent
 */
class CountryGovernment extends Model
{
	public $incrementing = false;
	public $table = "country_government";

 /**
  *
  * @OAS\Property(ref="#/components/schemas/Country/properties/id")
  * @method static CountryGovernment whereCountryId($value)
  * @property string $country_id
  */
 protected $country_id;
 /**
  *
 * @OAS\Property(
 *     title="name",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereName($value)
 * @property string $name
 */
 protected $name;
 /**
  *
 * @OAS\Property(
 *     title="name_etymology",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereNameEtymology($value)
 * @property string $name_etymology
 */
 protected $name_etymology;
 /**
  *
 * @OAS\Property(
 *     title="conventional_long_form",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereConventionalLongForm($value)
 * @property string $conventional_long_form
 */
 protected $conventional_long_form;
 /**
  *
 * @OAS\Property(
 *     title="conventional_short_form",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereConventionalShortForm($value)
 * @property string $conventional_short_form
 */
 protected $conventional_short_form;
 /**
  *
 * @OAS\Property(
 *     title="dependency_status",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereDependencyStatus($value)
 * @property string $dependency_status
 */
 protected $dependency_status;
 /**
  *
 * @OAS\Property(
 *     title="government_type",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereGovernmentType($value)
 * @property string $government_type
 */
 protected $government_type;
 /**
  *
 * @OAS\Property(
 *     title="capital",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereCapital($value)
 * @property string $capital
 */
 protected $capital;
 /**
  *
 * @OAS\Property(
 *     title="capital_coordinates",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereCapitalCoordinates($value)
 * @property string $capital_coordinates
 */
 protected $capital_coordinates;
 /**
  *
 * @OAS\Property(
 *     title="capital_time_zone",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereCapitalTimeZone($value)
 * @property string $capital_time_zone
 */
 protected $capital_time_zone;
 /**
  *
 * @OAS\Property(
 *     title="administrative_divisions",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereAdministrativeDivisions($value)
 * @property string $administrative_divisions
 */
 protected $administrative_divisions;
 /**
  *
 * @OAS\Property(
 *     title="administrative_divisions_note",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereAdministrativeDivisionsNote($value)
 * @property string $administrative_divisions_note
 */
 protected $administrative_divisions_note;
 /**
  *
 * @OAS\Property(
 *     title="independence",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereIndependence($value)
 * @property string $independence
 */
 protected $independence;
 /**
  *
 * @OAS\Property(
 *     title="national_holiday",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereNationalHoliday($value)
 * @property string $national_holiday
 */
 protected $national_holiday;
 /**
  *
 * @OAS\Property(
 *     title="constitution",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereConstitution($value)
 * @property string $constitution
 */
 protected $constitution;
 /**
  *
 * @OAS\Property(
 *     title="legal_system",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereLegalSystem($value)
 * @property string $legal_system
 */
 protected $legal_system;
 /**
  *
 * @OAS\Property(
 *     title="citizenship",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereCitizenship($value)
 * @property string $citizenship
 */
 protected $citizenship;
 /**
  *
 * @OAS\Property(
 *     title="suffrage",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereSuffrage($value)
 * @property string $suffrage
 */
 protected $suffrage;
 /**
  *
 * @OAS\Property(
 *     title="executive_chief_of_state",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereExecutiveChiefOfState($value)
 * @property string $executive_chief_of_state
 */
 protected $executive_chief_of_state;
 /**
  *
 * @OAS\Property(
 *     title="executive_head_of_government",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereExecutiveHeadOfGovernment($value)
 * @property string $executive_head_of_government
 */
 protected $executive_head_of_government;
 /**
  *
 * @OAS\Property(
 *     title="executive_cabinet",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereExecutiveCabinet($value)
 * @property string $executive_cabinet
 */
 protected $executive_cabinet;
 /**
  *
 * @OAS\Property(
 *     title="executive_elections",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereExecutiveElections($value)
 * @property string $executive_elections
 */
 protected $executive_elections;
 /**
  *
 * @OAS\Property(
 *     title="executive_election_results",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereExecutiveElectionResults($value)
 * @property string $executive_election_results
 */
 protected $executive_election_results;
 /**
  *
 * @OAS\Property(
 *     title="legislative_description",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereLegislativeDescription($value)
 * @property string $legislative_description
 */
 protected $legislative_description;
 /**
  *
 * @OAS\Property(
 *     title="legislative_elections",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereLegislativeElections($value)
 * @property string $legislative_elections
 */
 protected $legislative_elections;
 /**
  *
 * @OAS\Property(
 *     title="legislative_election_results",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereLegislativeElectionResults($value)
 * @property string $legislative_election_results
 */
 protected $legislative_election_results;
 /**
  *
 * @OAS\Property(
 *     title="legislative_highest_courts",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereLegislativeHighestCourts($value)
 * @property string $legislative_highest_courts
 */
 protected $legislative_highest_courts;
 /**
  *
 * @OAS\Property(
 *     title="legislative_judge_selection",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereLegislativeJudgeSelection($value)
 * @property string $legislative_judge_selection
 */
 protected $legislative_judge_selection;
 /**
  *
 * @OAS\Property(
 *     title="legislative_subordinate_courts",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereLegislativeSubordinateCourts($value)
 * @property string $legislative_subordinate_courts
 */
 protected $legislative_subordinate_courts;
 /**
  *
 * @OAS\Property(
 *     title="political_parties",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment wherePoliticalParties($value)
 * @property string $political_parties
 */
 protected $political_parties;
 /**
  *
 * @OAS\Property(
 *     title="political_pressure",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment wherePoliticalPressure($value)
 * @property string $political_pressure
 */
 protected $political_pressure;
 /**
  *
 * @OAS\Property(
 *     title="international_organization_participation",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereInternationalOrganizationParticipation($value)
 * @property string $international_organization_participation
 */
 protected $international_organization_participation;
 /**
  *
 * @OAS\Property(
 *     title="diplomatic_representation_in_usa",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereDiplomaticRepresentationInUsa($value)
 * @property string $diplomatic_representation_in_usa
 */
 protected $diplomatic_representation_in_usa;
 /**
  *
 * @OAS\Property(
 *     title="diplomatic_representation_from_usa",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereDiplomaticRepresentationFromUsa($value)
 * @property string $diplomatic_representation_from_usa
 */
 protected $diplomatic_representation_from_usa;
 /**
  *
 * @OAS\Property(
 *     title="flag_description",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereFlagDescription($value)
 * @property string $flag_description
 */
 protected $flag_description;
 /**
  *
 * @OAS\Property(
 *     title="national_symbols",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereNationalSymbols($value)
 * @property string $national_symbols
 */
 protected $national_symbols;
 /**
  *
 * @OAS\Property(
 *     title="national_anthem",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereNationalAnthem($value)
 * @property string $national_anthem
 */
 protected $national_anthem;
 /**
  *
 * @OAS\Property(
 *     title="created_at",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereCreatedAt($value)
 * @property \Carbon\Carbon|null $created_at
 */
 protected $created_at;
 /**
  *
 * @OAS\Property(
 *     title="updated_at",
 *     description="",
 *     type="string"
 * )
 *
 * @method static CountryGovernment whereUpdatedAt($value)
 * @property \Carbon\Carbon|null $updated_at
 */
 protected $updated_at;

}
