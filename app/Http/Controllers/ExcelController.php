<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Models\RequestTable;
use Illuminate\Http\Request;
use App\Models\RequestStatus;
use App\Exports\NewStatusExport;
use App\Exports\ActiveStatusExport;
use App\Exports\UnPaidStatusExport;
use App\Exports\PendingStatusExport;
use App\Exports\ToCloseStatusExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ConcludeStatusExport;

class ExcelController extends Controller
{
    public function exportAll()
    {
        return Excel::download(new UsersExport, 'AllData.xls');
    }

    public function exportNewData(Request $request)
    {

        if (!empty($request->filter_search)) {
            $cases = RequestStatus::where('status', 1)
                ->whereHas('request', function ($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->filter_search . '%');
                    $q->orWhereHas('requestClient', function ($query) use ($request) {
                        $query->where('first_name', 'like', "%$request->filter_search%");
                    });
                });
        } 
        else if (!empty($request->filter_region)) {

            if ($request->filter_region == 1) {
                $regionName = "Somnath";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 1);
            } else if ($request->filter_region == 2) {
                $regionName = "Dwarka";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 1);
            } else if ($request->filter_region == 3) {
                $regionName = "Rajkot";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 1);
            } else if ($request->filter_region == 4) {
                $regionName = "Bhavnagar";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 1);
            } else if ($request->filter_region == 5) {
                $regionName = "Ahmedabad";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 1);
            }
        } 
        else if (!empty($request->filter_category)) {
            dd('category');
        }

        $exportNew = new NewStatusExport($cases);

        return Excel::download($exportNew, 'NewData.xls');
    }


    public function pendingDataExport(Request $request)
    {

        if (!empty($request->filter_search)) {
            $cases = RequestStatus::where('status', 3)
            ->whereHas('request', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->filter_search . '%');
                $q->orWhereHas('requestClient', function ($query) use ($request) {
                    $query->where('first_name', 'like', "%$request->filter_search%");
                });
            });
        } else if (!empty($request->filter_region)) {

            if ($request->filter_region == 1) {
                $regionName = "Somnath";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 3);
            } else if ($request->filter_region == 2) {
                $regionName = "Dwarka";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 3);
            } else if ($request->filter_region == 3) {
                $regionName = "Rajkot";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 3);
            } else if ($request->filter_region == 4) {
                $regionName = "Bhavnagar";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 3);
            } else if ($request->filter_region == 5) {
                $regionName = "Ahmedabad";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 3);
            }
        } else if (!empty($request->filter_category)) {
            dd('category');
        }

        $exportPending = new PendingStatusExport($cases);

        return Excel::download($exportPending, 'PendingData.xls');
    }


    public function activeDataExport(Request $request)
    {
        if (!empty($request->filter_search)) {
            $cases = RequestStatus::where('status', 4)->orWhere('status', 5)
            ->whereHas('request', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->filter_search . '%');
                $q->orWhereHas('requestClient', function ($query) use ($request) {
                    $query->where('first_name', 'like', "%$request->filter_search%");
                });
            });
        } else if (!empty($request->filter_region)) {

            if ($request->filter_region == 1) {
                $regionName = "Somnath";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 4)->orWhere('status', 5);
            } else if ($request->filter_region == 2) {
                $regionName = "Dwarka";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 4)->orWhere('status', 5);
            } else if ($request->filter_region == 3) {
                $regionName = "Rajkot";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 4)->orWhere('status', 5);
            } else if ($request->filter_region == 4) {
                $regionName = "Bhavnagar";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 4)->orWhere('status', 5);
            } else if ($request->filter_region == 5) {
                $regionName = "Ahmedabad";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 4)->orWhere('status', 5);
            }
        } else if (!empty($request->filter_category)) {
            dd('category');
        }


        $exportActive = new ActiveStatusExport($cases);

        return Excel::download($exportActive, 'ActiveData.xls');
    }


    public function concludeDataExport(Request $request)
    {
        if (!empty($request->filter_search)) {
            $cases = RequestStatus::where('status', 6)
            ->whereHas('request', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->filter_search . '%');
                $q->orWhereHas('requestClient', function ($query) use ($request) {
                    $query->where('first_name', 'like', "%$request->filter_search%");
                });
            });
        } else if (!empty($request->filter_region)) {

            if ($request->filter_region == 1) {
                $regionName = "Somnath";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 6);
            } else if ($request->filter_region == 2) {
                $regionName = "Dwarka";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 6);
            } else if ($request->filter_region == 3) {
                $regionName = "Rajkot";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 6);
            } else if ($request->filter_region == 4) {
                $regionName = "Bhavnagar";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 6);
            } else if ($request->filter_region == 5) {
                $regionName = "Ahmedabad";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 6);
            }
        } else if (!empty($request->filter_category)) {
            dd('category');
        }

        $exportConclude = new ConcludeStatusExport($cases);

        return Excel::download($exportConclude, 'ConcludeData.xls');
    }

    public function toCloseDataExport(Request $request)
    {

        if (!empty($request->filter_search)) {
            $cases = RequestStatus::where('status', 2)->orWhere('status',7)
            ->whereHas('request', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->filter_search . '%');
                $q->orWhereHas('requestClient', function ($query) use ($request) {
                    $query->where('first_name', 'like', "%$request->filter_search%");
                });
            });
        } else if (!empty($request->filter_region)) {

            if ($request->filter_region == 1) {
                $regionName = "Somnath";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 2)->orWhere('status', 7);
            } else if ($request->filter_region == 2) {
                $regionName = "Dwarka";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 2)->orWhere('status', 7);
            } else if ($request->filter_region == 3) {
                $regionName = "Rajkot";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 2)->orWhere('status', 7);
            } else if ($request->filter_region == 4) {
                $regionName = "Bhavnagar";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 2)->orWhere('status', 7);
            } else if ($request->filter_region == 5) {
                $regionName = "Ahmedabad";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 2)->orWhere('status', 7);
            }
        } else if (!empty($request->filter_category)) {
            dd('category');
        }

        $exportToClose = new ToCloseStatusExport($cases);

        return Excel::download($exportToClose, 'ToCloseData.xls');
    }

    public function unPaidDataExport(Request $request)
    {

        if (!empty($request->filter_search)) {
            $cases = RequestStatus::where('status', 9)
            ->whereHas('request', function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->filter_search . '%');
                $q->orWhereHas('requestClient', function ($query) use ($request) {
                    $query->where('first_name', 'like', "%$request->filter_search%");
                });
            });
        } else if (!empty($request->filter_region)) {

            if ($request->filter_region == 1) {
                $regionName = "Somnath";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 9);
            } else if ($request->filter_region == 2) {
                $regionName = "Dwarka";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 9);
            } else if ($request->filter_region == 3) {
                $regionName = "Rajkot";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 9);
            } else if ($request->filter_region == 4) {
                $regionName = "Bhavnagar";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 9);
            } else if ($request->filter_region == 5) {
                $regionName = "Ahmedabad";
                $cases = RequestStatus::with(['request', 'requestClient'])->whereHas('requestClient', function ($query) use ($regionName) {
                    $query->where('state', 'like', '%' . $regionName . '%');
                })->where('status', 9);
            }
        } else if (!empty($request->filter_category)) {
            dd('category');
        }


        $exportUnpaid = new UnPaidStatusExport($cases);

        return Excel::download($exportUnpaid, 'UnPaidData.xls');
    }
}
