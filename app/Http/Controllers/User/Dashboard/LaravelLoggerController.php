<?php

namespace App\Http\Controllers\User\Dashboard;

use App\Http\Controllers\APIController;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use App\Traits\IpAddressDetails;
use App\Traits\UserAgentDetails;
use App\Models\User\Activity;
use Illuminate\View\View;

class LaravelLoggerController extends APIController
{
	use AuthorizesRequests, DispatchesJobs, IpAddressDetails, UserAgentDetails, ValidatesRequests;

	private $_rolesEnabled;
	private $_rolesMiddlware;

	/**
	 * Create a new controller instance.
	 *
	 */
	public function __construct()
	{
		parent::__construct();
		$this->middleware('auth');

		$this->_rolesEnabled = config('LaravelLogger.rolesEnabled');
		$this->_rolesMiddlware = config('LaravelLogger.rolesMiddlware');

		if ($this->_rolesEnabled) {
			$this->middleware($this->_rolesMiddlware);
		}
	}

	/**
	 * Add additional details to a collections.
	 *
	 * @param collection $collectionItems
	 *
	 * @return collection
	 */
	private function mapAdditionalDetails($collectionItems)
	{
		$collectionItems->map(function ($collectionItem) {
			$eventTime = Carbon::parse($collectionItem->updated_at);
			$collectionItem['timePassed'] = $eventTime->diffForHumans();
			$collectionItem['userAgentDetails'] = UserAgentDetails::details($collectionItem->useragent);
			$collectionItem['langDetails'] = UserAgentDetails::localeLang($collectionItem->locale);
			$collectionItem['userDetails'] = config('LaravelLogger.defaultUserModel')::find($collectionItem->userId);

			return $collectionItem;
		});

		return $collectionItems;
	}

	/**
	 * Show the activities log dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showAccessLog()
	{
		if (config('LaravelLogger.loggerPaginationEnabled')) {
			$activities = Activity::orderBy('created_at', 'desc')->paginate(config('LaravelLogger.loggerPaginationPerPage'));
			$totalActivities = $activities->total();
		} else {
			$activities = Activity::orderBy('created_at', 'desc')->get();
			$totalActivities = $activities->count();
		}

		$this->mapAdditionalDetails($activities);

		$data = [
			'activities'        => $activities,
			'totalActivities'   => $totalActivities,
		];

		return view('dashboard.logging.logger.activity-log', $data);
	}

	/**
	 * Show an individual activity log entry.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showAccessLogEntry($id)
	{
		$activity = Activity::findOrFail($id);

		$userDetails = config('LaravelLogger.defaultUserModel')::find($activity->userId);
		$userAgentDetails = UserAgentDetails::details($activity->useragent);
		$ipAddressDetails = IpAddressDetails::checkIP($activity->ipAddress);
		$langDetails = UserAgentDetails::localeLang($activity->locale);
		$eventTime = Carbon::parse($activity->created_at);
		$timePassed = $eventTime->diffForHumans();

		if (config('LaravelLogger.loggerPaginationEnabled')) {
			$userActivities = Activity::where('userId', $activity->userId)
			                          ->orderBy('created_at', 'desc')
			                          ->paginate(config('LaravelLogger.loggerPaginationPerPage'));
			$totalUserActivities = $userActivities->total();
		} else {
			$userActivities = Activity::where('userId', $activity->userId)
			                          ->orderBy('created_at', 'desc')
			                          ->get();
			$totalUserActivities = $userActivities->count();
		}

		$this->mapAdditionalDetails($userActivities);

		$data = [
			'activity'              => $activity,
			'userDetails'           => $userDetails,
			'ipAddressDetails'      => $ipAddressDetails,
			'timePassed'            => $timePassed,
			'userAgentDetails'      => $userAgentDetails,
			'langDetails'           => $langDetails,
			'userActivities'        => $userActivities,
			'totalUserActivities'   => $totalUserActivities,
			'isClearedEntry'        => false,
		];

		return view('dashboard.logging.logger.activity-log-item', $data);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function clearActivityLog()
	{
		$activities = Activity::all();
		foreach ($activities as $activity) $activity->delete();
		return redirect('activity')->with('success', trans('LaravelLogger::laravel-logger.messages.logClearedSuccessfuly'));
	}

	/**
	 * Show the cleared activity log - softdeleted records.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function showClearedActivityLog()
	{
		if (config('LaravelLogger.loggerPaginationEnabled')) {
			$activities = Activity::onlyTrashed()
			                      ->orderBy('created_at', 'desc')
			                      ->paginate(config('LaravelLogger.loggerPaginationPerPage'));
			$totalActivities = $activities->total();
		} else {
			$activities = Activity::onlyTrashed()
			                      ->orderBy('created_at', 'desc')
			                      ->get();
			$totalActivities = $activities->count();
		}

		$this->mapAdditionalDetails($activities);

		$data = [
			'activities'        => $activities,
			'totalActivities'   => $totalActivities,
		];

		return view('dashboard.logging.logger.activity-log-cleared', $data);
	}

	/**
	 * Show an individual cleared (soft deleted) activity log entry.
	 *
	 * @param int $id
	 *
	 * @return View
	 */
	public function showClearedAccessLogEntry($id)
	{
		$activity = $this->getClearedActivity($id);
		$data     = [
			'activity'              => $activity,
			'userDetails'           => config('LaravelLogger.defaultUserModel')::find($activity->userId),
			'ipAddressDetails'      => IpAddressDetails::checkIP($activity->ipAddress),
			'timePassed'            => Carbon::parse($activity->created_at)->diffForHumans(),
			'userAgentDetails'      => UserAgentDetails::details($activity->useragent),
			'langDetails'           => UserAgentDetails::localeLang($activity->locale),
			'isClearedEntry'        => true,
		];

		return view('dashboard.logging.logger.activity-log-item', $data);
	}

	/**
	 * Get Cleared (Soft Deleted) Activity - Helper Method.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\Http\Response
	 */
	private function getClearedActivity($id)
	{
		$activity = Activity::onlyTrashed()->where('id', $id)->get();
		if (\count($activity) !== 1) return abort(404);

		return $activity[0];
	}

	/**
	 * Destroy the specified resource from storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroyActivityLog()
	{
		$activities = Activity::onlyTrashed()->get();
		foreach ($activities as $activity) {
			$activity->forceDelete();
		}

		return redirect('activity')->with('success', trans('LaravelLogger::laravel-logger.messages.logDestroyedSuccessfuly'));
	}

	/**
	 * Restore the specified resource from soft deleted storage.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function restoreClearedActivityLog()
	{
		$activities = Activity::onlyTrashed()->get();
		foreach ($activities as $activity) $activity->restore();

		return redirect('activity')->with('success', trans('LaravelLogger::laravel-logger.messages.logRestoredSuccessfuly'));
	}
}
