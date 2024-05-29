<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePartners;
use App\Models\HealthProfessional;
use App\Models\HealthProfessionalType;
use App\Services\PartnersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PartnersController extends Controller
{
    /**
     * Display Partners/Vendors page
     *
     * @param int $id healthProfessionalType id to show selection on dropdown
     *
     * @return \Illuminate\View\View partners page
     */
    public function viewPartners($id = null)
    {
        if ($id === null || $id === 0) {
            $vendors = HealthProfessional::with('healthProfessionalType')->latest('id')->paginate(10);
        } elseif ($id) {
            $vendors = HealthProfessional::with('healthProfessionalType')->where('profession', $id)->latest('id')->paginate(10);
        }
        $professions = HealthProfessionalType::get();
        $search = null;

        return view('adminPage.partners.partners', compact('vendors', 'professions', 'id', 'search'));
    }

    /**
     * For Searching and filtering Partners
     *
     * Filtering partners based on healthProfessionalType or by search term query to search by business name
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View partners page
     */
    public function searchPartners(Request $request)
    {
        $search = $request->get('search');
        $id = $request->get('profession');
        $page = $request->query('page') ?? 1; // Default to page 1 if no page number provided
        $query = HealthProfessional::with('healthProfessionalType');

        if ($search) {
            $query->where('vendor_name', 'like', "%{$search}%");
        }

        if ($id !== '0') {
            $query->where('profession', $id);
            if ($search) {
                $query->where('profession', $id)->where('vendor_name', 'like', "%{$search}%");
            }
        }
        $vendors = $query->orderByDesc('id')->paginate(10, ['*'], 'page', $page);
        $professions = HealthProfessionalType::get();

        return view('adminPage.partners.partners', compact('vendors', 'professions', 'id', 'search'));
    }

    /**
     * Display Add Business page
     *
     * @return \Illuminate\View\View partners page
     */
    public function addBusinessView()
    {
        $types = HealthProfessionalType::get();
        return view('adminPage.partners.addBusiness', compact('types'));
    }

    /**
     * Add Business entry in partners/vendors
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse redirect back with success message
     */
    public function addBusiness(CreatePartners $request, PartnersService $partnersService)
    {
        $partnersService->createBusiness($request);
        return redirect()->route('admin.partners')->with('businessAdded', 'Business Added Successfully!');
    }

    /**
     * Display the form to update the business page.
     *
     * @param string $id
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function updateBusinessView($id)
    {
        try {
            $caseId = Crypt::decrypt($id);
            // HealthProfessional Id whose value need to be updated
            $vendor = HealthProfessional::where('id', $caseId)->first();
            $professions = HealthProfessionalType::get();
            return view('adminPage.partners.updateBusiness', compact('vendor', 'professions'));
        } catch (\Throwable $th) {
            return view('errors.404');
        }
    }

    /**
     * Update business data based on the provided request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateBusiness(CreatePartners $request, PartnersService $partnersService)
    {
        $partnersService->updateBusiness($request);
        return redirect()->route('admin.partners')->with('changesSaved', 'Changes Saved Successfully!');
    }

    /**
     * Delete a business from the vendors page.
     *
     * @param int|null $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteBusiness($id = null)
    {
        HealthProfessional::where('id', $id)->delete();
        return redirect()->back();
    }
}
