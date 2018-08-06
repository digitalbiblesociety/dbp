<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryGovernment
 *
 * @OA\Schema (
 *     type="object",
 *     description="CountryGovernment",
 *     title="CountryGovernment",
 *     @OA\Xml(name="CountryGovernment")
 * )
 *
 * @mixin \Eloquent
 */
class CountryGovernment extends Model
{
	protected $connection = 'dbp';
	public $incrementing = false;
	public $table = "country_government";

 /**
  *
  * @OA\Property(ref="#/components/schemas/Country/properties/id")
  * @method static CountryGovernment whereCountryId($value)
  * @property string $country_id
  */
 protected $country_id;
 /**
  *
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
 * @OA\Property(
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
