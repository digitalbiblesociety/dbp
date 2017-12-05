<?php

namespace App\Models\Country\FactBook;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Country\FactBook\CountryGovernment
 *
 * @property string $country_id
 * @property string $name
 * @property string $name_etymology
 * @property string $conventional_long_form
 * @property string $conventional_short_form
 * @property string $dependency_status
 * @property string $government_type
 * @property string $capital
 * @property string $capital_coordinates
 * @property string $capital_time_zone
 * @property string $administrative_divisions
 * @property string $administrative_divisions_note
 * @property string $independence
 * @property string $national_holiday
 * @property string $constitution
 * @property string $legal_system
 * @property string $citizenship
 * @property string $suffrage
 * @property string $executive_chief_of_state
 * @property string $executive_head_of_government
 * @property string $executive_cabinet
 * @property string $executive_elections
 * @property string $executive_election_results
 * @property string $legislative_description
 * @property string $legislative_elections
 * @property string $legislative_election_results
 * @property string $legislative_highest_courts
 * @property string $legislative_judge_selection
 * @property string $legislative_subordinate_courts
 * @property string $political_parties
 * @property string $political_pressure
 * @property string $international_organization_participation
 * @property string $diplomatic_representation_in_usa
 * @property string $diplomatic_representation_from_usa
 * @property string $flag_description
 * @property string $national_symbols
 * @property string $national_anthem
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereAdministrativeDivisions($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereAdministrativeDivisionsNote($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereCapital($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereCapitalCoordinates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereCapitalTimeZone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereCitizenship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereConstitution($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereConventionalLongForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereConventionalShortForm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereDependencyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereDiplomaticRepresentationFromUsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereDiplomaticRepresentationInUsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereExecutiveCabinet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereExecutiveChiefOfState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereExecutiveElectionResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereExecutiveElections($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereExecutiveHeadOfGovernment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereFlagDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereGovernmentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereIndependence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereInternationalOrganizationParticipation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereLegalSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereLegislativeDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereLegislativeElectionResults($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereLegislativeElections($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereLegislativeHighestCourts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereLegislativeJudgeSelection($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereLegislativeSubordinateCourts($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereNameEtymology($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereNationalAnthem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereNationalHoliday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereNationalSymbols($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment wherePoliticalParties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment wherePoliticalPressure($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereSuffrage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Country\FactBook\CountryGovernment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CountryGovernment extends Model
{
	public $incrementing = false;
	public $table = "country_government";

}
